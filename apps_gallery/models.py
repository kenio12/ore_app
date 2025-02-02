from django.db import models
from django.core.exceptions import ValidationError
from cloudinary.models import CloudinaryField
from .constants.app_info import APP_DEFAULTS, APP_TYPES, APP_STATUS, GENRES, MODEL_FIELDS  # 明示的にインポート
from .constants.architecture import *  # アーキテクチャ関連の定数

class AppGallery(models.Model):
    """アプリギャラリーモデル"""
    
    # ==================== 基本情報タブ ====================
    # アプリ名
    title = models.CharField('アプリ名', max_length=100)
    
    # アプリの種類（複数選択可能）
    app_types = models.JSONField(
        'アプリの種類',
        default=list,
        help_text=f'選択可能な値: {", ".join(APP_TYPES.keys())}'
    )
    
    # ジャンル（複数選択可能）
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
    status = models.CharField(
        '開発状況',
        max_length=20,
        choices=APP_STATUS.items(),
        default=APP_DEFAULTS['status']  # 定数から初期値を取得
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
    catchphrases = models.JSONField('キャッチコピー', default=list)
    
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
