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
    catchphrases = forms.CharField(
        required=False,
        widget=forms.Textarea(attrs={
            'class': 'form-control cyber-green-focus',
            'rows': '8'
        })
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
            'catchphrases'
        ]

    def __init__(self, *args, **kwargs):
        super().__init__(*args, **kwargs)
        
        # チェックボックスにサイバーパンクなスタイルを適用
        checkbox_fields = ['app_types', 'genres']
        
        for field_name in checkbox_fields:
            if field_name in self.fields:
                self.fields[field_name].widget.attrs['class'] = 'cyber-checkbox'
        
        # タイトルのみ必須フィールドに設定
        self.fields['title'].required = True

        # キャッチフレーズの初期値を設定
        if 'instance' in kwargs and kwargs['instance']:
            if kwargs['instance'].catchphrases:
                try:
                    # JSON文字列をパースしてリストに変換
                    catchphrases = json.loads(kwargs['instance'].catchphrases)
                    if isinstance(catchphrases, list):
                        self.initial['catchphrases'] = catchphrases
                    else:
                        self.initial['catchphrases'] = []
                except (json.JSONDecodeError, TypeError):
                    self.initial['catchphrases'] = []
                print(f"Initial catchphrases: {self.initial['catchphrases']}")  # デバッグ出力

        # フィールドのカスタマイズ
        self.fields['title'].widget.attrs.update({
            'class': 'form-control cyber-yellow-focus',
            'placeholder': 'アプリの名前を入力してください'
        })

        self.fields['app_url'].widget.attrs.update({
            'class': 'form-control cyber-yellow-focus',
            'placeholder': 'https://example.com'
        })

        self.fields['github_url'].widget.attrs.update({
            'class': 'form-control cyber-yellow-focus',
            'placeholder': 'https://github.com/username/repository'
        })

        # テキストエリアのカスタマイズ
        text_areas = ['overview', 'motivation', 'catchphrases', 
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

    def clean_catchphrases(self):
        """キャッチフレーズのバリデーションと変換"""
        catchphrases = self.cleaned_data.get('catchphrases', '')
        
        # デバッグ出力
        print(f"Cleaning catchphrases: {catchphrases}")
        print(f"Type: {type(catchphrases)}")

        # 空の場合は空リストを返す
        if not catchphrases:
            return []

        # 文字列の場合はJSONとしてパース
        if isinstance(catchphrases, str):
            try:
                parsed = json.loads(catchphrases)
                if isinstance(parsed, list):
                    return parsed
                return []
            except json.JSONDecodeError:
                # 単一の文字列の場合は1要素のリストとして扱う
                if catchphrases.strip():
                    return [catchphrases.strip()]
                return []

        # リストの場合はそのまま返す
        if isinstance(catchphrases, list):
            return catchphrases

        # その他の型の場合は空リストを返す
        return []

    def clean(self):
        """フォーム全体のバリデーション"""
        cleaned_data = super().clean()
        
        # キャッチフレーズが存在しない場合は空リストを設定
        if 'catchphrases' not in cleaned_data:
            cleaned_data['catchphrases'] = []
            
        # デバッグ出力
        print(f"Final cleaned data: {cleaned_data}")
        
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