from django.shortcuts import render, redirect, get_object_or_404
from django.contrib.auth.decorators import login_required
from django.contrib.auth import get_user_model
from django.http import JsonResponse, StreamingHttpResponse, HttpResponseForbidden
from .models import Conversation, Message
import json
from django.utils import timezone
import threading
import time
from django.core.cache import cache

# Create your views here.

# メッセージストリーム用のグローバル変数
message_queues = {}
message_lock = threading.Lock()

# キャッシュキーのプレフィックス
CACHE_PREFIX = 'chat_notification_'

# キャッシュからメッセージキューを取得
def get_cached_messages(user_id):
    cache_key = f"{CACHE_PREFIX}{user_id}"
    messages = cache.get(cache_key, [])
    return messages

# キャッシュにメッセージキューを保存
def save_cached_messages(user_id, messages):
    cache_key = f"{CACHE_PREFIX}{user_id}"
    cache.set(cache_key, messages, timeout=86400)  # 24時間有効

@login_required
def chat_list(request):
    """ユーザーの会話一覧を表示"""
    # ユーザーが参加している会話を取得
    conversations = Conversation.objects.filter(participants=request.user).order_by('-updated_at')
    
    # 各会話の相手と最新メッセージを取得
    chat_info = []
    for conversation in conversations:
        # 会話の相手（自分以外の参加者）を取得
        other_participants = conversation.participants.exclude(id=request.user.id)
        
        if other_participants.exists():
            other_user = other_participants.first()
            # 最新のメッセージを取得
            messages = conversation.get_messages().order_by('-timestamp')
            latest_message = messages.first() if messages.exists() else None
            
            # 未読メッセージ数をカウント
            unread_count = messages.filter(recipient=request.user, is_read=False).count()
            
            chat_info.append({
                'conversation': conversation,
                'other_user': other_user,
                'latest_message': latest_message,
                'unread_count': unread_count
            })
    
    context = {
        'chat_info': chat_info
    }
    
    return render(request, 'chats/chat_list.html', context)

@login_required
def chat_detail(request, conversation_id=None, user_id=None):
    """特定の会話を表示するか、新しい会話を開始する"""
    User = get_user_model()
    
    # 会話IDまたはユーザーIDのどちらかが必要
    if conversation_id is None and user_id is None:
        return redirect('chats:chat_list')
    
    # ユーザーIDが指定されている場合は、そのユーザーとの会話を取得または作成
    if user_id is not None:
        other_user = get_object_or_404(User, id=user_id)
        conversation = Conversation.get_or_create_conversation(request.user, other_user)
    else:
        # 会話IDが指定されている場合は、その会話を取得
        conversation = get_object_or_404(Conversation, id=conversation_id)
        
        # 自分が参加していない会話にはアクセスできない
        if request.user not in conversation.participants.all():
            return redirect('chats:chat_list')
        
        # 相手のユーザーを取得
        other_user = conversation.participants.exclude(id=request.user.id).first()
    
    # クエリパラメータをチェック - 招待からのアクセスの場合は入室通知を送信しない
    from_invitation = request.GET.get('from_invitation', '0') == '1'
    
    # 招待からのアクセスでない場合のみ入室メッセージを送信
    if not from_invitation:
        # チャットルームに入ったことを示す通知メッセージを作成（相手に通知するため）
        try:
            message = Message.objects.create(
                sender=request.user,
                recipient=other_user,
                conversation=conversation,
                content=f"{request.user.username}がチャットルームに入りました",
                is_read=False,
                message_type='enter'  # 入室メッセージであることを明示
            )
            # リアルタイム通知を送信（送信者自身には通知しない）
            notify_new_message(request.user, other_user, message, notify_sender=False)
            print(f"🔔 {request.user.username}が{other_user.username}とのチャットルームに入室")
        except Exception as e:
            # エラーがあっても処理は続行
            print(f"❌ チャット入室通知の作成エラー: {str(e)}")
    else:
        print(f"📣 招待からのアクセスのため、入室通知をスキップします: {request.user.username} -> {other_user.username}")
    
    # 自分宛のメッセージを既読にする
    Message.objects.filter(
        sender=other_user,
        recipient=request.user,
        is_read=False
    ).update(is_read=True)
    
    # 会話のメッセージを取得（最新の50件のみ）
    messages_list = list(conversation.get_messages().order_by('-timestamp')[:50])
    messages_list.reverse()  # タイムスタンプの昇順に並べ替え
    
    # メッセージの総数を取得
    total_messages = conversation.get_messages().count()
    has_older_messages = total_messages > 50
    
    context = {
        'conversation': conversation,
        'other_user': other_user,
        'messages': messages_list,
        'has_older_messages': has_older_messages,
        'total_messages': total_messages
    }
    
    return render(request, 'chats/chat_detail.html', context)

