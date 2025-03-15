from django.shortcuts import render, redirect
from django.contrib.auth.decorators import login_required
from django.conf import settings
from apps_gallery.models import AppGallery
import dateutil.parser
import os
import cloudinary
import cloudinary.uploader
import base64
from django.apps import apps
from django.contrib import messages

@login_required
def index(request):
    """ダッシュボードのメインページ"""
    context = {
        'user': request.user,
        'recent_apps': AppGallery.objects.filter(author=request.user).order_by('-created_at')[:5],
        'total_apps': AppGallery.objects.filter(author=request.user).count(),
    }
    return render(request, 'dashboard/index.html', context)

@login_required
def apps(request):
    """アプリ管理ページ"""
    context = {
        'apps': AppGallery.objects.filter(author=request.user).order_by('-created_at')
    }
    return render(request, 'dashboard/apps.html', context)

@login_required
def profile(request):
    """プロフィール設定ページ - ダッシュボード内に表示"""
    from profiles.models import Profile
    
    # プロフィール編集フォームをインポート
    from profiles.forms import ProfileEditForm
    
    # CPU・メモリ・ストレージなどのタイプ情報を取得
    from profiles.views import (
        get_pc_types, get_device_types, get_cpu_types, get_memory_sizes,
        get_storage_types, get_monitor_counts, get_internet_types, get_maker_examples
    )
    
    try:
        profile = Profile.objects.get(user=request.user)
    except Profile.DoesNotExist:
        profile = None
    
    if request.method == 'POST':
        print("POSTリクエスト受信:", request.POST)
        
        form = ProfileEditForm(request.POST, instance=profile)
        
        if form.is_valid():
            print("フォームバリデーション成功")
            
            # プロフィールを保存
            profile = form.save(commit=False)
            
            # ハードウェア関連の情報を取得して保存
            hardware_specs = {}
            
            # メーカーと機種情報
            if 'maker' in request.POST and request.POST['maker']:
                hardware_specs['maker'] = request.POST['maker']
            
            if 'model' in request.POST and request.POST['model']:
                hardware_specs['model'] = request.POST['model']
                
            # PCタイプ
            if 'pc_type' in request.POST and request.POST['pc_type']:
                hardware_specs['pc_type'] = request.POST['pc_type']
                
            # デバイスタイプ
            if 'device_type' in request.POST and request.POST['device_type']:
                hardware_specs['device_type'] = request.POST['device_type']
                
            # CPU
            if 'cpu_type' in request.POST and request.POST['cpu_type']:
                hardware_specs['cpu_type'] = request.POST['cpu_type']
                
            # メモリ
            if 'memory_size' in request.POST and request.POST['memory_size']:
                hardware_specs['memory_size'] = request.POST['memory_size']
                
            # ストレージ
            if 'storage_type' in request.POST and request.POST['storage_type']:
                hardware_specs['storage_type'] = request.POST['storage_type']
                
            # モニター数
            if 'monitor_count' in request.POST and request.POST['monitor_count']:
                hardware_specs['monitor_count'] = request.POST['monitor_count']
                
            # インターネット回線
            if 'internet_type' in request.POST and request.POST['internet_type']:
                hardware_specs['internet_type'] = request.POST['internet_type']
            
            # ハードウェア情報をプロフィールに保存
            profile.hardware_specs = hardware_specs
            profile.save()
            
            # 技術スキル情報を自動更新
            profile.update_skills_from_apps()
            
            messages.success(request, 'プロフィールを更新しました！')
            return redirect('dashboard:profile')
        else:
            print("フォームバリデーションエラー:", form.errors)
            messages.error(request, 'プロフィールの更新に失敗しました。入力内容を確認してください。')
    else:
        form = ProfileEditForm(instance=profile)
        
        # 既存のjob_typesの値をフォームにセット
        if profile and profile.job_types:
            form.initial['job_types'] = profile.job_types
    
    context = {
        'user': request.user,
        'profile': profile,
        'form': form,
        'pc_types': get_pc_types(),
        'device_types': get_device_types(),
        'cpu_types': get_cpu_types(),
        'memory_sizes': get_memory_sizes(),
        'storage_types': get_storage_types(),
        'monitor_counts': get_monitor_counts(),
        'internet_types': get_internet_types(),
        'maker_examples': get_maker_examples(),
    }
    
    return render(request, 'dashboard/profile.html', context)

