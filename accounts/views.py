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
from django.views.decorators.csrf import csrf_protect
from django.utils.decorators import method_decorator

# Create your views here.

@method_decorator(csrf_protect, name='dispatch')
class SignUpView(CreateView):
    model = CustomUser  # カスタムユーザーモデルを指定
    form_class = CustomUserCreationForm
    success_url = reverse_lazy('login')
    template_name = 'registration/signup.html'

    def form_valid(self, form):
        user = form.save(commit=False)
        user.is_active = False
        user.save()

        # 確認メールを送信
        token = default_token_generator.make_token(user)
        uid = urlsafe_base64_encode(force_bytes(user.pk))
        verification_url = self.request.build_absolute_uri(
            reverse_lazy('accounts:verify_email', kwargs={'uidb64': uid, 'token': token})
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

        msg = EmailMultiAlternatives(
            subject,
            text_content,
            'from@example.com',
            [user.email]
        )
        msg.attach_alternative(html_content, "text/html")
        msg.send()

        messages.success(self.request, '確認メールを送信しました。メールをご確認ください。')
        return super().form_valid(form)

def verify_email(request, uidb64, token):
    try:
        uid = urlsafe_base64_decode(uidb64).decode()
        user = CustomUser.objects.get(pk=uid)  # auth.User から変更
    except (TypeError, ValueError, OverflowError, CustomUser.DoesNotExist):
        user = None

    if user is not None and default_token_generator.check_token(user, token):
        user.is_active = True
        user.email_verified = True
        user.save()
        messages.success(request, 'メールアドレスが確認されました。ログインしてください。')
        return redirect('login')
    else:
        messages.error(request, '無効なリンクです。')
        return redirect('login')
