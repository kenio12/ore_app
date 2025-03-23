from django.shortcuts import render, redirect
from django.contrib.auth.tokens import default_token_generator
from django.core.mail import send_mail, EmailMultiAlternatives
from django.template.loader import render_to_string
from django.utils.encoding import force_bytes
from django.utils.http import urlsafe_base64_encode, urlsafe_base64_decode
from django.contrib import messages
from django.urls import reverse_lazy
from django.views.generic import CreateView
from .models import CustomUser  # auth.User から変更
from .forms import CustomUserCreationForm
from django.views.decorators.csrf import csrf_protect, csrf_exempt, ensure_csrf_cookie
from django.utils.decorators import method_decorator
from django.conf import settings

# Create your views here.

@method_decorator(csrf_protect, name='dispatch')
class SignUpView(CreateView):
    model = CustomUser  # カスタムユーザーモデルを指定
    form_class = CustomUserCreationForm
    success_url = reverse_lazy('login')
    template_name = 'registration/signup.html'

    def form_valid(self, form):
        user = form.save(commit=False)
        
        # 開発用：メール確認なしでアカウントを有効化（本番環境では無効にすること）
        user.is_active = True  # 開発中のみTrue、本番では元のFalseに戻す
        
        # 確認コードを生成して保存
        verification_code = user.generate_verification_code()
        user.save()
        
        # 確認メールを送信
        verification_url = self.request.build_absolute_uri(
            reverse_lazy('accounts:verify_email', kwargs={'code': verification_code})
        )
        
        subject = 'メールアドレスの確認'
        text_content = f'''
こんにちは {user.username} さん,

以下のリンクをクリックして、メールアドレスを確認してください：

{verification_url}

このリンクは24時間有効です。

このメールに心当たりがない場合は、無視してください。

よろしくお願いいたします。
'''
        html_content = render_to_string('registration/email_verification.html', {
            'user': user,
            'verification_url': verification_url,
        })

        try:
            print(f"メール送信開始: 宛先={user.email}, 送信元={settings.DEFAULT_FROM_EMAIL}")
            print(f"SMTP設定: HOST={settings.EMAIL_HOST}, PORT={settings.EMAIL_PORT}, USER={settings.EMAIL_HOST_USER}")
            
            msg = EmailMultiAlternatives(
                subject,
                text_content,
                settings.DEFAULT_FROM_EMAIL,
                [user.email]
            )
            msg.attach_alternative(html_content, "text/html")
            msg.send()
            print(f"確認メール送信成功: {user.email}")  # デバッグ用
        except Exception as e:
            print(f"メール送信エラー: {str(e)}")  # デバッグ用
            # メール送信に失敗した場合でもユーザーは作成済み
            messages.warning(self.request, 'アカウントは作成されましたが、確認メールの送信に失敗しました。管理者にお問い合わせください。')
            return super().form_valid(form)

        messages.success(self.request, '確認メールを送信しました。メールをご確認ください。')
        
        return super().form_valid(form)

@csrf_protect
def verify_email(request, code):
    """新しい確認コードでユーザーを認証する方法"""
    print(f"***** メール検証開始 - code={code}")
    
    # 確認コードでユーザーを検索
    try:
        user = CustomUser.objects.get(verification_code=code)
        print(f"***** 確認コードでユーザー取得成功: {user.username}")
        
        # ユーザーを有効化
        user.is_active = True
        user.email_verified = True
        user.verification_code = None  # 使用済みコードを無効化
        user.save()
        
        # 更新後の状態を確認
        user.refresh_from_db()
        print(f"***** 更新後の状態: active={user.is_active}, verified={user.email_verified}")
        
        messages.success(request, 'メールアドレスが確認されました。ログインしてください。')
        
        # ユーザーをログインページに安全にリダイレクト
        response = redirect('login')
        response['Location'] = settings.LOGIN_URL
        return response
    except CustomUser.DoesNotExist:
        print(f"***** 確認コードに一致するユーザーが見つかりません: {code}")
        messages.error(request, '無効な確認コードです。')
    except Exception as e:
        print(f"***** 検証処理エラー: {e}")
        messages.error(request, 'アカウント有効化に問題が発生しました。')
    
    return redirect('login')
