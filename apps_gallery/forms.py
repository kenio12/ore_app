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
    SECURITY_MEASURES
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
    catchphrases = forms.CharField(required=False)

    class Meta:
        model = AppGallery
        fields = [
            'title',
            'app_types',
            'genres',
            'dev_status',
            'status',
            'app_url',
            'github_url',
            'overview',
            'motivation',
            'catchphrases',
            'target_users',
            'problems',
            'final_appeal'
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
        # POSTデータから直接配列として取得
        catchphrases = self.data.getlist('catchphrases')
        print(f"Received catchphrases: {catchphrases}")  # デバッグ出力
        
        # 空の要素を除去
        cleaned = [phrase.strip() for phrase in catchphrases if phrase and phrase.strip()]
        print(f"Cleaned catchphrases: {cleaned}")  # デバッグ出力
        
        # JSON文字列に変換して返す（モデルの要件）
        return json.dumps(cleaned[:3])

    def clean(self):
        cleaned_data = super().clean()
        return cleaned_data

    def save(self, commit=True):
        instance = super().save(commit=False)
        if commit:
            instance.save()
        return instance 