@login_required
def send_message(request, conversation_id):
    """APIエンドポイント: メッセージを送信する"""
    if request.method == 'POST':
        conversation = get_object_or_404(Conversation, id=conversation_id)
        if request.user not in conversation.participants.all():
            return JsonResponse({'status': 'error', 'message': 'このチャットに参加する権限がありません。'})
        
        content = request.POST.get('content', '').strip()
        if content:
            # 受信者を特定
            recipient = conversation.participants.exclude(id=request.user.id).first()
            
            message = Message.objects.create(
                conversation=conversation,
                sender=request.user,
                recipient=recipient,  # 受信者を設定
                content=content,
                is_read=False
            )
            
            # 会話の最終更新時間を更新
            conversation.save()  # auto_now=Trueフィールドを更新
            
            # 新しいメッセージを通知（送信者自身には通知しない）
            notify_new_message(request.user, recipient, message, notify_sender=False)
            
            return JsonResponse({
                'status': 'success',
                'message': {
                    'id': message.id,
                    'content': message.content,
                    'timestamp': message.timestamp.isoformat(),
                    'sender_name': message.sender.username,
                    'is_mine': True
                }
            })
        return JsonResponse({'status': 'error', 'message': 'メッセージを入力してください。'})
    return JsonResponse({'status': 'error', 'message': '不正なリクエストです。'})

@login_required
def get_messages(request, conversation_id):
    """APIエンドポイント: 会話のメッセージを取得する"""
    try:
        # 会話を取得
        conversation = Conversation.objects.get(id=conversation_id, participants=request.user)
        
        # 最後に取得したメッセージのID（ポーリングで新しいメッセージのみを取得するため）
        last_id = request.GET.get('last_id')
        
        # メッセージを取得
        messages_query = conversation.get_messages().order_by('timestamp')
        
        if last_id:
            messages_query = messages_query.filter(id__gt=last_id)
        
        # 相手のユーザーを取得
        other_user = conversation.participants.exclude(id=request.user.id).first()
        
        # 未読メッセージを既読にする
        Message.objects.filter(
            sender=other_user,
            recipient=request.user,
            is_read=False
        ).update(is_read=True)
        
        # メッセージをJSONに変換
        messages_data = []
        for msg in messages_query:
            messages_data.append({
                'id': msg.id,
                'sender_id': msg.sender.id,
                'sender_name': msg.sender.username,
                'content': msg.content,
                'timestamp': msg.timestamp.strftime('%Y-%m-%d %H:%M:%S'),
                'is_mine': msg.sender.id == request.user.id
            })
        
        return JsonResponse({
            'status': 'success',
            'messages': messages_data,
            'conversation_id': conversation.id
        })
        
    except Conversation.DoesNotExist:
        return JsonResponse({'status': 'error', 'message': '会話が見つかりません'}, status=404)
    except Exception as e:
        return JsonResponse({'status': 'error', 'message': str(e)}, status=500)

@login_required
def get_unread_count(request):
    """未読メッセージの数を取得するAPI"""
    # 未読メッセージの数をカウント
    unread_count = Message.objects.filter(recipient=request.user, is_read=False).count()
    
    return JsonResponse({
        'unread_count': unread_count
    })

