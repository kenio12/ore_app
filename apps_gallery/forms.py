from django import forms
import json
from .models import AppGallery
from .constants.app_info import APP_TYPES, GENRES, APP_STATUS, PUBLISH_STATUS  # 正しいパスからインポート

class AppForm(forms.ModelForm):
    # フィールド名をPOSTデータに合わせる
    types = forms.MultipleChoiceField(
        choices=[(k, v) for k, v in APP_TYPES.items()],  # タプルのリストに変換
        required=False
    )
    genres = forms.MultipleChoiceField(
        choices=[(k, v) for k, v in GENRES.items()],  # タプルのリストに変換
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
            'title',           # アプリ名
            'types',          # app_types から types に変更
            'genres',          # ジャンル
            'dev_status',      # 開発状況
            'status',          # 公開状態
            'app_url',         # アプリのURL
            'github_url',      # GitHubリポジトリURL
            'overview',        # アプリの説明
            'motivation',      # 開発のきっかけ
            'catchphrases',    # キャッチコピー
            'target_users',    # ターゲットユーザー
            'problems',        # 問題点
            'final_appeal',    # 最後のアピール
        ]

    def __init__(self, *args, **kwargs):
        super().__init__(*args, **kwargs)
        
        # フィールドのカスタマイズ
        self.fields['title'].widget.attrs.update({
            'class': 'form-control cyber-yellow-focus',
            'placeholder': 'アプリの名前を入力してください'
        })

        # 必須フィールドの設定
        self.fields['title'].required = True
        # self.fields['overview'].required = True  # この行をコメントアウトまたは削除

        # その他のフィールドは任意に
        optional_fields = ['app_url', 'github_url', 'motivation', 'catchphrases', 
                         'target_users', 'problems', 'final_appeal']
        for field in optional_fields:
            self.fields[field].required = False

        self.fields['app_url'].widget.attrs.update({
            'class': 'form-control cyber-yellow-focus',
            'placeholder': 'https://example.com'
        })

        self.fields['github_url'].widget.attrs.update({
            'class': 'form-control cyber-yellow-focus',
            'placeholder': 'https://github.com/username/repository'
        })

        # テキストエリアのカスタマイズ
        text_areas = ['overview', 'motivation', 'target_users', 'problems', 'final_appeal']
        for field in text_areas:
            self.fields[field].widget.attrs.update({
                'class': 'form-control cyber-green-focus',
                'rows': '8'
            })

    def clean_types(self):  # app_types から types に変更
        types = self.cleaned_data.get('types', [])
        if isinstance(types, str):
            return [types]
        return types

    def clean_genres(self):
        genres = self.cleaned_data.get('genres', [])
        if isinstance(genres, str):
            return [genres]
        return genres

    def clean_catchphrases(self):
        catchphrases = self.cleaned_data.get('catchphrases', '')
        if not catchphrases:
            return []
        
        # 文字列の場合（フォームからの入力）
        if isinstance(catchphrases, str):
            # カンマで分割して重複を除去
            phrases = list(dict.fromkeys([
                p.strip() 
                for p in catchphrases.split(',') 
                if p.strip()
            ]))
            return phrases[:3]  # 最大3つまで
        
        # リストの場合（既存データ）
        if isinstance(catchphrases, list):
            # 重複を除去
            phrases = list(dict.fromkeys(catchphrases))
            return phrases[:3]  # 最大3つまで
        
        return []  # その他の場合は空リスト

    def clean(self):
        cleaned_data = super().clean()
        print("クリーニング後のデータ:", cleaned_data)  # デバッグ用
        return cleaned_data

    def save(self, commit=True):
        instance = super().save(commit=False)
        # types を app_types にマッピング
        instance.app_types = self.cleaned_data.get('types', [])
        if commit:
            instance.save()
        return instance 