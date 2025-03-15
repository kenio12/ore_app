from django import forms
from .models import Profile

class ProfileForm(forms.ModelForm):
    avatar = forms.FileField(required=False, label='プロフィール画像', 
                            widget=forms.FileInput(attrs={'class': 'form-control'}))
    
    class Meta:
        model = Profile
        fields = ['bio', 'social_github', 'social_twitter']
        widgets = {
            'bio': forms.Textarea(attrs={'rows': 4, 'class': 'form-control'}),
            'social_github': forms.TextInput(attrs={'class': 'form-control'}),
            'social_twitter': forms.TextInput(attrs={'class': 'form-control'}),
        } 

class ProfileEditForm(forms.ModelForm):
    """プロフィール編集フォーム"""
    
    class Meta:
        model = Profile
        fields = ['bio', 'social_github', 'social_twitter', 'job_status', 'job_types', 'work_rate']
        
    # 仕事タイプのフィールドはModelForm自体から生成されず、ビューで処理する
    job_types = forms.MultipleChoiceField(
        label='受付可能な仕事タイプ',
        required=False,
        choices=[
            ('frontend', 'フロントエンド開発'),
            ('backend', 'バックエンド開発'),
            ('fullstack', 'フルスタック開発'),
            ('mobile', 'モバイルアプリ開発'),
            ('database', 'データベース設計・最適化'),
            ('infrastructure', 'インフラ構築・運用'),
            ('design', 'UI/UXデザイン'),
            ('consulting', '技術コンサルティング'),
            ('other', 'その他'),
        ],
        widget=forms.CheckboxSelectMultiple,
        help_text="受付可能な仕事の種類を選択してください"
    ) 