@login_required
def get_unread_messages(request):
    """未読メッセージのリストを取得するAPI"""
    try:
        # 現在の時刻を取得
        current_time = timezone.now()
        
        # デバッグ用：リクエスト情報を出力
        print(f"🔍 未読メッセージAPI呼び出し: ユーザー={request.user.username}, 時刻={current_time}")
        
        # 送信者ごとに最新の未読メッセージのみを取得するためのクエリ
        # 1. 送信者ごとにグループ化して最新のメッセージIDを取得
        sender_latest_messages = {}
        
        # 未読メッセージを取得
        unread_messages = Message.objects.select_related('sender', 'conversation').filter(
            recipient=request.user,
            is_read=False,
            timestamp__gte=current_time - timezone.timedelta(minutes=30)
        ).order_by('-timestamp')
        
        # 送信者ごとに最新のメッセージだけを保持
        for message in unread_messages:
            sender_id = message.sender.id
            if sender_id not in sender_latest_messages:
                sender_latest_messages[sender_id] = message
        
        # 最新のメッセージだけを含むリストを作成
        unread_query = list(sender_latest_messages.values())
        
        # デバッグ用：クエリの詳細を出力
        print(f"🔍 未読メッセージクエリ: 送信者ごとに最新の1件に制限")
        
        # 未読メッセージがある場合のみログを出力
        if unread_query:
            print(f"🔔 新着メッセージ: {len(unread_query)}件")
            
            # デバッグ用：各メッセージの詳細を出力
            for i, msg in enumerate(unread_query):
                print(f"🔔 メッセージ {i+1}:")
                print(f"  - ID: {msg.id}")
                print(f"  - 送信者: {msg.sender.username} (ID: {msg.sender.id})")
                print(f"  - 内容: {msg.content[:50]}...")
                print(f"  - タイプ: {msg.message_type}")
                print(f"  - 会話ID: {msg.conversation.id if msg.conversation else 'なし'}")
                print(f"  - 送信日時: {msg.timestamp}")
        else:
            print("📭 未読メッセージはありません")
        
        # JSONシリアライズ可能な形式に変換
        messages_data = []
        for message in unread_query:
            sender = message.sender
            sender_avatar = None
            
            # プロフィール情報の安全な取得
            if hasattr(sender, 'profile'):
                profile = sender.profile
                if hasattr(profile, 'avatar_url') and profile.avatar_url:
                    sender_avatar = profile.avatar_url
            
            messages_data.append({
                'id': message.id,
                'content': message.content,
                'timestamp': message.timestamp.isoformat(),
                'sender_id': sender.id,
                'sender_name': sender.username,
                'sender_avatar': sender_avatar,
                'conversation_id': message.conversation.id if message.conversation else None,
                'message_type': message.message_type
            })
        
        return JsonResponse({
            'status': 'success',
            'unread_messages': messages_data
        })
        
    except Exception as e:
        print(f"❌ 重大なエラー: {str(e)}")
        return JsonResponse({
            'status': 'error',
            'message': str(e),
            'unread_messages': []
        }, status=200)

@login_required
def message_stream(request):
    """SSEを使用したリアルタイムメッセージストリーム"""
    user_id = request.user.id
    
    with message_lock:
        # ユーザーごとにメッセージキューを作成（存在しない場合）
        if user_id not in message_queues:
            message_queues[user_id] = []
        
        # デバッグ情報：接続をログに出力
        print(f"🔌 [{request.user.username}] (ID: {user_id}) がSSEに接続しました")
        print(f"🔍 現在のキュー一覧（接続後）: {list(message_queues.keys())}")
    
    # キャッシュから未読メッセージを取得して追加
    cached_messages = get_cached_messages(user_id)
    
    def event_stream():
        """SSEイベントストリーム生成器"""
        # キープアライブ用カウンター
        keepalive_counter = 0
        
        try:
            # キャッシュから取得したメッセージがあれば送信
            if cached_messages:
                for message in cached_messages:
                    # 明示的にチェック: 自分が送信したメッセージは送らない
                    if 'data' in message and 'sender_id' in message['data'] and message['data']['sender_id'] == user_id:
                        print(f"⚠️ キャッシュから取得したメッセージのうち、自分が送信者のメッセージはスキップします: {message}")
                        continue
                    
                    # 送信するメッセージをシリアライズ
                    yield f"data: {json.dumps(message)}\n\n"
                
                # キャッシュをクリア（送信済みとしてマーク）
                save_cached_messages(user_id, [])
            
            # 接続が維持されている間、メッセージを送信し続ける
            while True:
                # キープアライブ送信（30秒ごと）
                keepalive_counter += 1
                if keepalive_counter >= 30:
                    yield ": keepalive\n\n"
                    keepalive_counter = 0
                
                # キューにメッセージがあれば送信
                with message_lock:
                    if user_id in message_queues and message_queues[user_id]:
                        # 自分が送信者のメッセージを除外するフィルタリング
                        filtered_messages = []
                        for message in message_queues[user_id]:
                            # 明示的にチェック: 自分が送信したメッセージは送らない
                            if 'data' in message and 'sender_id' in message['data'] and message['data']['sender_id'] == user_id:
                                print(f"⚠️ 送信キューのメッセージのうち、自分が送信者のメッセージはスキップします: {message}")
                                continue
                            filtered_messages.append(message)
                        
                        # フィルタリング後のメッセージを処理
                        if filtered_messages:
                            for message in filtered_messages:
                                print(f"📤 メッセージを送信: ユーザー={request.user.username}, データ={json.dumps(message)[:100]}...")
                                yield f"data: {json.dumps(message)}\n\n"
                            
                            # 送信済みのメッセージをキューから削除
                            message_queues[user_id] = []
                
                # 100ミリ秒待機
                time.sleep(0.1)
        except Exception as e:
            print(f"❌ SSEストリームエラー: {str(e)}")
        finally:
            # 切断時にキューを削除
            with message_lock:
                if user_id in message_queues:
                    del message_queues[user_id]
            
            # デバッグ情報：切断をログに出力
            print(f"🔌 [{request.user.username}] (ID: {user_id}) がSSEから切断しました")
            print(f"🔍 現在のキュー一覧（切断後）: {list(message_queues.keys())}")
    
    response = StreamingHttpResponse(
        event_stream(),
        content_type='text/event-stream'
    )
    response['Cache-Control'] = 'no-cache'
    response['X-Accel-Buffering'] = 'no'
    return response

