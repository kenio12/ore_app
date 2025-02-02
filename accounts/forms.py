from django import forms
from django.contrib.auth.forms import UserCreationForm
from .models import CustomUser

class CustomUserCreationForm(UserCreationForm):
    email = forms.EmailField(
        label='メールアドレス',
        required=True,
        help_text='必須。有効なメールアドレスを入力してください。'
    )

    class Meta:
        model = CustomUser
        fields = ('username', 'email', 'password1', 'password2')

    def __init__(self, *args, **kwargs):
        super().__init__(*args, **kwargs)
        # フィールドのラベルを日本語化
        self.fields['username'].label = 'ユーザー名'
        self.fields['email'].label = 'メールアドレス'
        self.fields['password1'].label = 'パスワード'
        self.fields['password2'].label = 'パスワード（確認用）'
        # ヘルプテキストを日本語化
        self.fields['username'].help_text = '150文字以下で、文字、数字、@/./+/-/_ のみ使用可能です。'
        self.fields['password1'].help_text = '''
            ・8文字以上
            ・数字だけのパスワードは使えません
            ・よく使われるパスワードは使えません
            ・ユーザー名と似たパスワードは使えません
        '''
        self.fields['password2'].help_text = '確認のため、同じパスワードを入力してください。'

    def clean_email(self):
        email = self.cleaned_data.get('email')
        if CustomUser.objects.filter(email=email).exists():
            raise forms.ValidationError('このメールアドレスは既に使用されています。')
        return email

    def clean_username(self):
        username = self.cleaned_data.get('username')
        if CustomUser.objects.filter(username=username).exists():
            raise forms.ValidationError('このユーザー名は既に使用されています。')
        return username 