@login_required
def account(request):
    """アカウント設定ページ"""
    # セッション情報を取得
    sessions = []
    
    # 現在のセッションキー
    current_session_key = request.session.session_key
    
    # すべてのセッション情報を取得（Django 1.6以降のセッションモデルを使用）
    from django.contrib.sessions.models import Session
    from django.utils import timezone
    
    active_sessions = Session.objects.filter(expire_date__gt=timezone.now())
    
    for session in active_sessions:
        session_data = session.get_decoded()
        # ユーザーIDが現在のユーザーと一致するセッションのみを表示
        if '_auth_user_id' in session_data and int(session_data['_auth_user_id']) == request.user.id:
            # セッション情報を作成
            user_agent = session_data.get('user_agent', 'Unknown Browser')
            ip_address = session_data.get('ip_address', 'Unknown IP')
            
            # 最終アクティビティ時間の取得（ミドルウェアで設定された場合）
            if 'last_activity' in session_data:
                try:
                    # ISO形式の文字列からdatetimeオブジェクトに変換
                    last_activity = dateutil.parser.parse(session_data['last_activity'])
                except:
                    # 変換エラーの場合はセッション期限から推定
                    last_activity = session.expire_date - timezone.timedelta(seconds=settings.SESSION_COOKIE_AGE)
            else:
                # 最終アクティビティが記録されていない場合はセッション期限から推定
                last_activity = session.expire_date - timezone.timedelta(seconds=settings.SESSION_COOKIE_AGE)
            
            sessions.append({
                'key': session.session_key,
                'device': user_agent,
                'ip_address': ip_address,
                'last_activity': last_activity,
                'is_current': session.session_key == current_session_key
            })
    
    context = {
        'user': request.user,
        'sessions': sessions
    }
    
    return render(request, 'dashboard/account.html', context)

@login_required
def change_password(request):
    """パスワード変更処理"""
    if request.method == 'POST':
        from django.contrib.auth.forms import PasswordChangeForm
        from django.contrib.auth import update_session_auth_hash
        from django.contrib import messages
        
        form = PasswordChangeForm(request.user, request.POST)
        if form.is_valid():
            user = form.save()
            # パスワード変更後もログイン状態を維持
            update_session_auth_hash(request, user)
            messages.success(request, 'パスワードが変更されました！')
            return redirect('dashboard:account')
        else:
            for error in form.errors.values():
                messages.error(request, error)
    
    return redirect('dashboard:account')

@login_required
def change_email(request):
    """メールアドレス変更処理"""
    if request.method == 'POST':
        from django.contrib.auth import authenticate
        from django.contrib import messages
        
        password = request.POST.get('password')
        new_email = request.POST.get('new_email')
        
        # 入力チェック
        if not password or not new_email:
            messages.error(request, '必須項目が入力されていません。')
            return redirect('dashboard:account')
        
        # 現在のパスワードを確認
        user = authenticate(username=request.user.username, password=password)
        if user is None:
            messages.error(request, 'パスワードが正しくありません。')
            return redirect('dashboard:account')
        
        # 既存のアカウントとメールアドレスの重複チェック
        CustomUser = apps.get_model('accounts', 'CustomUser')
        
        if CustomUser.objects.filter(email=new_email).exclude(id=request.user.id).exists():
            messages.error(request, 'このメールアドレスは既に使用されています。')
            return redirect('dashboard:account')
        
        # メールアドレスを更新
        user.email = new_email
        user.email_verified = False  # 新しいメールアドレスは未認証状態に
        user.save()
        
        # 確認メール送信（オプション）
        from django.contrib.auth.tokens import default_token_generator
        from django.utils.encoding import force_bytes
        from django.utils.http import urlsafe_base64_encode
        from django.urls import reverse_lazy
        from django.core.mail import EmailMultiAlternatives
        from django.template.loader import render_to_string
        
        token = default_token_generator.make_token(user)
        uid = urlsafe_base64_encode(force_bytes(user.pk))
        verification_url = request.build_absolute_uri(
            reverse_lazy('accounts:verify_email', kwargs={'uidb64': uid, 'token': token})
        )
        
        subject = 'メールアドレスの確認'
        text_content = f'''
こんにちは {user.username} さん,

以下のリンクをクリックして、新しいメールアドレスを確認してください：

{verification_url}

このリンクは24時間有効です。

このメールに心当たりがない場合は、無視してください。

よろしくお願いいたします。
'''
        html_content = render_to_string('registration/email_verification.html', {
            'user': user,
            'verification_url': verification_url,
        })
        
        msg = EmailMultiAlternatives(
            subject,
            text_content,
            'from@example.com',
            [user.email]
        )
        msg.attach_alternative(html_content, "text/html")
        msg.send()
        
        messages.success(request, '新しいメールアドレスに確認メールを送信しました。メールをご確認ください。')
        
    return redirect('dashboard:account')