def notify_new_message(sender, recipient, message, notify_sender=True):
    """新しいメッセージを受信したユーザーに通知"""
    print(f"=== 新メッセージ通知 ===")
    print(f"送信者: {sender.username} (ID: {sender.id})")
    print(f"受信者: {recipient.username} (ID: {recipient.id})")
    print(f"メッセージID: {message.id}")
    print(f"メッセージ内容: {message.content[:50]}...")
    print(f"会話ID: {message.conversation.id if message.conversation else 'なし'}")
    print(f"送信者に通知する: {notify_sender}")
    
    # デバッグ情報：利用可能なキューの一覧
    with message_lock:
        print(f"🔍 現在のキュー一覧: {list(message_queues.keys())}")
        print(f"🔍 recipient.id = {recipient.id}（{recipient.username}）")
        print(f"🔍 recipient.id がキューに存在するか: {recipient.id in message_queues}")
    
    # 安全に会話IDを取得
    conversation_id = None
    if message.conversation:
        conversation_id = message.conversation.id
    else:
        # 会話がない場合は取得または作成
        print(f"会話オブジェクトがないため作成します")
        conversation = Conversation.get_or_create_conversation(sender, recipient)
        conversation_id = conversation.id
        # メッセージに会話を関連付け
        message.conversation = conversation
        message.save()
        print(f"会話を作成しました（ID: {conversation_id}）")
    
    notification_data = {
        'type': 'new_message',
        'data': {
            'sender_id': sender.id,
            'sender_name': sender.username,
            'sender_avatar': sender.profile.avatar_url if hasattr(sender, 'profile') and hasattr(sender.profile, 'avatar_url') else None,
            'conversation_id': conversation_id,
            'content': message.content,
            'timestamp': timezone.now().isoformat()
        }
    }
    
    # 通知データの形式を確認
    print(f"🔍 通知データ: {json.dumps(notification_data)}")
    
    # 受信者のみ通知キューに追加
    # キャッシュから受信者のメッセージキューを取得
    cached_messages = get_cached_messages(recipient.id)
    print(f"🔍 キャッシュから取得したメッセージ数: {len(cached_messages)}")
    
    # 通知をキャッシュに追加
    cached_messages.append(notification_data)
    save_cached_messages(recipient.id, cached_messages)
    print(f"✅ キャッシュに通知を保存しました。キュー長: {len(cached_messages)}")
    
    # 受信者のキューにメッセージを追加（リアルタイム通知用）
    with message_lock:
        if recipient.id in message_queues:
            print(f"メッセージキューが存在します。通知を追加します。")
            message_queues[recipient.id].append(notification_data)
            print(f"通知処理完了 - キュー長: {len(message_queues[recipient.id])}")
        else:
            print(f"⚠️ 警告: 受信者 {recipient.username} (ID: {recipient.id}) のメッセージキューが見つかりません")
            print(f"⚠️ 現在のキュー一覧: {list(message_queues.keys())}")
            # キューが存在しない場合でも、一時的にキューを作成して通知を保存
            message_queues[recipient.id] = [notification_data]
            print(f"✅ 一時的なキューを作成しました。次回のSSE接続時に通知が表示されます。")
    
    # オプション: 送信者自身にも通知が必要な場合（システムメッセージなど）
    if notify_sender:
        # システムメッセージの場合のみ送信者にも通知する
        sender_notification_data = notification_data.copy()
        with message_lock:
            if sender.id in message_queues:
                message_queues[sender.id].append(sender_notification_data)
                print(f"送信者にも通知を送信しました")

