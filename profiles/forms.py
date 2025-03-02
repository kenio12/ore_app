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