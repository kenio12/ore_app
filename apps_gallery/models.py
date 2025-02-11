from django.db import models
from django.core.exceptions import ValidationError
from cloudinary.models import CloudinaryField
from django.conf import settings
from .constants import *  # 全ての定数をインポート

def validate_catchphrases(value):
    if not isinstance(value, list):
        raise ValidationError('キャッチフレーズはリスト形式で指定してください')
    if len(value) > 3:
        raise ValidationError('キャッチフレーズは最大3つまでです')
    for phrase in value:
        if not isinstance(phrase, str):
            raise ValidationError('キャッチフレーズは文字列で指定してください')
        if len(phrase) > 100:  # 100文字制限
            raise ValidationError('キャッチフレーズは100文字以内で指定してください')
        if value.count(phrase) > 1:  # 重複チェック
            raise ValidationError('同じキャッチフレーズが複数回指定されています')

class AppGallery(models.Model):
    """アプリギャラリーモデル"""
    
    # 作者（ユーザー）との関連付け
    author = models.ForeignKey(
        settings.AUTH_USER_MODEL,
        on_delete=models.CASCADE,
        verbose_name='作者',
        related_name='apps'
    )
    
    # サムネイル画像を追加
    thumbnail = models.JSONField(
        'サムネイル画像',
        null=True,
        blank=True,
        help_text='アプリのサムネイル画像情報'
    )
    
    # ==================== 基本情報タブ ====================
    title = models.CharField('アプリ名', max_length=100)
    
    app_types = models.JSONField(
        'アプリの種類',
        default=list,
        help_text=f'選択可能な値: {", ".join(APP_TYPES.keys())}'
    )
    
    genres = models.JSONField(
        'ジャンル',
        default=list,
        help_text=f'選択可能な値: {", ".join(GENRES.keys())}'
    )
    
    other_genre = models.CharField(
        'その他のジャンル',
        max_length=50,
        blank=True
    )
    
    # 開発状況
    dev_status = models.CharField(
        '開発状況',
        max_length=20,
        choices=APP_STATUS.items(),
        default='in_development'
    )
    
    # 公開状態
    status = models.CharField(
        '公開状態',
        max_length=20,
        choices=PUBLISH_STATUS.items(),
        default='draft'
    )
    
    # URL情報
    app_url = models.URLField(**MODEL_FIELDS['app_url'])
    github_url = models.URLField(**MODEL_FIELDS['github_url'])
    
    # ==================== アプリの魅力タブ ====================
    # アプリの概要
    overview = models.TextField('アプリの概要', blank=True)
    
    # 開発のきっかけ
    motivation = models.TextField('開発のきっかけ', blank=True)
    
    # キャッチコピー（最大3つ）
    catchphrases = models.JSONField(
        'キャッチコピー',
        default=list,
        validators=[validate_catchphrases],
        help_text='最大3つまで、各100文字以内'
    )
    
    # ターゲットユーザー
    target_users = models.TextField('ターゲットユーザー', blank=True)
    
    # アプリの問題点
    problems = models.TextField('アプリの問題点', blank=True)
    
    # 最後のアピール
    final_appeal = models.TextField('最後のアピール', blank=True)
    
    # ==================== スクリーンショットタブ ====================
    # スクリーンショット（複数枚）
    screenshots = models.JSONField('スクリーンショット', default=list)
    
    # ==================== 管理用フィールド ====================
    created_at = models.DateTimeField('作成日時', auto_now_add=True)
    updated_at = models.DateTimeField('更新日時', auto_now=True)
    
    class Meta:
        verbose_name = 'アプリギャラリー'
        verbose_name_plural = 'アプリギャラリー'
    
    def __str__(self):
        return self.title