@login_required
def leave_chat(request, conversation_id):
    """APIエンドポイント: チャットルームから入退室する時の通知を作成"""
    if request.method == 'POST':
        try:
            # リクエストボディからJSONデータを取得
            try:
                data = json.loads(request.body)
                action = data.get('action', 'leave')  # デフォルトは'leave'
            except json.JSONDecodeError:
                # JSONでない場合や空の場合は'leave'として扱う
                action = 'leave'
            
            print(f"🔑 受信したアクション: {action}")
            
            # 会話を取得
            conversation = get_object_or_404(Conversation, id=conversation_id)
            
            # 自分が参加していない会話の場合はエラー
            if request.user not in conversation.participants.all():
                return JsonResponse({'status': 'error', 'message': 'このチャットに参加する権限がありません。'})
            
            # 相手のユーザーを取得
            other_user = conversation.participants.exclude(id=request.user.id).first()
            
            if other_user:
                # アクションに応じてメッセージを作成
                if action == 'enter':
                    # 入場メッセージ
                    content = f"{request.user.username}がチャットルームに入りました"
                    message_type = 'enter'
                    action_log = f"🔔 {request.user.username}が{other_user.username}とのチャットルームに入室"
                else:
                    # 退室メッセージ
                    content = f"{request.user.username}がチャットルームから退室しました"
                    message_type = 'leave'
                    action_log = f"🚪 {request.user.username}が{other_user.username}とのチャットルームから退室"
                
                # メッセージを作成
                message = Message.objects.create(
                    conversation=conversation,
                    sender=request.user,
                    recipient=other_user,
                    content=content,
                    is_read=False,
                    message_type=message_type
                )
                
                # 新しいメッセージを通知（リアルタイム通知を送信）- 送信者自身には通知しない
                notify_new_message(request.user, other_user, message, notify_sender=False)
                
                print(action_log)
                
                return JsonResponse({
                    'status': 'success',
                    'message': content
                })
            
            return JsonResponse({'status': 'error', 'message': '相手ユーザーが見つかりません。'})
            
        except Exception as e:
            return JsonResponse({'status': 'error', 'message': str(e)})
    
    return JsonResponse({'status': 'error', 'message': '不正なリクエストです。'})

@login_required
def mark_message_read(request, message_id):
    """APIエンドポイント: 特定のメッセージを既読にマークする"""
    if request.method == 'POST':
        try:
            # メッセージを取得（自分宛のメッセージのみ）
            message = get_object_or_404(Message, id=message_id, recipient=request.user)
            
            # 既読にマーク
            message.is_read = True
            message.save()
            
            return JsonResponse({
                'status': 'success',
                'message': 'メッセージを既読にしました'
            })
            
        except Message.DoesNotExist:
            return JsonResponse({
                'status': 'error', 
                'message': 'メッセージが見つからないか、あなた宛てではありません'
            }, status=404)
            
        except Exception as e:
            return JsonResponse({
                'status': 'error', 
                'message': str(e)
            }, status=500)
    
    return JsonResponse({
        'status': 'error', 
        'message': '不正なリクエストです'
    }, status=400)

@login_required
def get_older_messages(request, conversation_id):
    """APIエンドポイント: 古いメッセージを取得する"""
    try:
        # 会話を取得
        conversation = Conversation.objects.get(id=conversation_id, participants=request.user)
        
        # 最も古いメッセージのID（これより古いメッセージを取得）
        oldest_id = request.GET.get('oldest_id')
        
        # 一度に取得するメッセージ数
        limit = 20
        
        # メッセージを取得
        messages_query = conversation.get_messages().order_by('-timestamp')
        
        if oldest_id:
            # 指定されたIDより古いメッセージを取得
            messages_query = messages_query.filter(id__lt=oldest_id)
        
        # 残りのメッセージ数を取得
        remaining_count = messages_query.count() - limit if messages_query.count() > limit else 0
        
        # 指定された数だけメッセージを取得
        messages_query = messages_query[:limit]
        
        # メッセージをJSONに変換
        messages_data = []
        for msg in messages_query:
            messages_data.append({
                'id': msg.id,
                'sender_id': msg.sender.id,
                'sender_name': msg.sender.username,
                'content': msg.content,
                'timestamp': msg.timestamp.strftime('%Y-%m-%d %H:%M:%S'),
                'is_mine': msg.sender.id == request.user.id
            })
        
        return JsonResponse({
            'status': 'success',
            'messages': messages_data,
            'has_more': remaining_count > 0,
            'remaining_count': remaining_count
        })
        
    except Conversation.DoesNotExist:
        return JsonResponse({'status': 'error', 'message': '会話が見つかりません'}, status=404)
    except Exception as e:
        return JsonResponse({'status': 'error', 'message': str(e)}, status=500)