@login_required
def terminate_session(request):
    """特定のセッションを終了"""
    if request.method == 'POST':
        from django.contrib.sessions.models import Session
        from django.contrib import messages
        
        session_key = request.POST.get('session_key')
        if session_key and session_key != request.session.session_key:  # 現在のセッションは終了できない
            try:
                Session.objects.filter(session_key=session_key).delete()
                messages.success(request, 'セッションが終了しました。')
            except Exception:
                messages.error(request, 'セッションの終了に失敗しました。')
    
    return redirect('dashboard:account')

@login_required
def terminate_all_sessions(request):
    """すべてのセッションを終了（現在のセッションを除く）"""
    if request.method == 'POST':
        from django.contrib.sessions.models import Session
        from django.contrib import messages
        
        current_session_key = request.session.session_key
        
        try:
            # 現在のセッション以外のユーザーのセッションをすべて削除
            from django.utils import timezone
            
            # すべてのアクティブなセッション
            active_sessions = Session.objects.filter(expire_date__gt=timezone.now())
            
            for session in active_sessions:
                session_data = session.get_decoded()
                # ユーザーIDが現在のユーザーと一致し、現在のセッションでないものを削除
                if '_auth_user_id' in session_data and \
                   int(session_data['_auth_user_id']) == request.user.id and \
                   session.session_key != current_session_key:
                    session.delete()
            
            messages.success(request, 'すべてのセッションが終了しました。（現在のセッションを除く）')
        except Exception:
            messages.error(request, 'セッションの終了に失敗しました。')
    
    return redirect('dashboard:account')

@login_required
def delete_account(request):
    """アカウント削除処理"""
    if request.method == 'POST':
        from django.contrib.auth import authenticate, logout
        from django.contrib import messages
        
        password = request.POST.get('password')
        confirm_delete = request.POST.get('confirm_delete') == 'on'
        
        # チェックボックスが選択されているか確認
        if not confirm_delete:
            messages.error(request, '削除の確認にチェックを入れてください。')
            return redirect('dashboard:account')
        
        # パスワードを確認
        user = authenticate(username=request.user.username, password=password)
        if user is None:
            messages.error(request, 'パスワードが正しくありません。')
            return redirect('dashboard:account')
        
        # ユーザーを削除
        try:
            # 関連するセッションをすべて削除
            from django.contrib.sessions.models import Session
            from django.utils import timezone
            
            active_sessions = Session.objects.filter(expire_date__gt=timezone.now())
            for session in active_sessions:
                session_data = session.get_decoded()
                if '_auth_user_id' in session_data and int(session_data['_auth_user_id']) == user.id:
                    session.delete()
            
            # ユーザー削除前にログアウト
            logout(request)
            
            # ユーザーを削除
            user.delete()
            
            messages.success(request, 'アカウントが完全に削除されました。ご利用ありがとうございました。')
            return redirect('home:home')  # ホームページにリダイレクト
        except Exception as e:
            messages.error(request, f'アカウントの削除中にエラーが発生しました: {str(e)}')
            return redirect('dashboard:account')
    
    return redirect('dashboard:account')

@login_required
def notifications(request):
    """通知設定ページ"""
    return render(request, 'dashboard/notifications.html')

@login_required
def analytics(request):
    """アナリティクスページ"""
    return render(request, 'dashboard/analytics.html')

@login_required
def chats(request):
    """チャット一覧ページ - ダッシュボード内に表示"""
    from chats.models import Conversation, Message
    
    # ユーザーが参加している会話を取得
    conversations = Conversation.objects.filter(participants=request.user)
    
    # チャット情報を整理
    chat_info = []
    for conversation in conversations:
        # 相手のユーザーを特定
        other_user = conversation.participants.exclude(id=request.user.id).first()
        if not other_user:
            continue
            
        # 最新メッセージを取得
        latest_message = Message.objects.filter(conversation=conversation).order_by('-timestamp').first()
        
        # 未読メッセージ数を取得
        unread_count = Message.objects.filter(
            conversation=conversation,
            is_read=False,
            sender=other_user
        ).count()
        
        chat_info.append({
            'conversation': conversation,
            'other_user': other_user,
            'latest_message': latest_message,
            'unread_count': unread_count
        })
    
    # 最新メッセージの時間順にソート
    chat_info = sorted(
        chat_info,
        key=lambda x: x['latest_message'].timestamp if x['latest_message'] else x['conversation'].created_at,
        reverse=True
    )
    
    context = {
        'chat_info': chat_info
    }
    
    return render(request, 'dashboard/chats.html', context)
