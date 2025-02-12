from django import forms
import json
from .models import AppGallery
from .constants.app_info import (
    APP_TYPES,
    GENRES,
    APP_STATUS,
    PUBLISH_STATUS
)
from .constants.tech_stack import (
    FRONTEND_LANGUAGES,
    FRONTEND_FRAMEWORKS,
    BACKEND_LANGUAGES,
    BACKEND_FRAMEWORKS,
    DATABASE_TYPES
)
from .constants.development import (
    TEAM_SIZES,
    VIRTUALIZATION_TOOLS,
    INFRASTRUCTURE
)
from .constants.development_period import (
    DEVELOPMENT_PERIODS,
    DEVELOPMENT_PHASES
)
from .constants.architecture import (
    ARCHITECTURE_PATTERNS,
    DESIGN_PATTERNS,
    SECURITY_MEASURES,
    TESTING_TOOLS,
    CODE_QUALITY_TOOLS
)
from .constants.hardware import (
    DEVICE_TYPES,
    OS_TYPES,
    CPU_TYPES,
    MEMORY_SIZES,
    STORAGE_TYPES,
    MONITOR_COUNTS,
    MONITOR_SIZES,
    MONITOR_RESOLUTIONS
)

class AppForm(forms.ModelForm):
    # フィールド名をPOSTデータに合わせる
    app_types = forms.MultipleChoiceField(
        choices=[(k, v) for k, v in APP_TYPES.items()],
        required=False
    )
    genres = forms.MultipleChoiceField(
        choices=[(k, v) for k, v in GENRES.items()],
        required=False
    )
    dev_status = forms.ChoiceField(
        choices=[(k, v) for k, v in APP_STATUS.items()],
        required=False
    )
    status = forms.ChoiceField(
        choices=[(k, v) for k, v in PUBLISH_STATUS.items()],
        required=False
    )

    class Meta:
        model = AppGallery
        fields = [
            'title',
            'genres',
            'app_types',
            'dev_status',
            'status',
            'app_url',
            'github_url',
            'overview',
            'motivation',
            'target_users',
            'problems',
            'final_appeal',
            'catchphrase_1',
            'catchphrase_2',
            'catchphrase_3'
        ]

    def __init__(self, *args, **kwargs):
        super().__init__(*args, **kwargs)
        
        # チェックボックスにサイバーパンクなスタイルを適用
        checkbox_fields = ['app_types', 'genres']
        
        for field_name in checkbox_fields:
            if field_name in self.fields:
                self.fields[field_name].widget.attrs['class'] = 'cyber-checkbox'
        
        # タイトルを必須から任意に変更
        self.fields['title'].required = False

        # フィールドのカスタマイズ
        self.fields['title'].widget.attrs.update({
            'class': 'form-control cyber-green-focus',
            'placeholder': 'アプリの名前を入力してください（任意）'
        })

        self.fields['app_url'].widget.attrs.update({
            'class': 'form-control cyber-green-focus',
            'placeholder': 'https://example.com'
        })

        self.fields['github_url'].widget.attrs.update({
            'class': 'form-control cyber-green-focus',
            'placeholder': 'https://github.com/username/repository'
        })

        # テキストエリアのカスタマイズ
        text_areas = ['overview', 'motivation', 
                     'target_users', 'problems', 'final_appeal']
        for field in text_areas:
            if field in self.fields:  # フィールドが存在する場合のみ更新
                self.fields[field].widget.attrs.update({
                    'class': 'form-control cyber-green-focus',
                    'rows': '8'
                })

    def clean_app_types(self):
        app_types = self.cleaned_data.get('app_types', [])
        if isinstance(app_types, str):
            return [app_types]
        return app_types

    def clean_genres(self):
        genres = self.cleaned_data.get('genres', [])
        if isinstance(genres, str):
            return [genres]
        return genres

    def clean(self):
        """フォーム全体のバリデーション"""
        cleaned_data = super().clean()
        return cleaned_data

    def save(self, commit=True):
        """保存前の処理"""
        instance = super().save(commit=False)
        
        # キャッチフレーズをJSON文字列に変換
        if hasattr(instance, 'catchphrases'):
            if isinstance(instance.catchphrases, list):
                instance.catchphrases = json.dumps(instance.catchphrases)
            elif not instance.catchphrases:
                instance.catchphrases = '[]'
                
        if commit:
            instance.save()
        return instance 