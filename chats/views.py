from django.shortcuts import render, redirect, get_object_or_404
from django.contrib.auth.decorators import login_required
from django.contrib.auth import get_user_model
from django.http import JsonResponse
from .models import Conversation, Message
import json
from django.utils import timezone

# Create your views here.

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
    
    # メッセージを既読にする
    Message.objects.filter(
        sender=other_user,
        recipient=request.user,
        is_read=False
    ).update(is_read=True)
    
    # 会話のメッセージを取得
    messages_list = conversation.get_messages().order_by('timestamp')
    
    context = {
        'conversation': conversation,
        'other_user': other_user,
        'messages': messages_list
    }
    
    return render(request, 'chats/chat_detail.html', context)

@login_required
def send_message(request):
    """APIエンドポイント: メッセージを送信する"""
    if request.method == 'POST':
        try:
            data = json.loads(request.body)
            recipient_id = data.get('recipient_id')
            content = data.get('content', '').strip()
            
            # 内容が空の場合は処理しない
            if not content or not recipient_id:
                return JsonResponse({'status': 'error', 'message': '受信者IDとメッセージ内容が必要です'}, status=400)
            
            # メッセージ長さの制限（例: 1000文字まで）
            if len(content) > 1000:
                return JsonResponse({'status': 'error', 'message': 'メッセージは1000文字以内にしてください'}, status=400)
            
            # 受信者を取得
            User = get_user_model()
            try:
                recipient = User.objects.get(id=recipient_id)
            except User.DoesNotExist:
                return JsonResponse({'status': 'error', 'message': 'ユーザーが見つかりません'}, status=404)
            
            # 会話を取得または作成
            conversation = Conversation.get_or_create_conversation(request.user, recipient)
            
            # 前回と同じ内容のメッセージを防止（オプション）
            last_message = Message.objects.filter(sender=request.user, recipient=recipient).order_by('-timestamp').first()
            if last_message and last_message.content == content and (timezone.now() - last_message.timestamp).seconds < 10:
                return JsonResponse({'status': 'error', 'message': '同じメッセージが連続して送信されました'}, status=400)
            
            # メッセージを作成
            message = Message.objects.create(
                sender=request.user,
                recipient=recipient,
                content=content,
                conversation=conversation
            )
            
            # 会話の更新日時を更新
            conversation.save()  # updated_at が自動的に更新される
            
            return JsonResponse({
                'status': 'success',
                'conversation_id': conversation.id,
                'message': {
                    'id': message.id,
                    'content': message.content,
                    'timestamp': message.timestamp.strftime('%Y-%m-%d %H:%M:%S'),
                    'is_read': message.is_read
                }
            })
            
        except json.JSONDecodeError:
            return JsonResponse({'status': 'error', 'message': '無効なJSONデータ'}, status=400)
        except Exception as e:
            return JsonResponse({'status': 'error', 'message': str(e)}, status=500)
    
    return JsonResponse({'status': 'error', 'message': 'POSTメソッドのみ許可されています'}, status=405)

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
        print("=== get_unread_messages 開始 ===")
        # クエリパラメータから前回チェック時間を取得
        since = request.GET.get('since', None)
        print(f"since パラメータ: {since}")
        
        # 未読メッセージのクエリを作成
        unread_query = Message.objects.select_related('sender', 'conversation').filter(
            recipient=request.user,
            is_read=False
        )
        print(f"未読メッセージ件数: {unread_query.count()}")
        
        # 前回チェック時間以降のメッセージに限定（指定があれば）
        if since:
            try:
                print(f"since の処理開始: {since}")
                # タイムゾーン対応のISOフォーマット変換（両方の形式をサポート）
                if 'Z' in since:
                    since_datetime = timezone.datetime.strptime(
                        since.replace('Z', '+0000'), 
                        "%Y-%m-%dT%H:%M:%S.%f%z"
                    )
                else:
                    since_datetime = timezone.datetime.fromisoformat(since)
                
                print(f"変換後の since_datetime: {since_datetime}")
                
                # タイムゾーン対応
                if timezone.is_naive(since_datetime):
                    since_datetime = timezone.make_aware(since_datetime)
                    print(f"タイムゾーン適用後: {since_datetime}")
                
                unread_query = unread_query.filter(timestamp__gt=since_datetime)
                print(f"フィルター後の未読メッセージ件数: {unread_query.count()}")
            except Exception as e:
                print(f"日付変換エラーの詳細: {str(e)}")
                print(f"エラータイプ: {type(e)}")
                import traceback
                print(f"スタックトレース: {traceback.format_exc()}")
        
        # 未読メッセージを新しい順に取得
        unread_messages = unread_query.order_by('timestamp')
        
        # レスポンス用のデータを整形
        messages_data = []
        for message in unread_messages:
            try:
                print(f"メッセージID {message.id} の処理開始")
                # 送信者情報
                sender = message.sender
                sender_avatar = None
                
                # プロフィール情報の安全な取得
                if hasattr(sender, 'profile'):
                    profile = sender.profile
                    if hasattr(profile, 'avatar_url') and profile.avatar_url:
                        sender_avatar = profile.avatar_url
                
                # 会話情報の取得（存在しない場合は作成）
                conversation = message.conversation
                if not conversation:
                    print(f"会話が存在しないため作成: sender={sender.id}, recipient={message.recipient.id}")
                    conversation = Conversation.get_or_create_conversation(
                        sender,
                        message.recipient
                    )
                    message.conversation = conversation
                    message.save()
                
                message_data = {
                    'id': message.id,
                    'content': message.content,
                    'timestamp': message.timestamp.isoformat(),
                    'sender_id': sender.id,
                    'sender_name': sender.username,
                    'sender_avatar': sender_avatar,
                    'conversation_id': conversation.id if conversation else None
                }
                print(f"メッセージデータ作成完了: {message_data}")
                messages_data.append(message_data)
            except Exception as e:
                print(f"メッセージ {message.id} の処理でエラー: {str(e)}")
                print(f"エラータイプ: {type(e)}")
                import traceback
                print(f"スタックトレース: {traceback.format_exc()}")
                continue
        
        print(f"処理完了: {len(messages_data)} 件のメッセージを返します")
        return JsonResponse({
            'status': 'success',
            'unread_messages': messages_data
        })
        
    except Exception as e:
        print("=== 重大なエラーが発生 ===")
        print(f"エラーメッセージ: {str(e)}")
        print(f"エラータイプ: {type(e)}")
        import traceback
        print(f"スタックトレース: {traceback.format_exc()}")
        return JsonResponse({
            'status': 'error',
            'message': f'未読メッセージの取得中にエラーが発生しました: {str(e)}',
            'unread_messages': []
        }, status=200)
