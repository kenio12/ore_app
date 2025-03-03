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
    title = models.CharField(
        'アプリ名', 
        max_length=100,
        null=True,
        blank=True
    )
    
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
    
    # キャッチコピーを3つのフィールドに分割
    catchphrase_1 = models.CharField(
        verbose_name='キャッチコピー1',
        max_length=100,
        blank=True
    )
    catchphrase_2 = models.CharField(
        verbose_name='キャッチコピー2',
        max_length=100,
        blank=True
    )
    catchphrase_3 = models.CharField(
        verbose_name='キャッチコピー3',
        max_length=100,
        blank=True
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
    
    # ==================== 技術情報タブ ====================
    # ハードウェア情報
    hardware_specs = models.JSONField(
        '開発環境のスペック',
        default=dict,
        blank=True,
        help_text='開発時に使用したパソコンのスペック情報（タイプ、メーカー、OS、CPU、メモリ、ストレージ、モニター構成、ネット環境）'
    )
    
    # 開発環境情報
    development_environment = models.JSONField(
        '開発環境',
        default=dict,
        blank=True,
        help_text='開発環境の情報（エディタ、バージョン管理、CI/CD、仮想化ツール、'
                 'チームサイズ、コミュニケーションツール、インフラ、APIツール、'
                 'モニタリングツール）'
    )
    
    # バックエンド情報
    backend = models.JSONField(
        'バックエンド情報',
        default=dict,
        blank=True,
        help_text='バックエンド関連の情報（メイン言語、フレームワーク、パッケージなど）'
    )

    # アーキテクチャ情報を追加
    architecture = models.JSONField(
        'アーキテクチャ情報',
        default=dict,
        blank=True,
        help_text='アーキテクチャ関連の情報（アーキテクチャパターン、デザインパターン、説明など）'
    )

    # フロントエンド情報
    frontend = models.JSONField(
        'フロントエンド情報',
        default=dict,
        blank=True,
        help_text='フロントエンド関連の情報（言語、フレームワーク、CSSフレームワークなど）'
    )

    # データベース情報
    database = models.JSONField(
        'データベース情報',
        default=dict,
        blank=True,
        help_text='データベース関連の情報（種類、ホスティング、ORMなど）'
    )

    # セキュリティ情報
    security = models.JSONField(
        'セキュリティ情報',
        default=dict,
        blank=True,
        help_text='セキュリティ関連の情報（認証方式、セキュリティ対策など）'
    )
    
    # 開発ストーリー情報を追加
    development_story = models.JSONField(
        '開発ストーリー情報',
        default=dict,
        blank=True,
        help_text='開発ストーリー関連の情報（開発期間、工夫したポイント、苦労した点など）'
    )
    
    # ==================== 管理用フィールド ====================
    created_at = models.DateTimeField('作成日時', auto_now_add=True)
    updated_at = models.DateTimeField('更新日時', auto_now=True)
    
    class Meta:
        verbose_name = 'アプリギャラリー'
        verbose_name_plural = 'アプリギャラリー'
    
    def __str__(self):
        return self.title

# ==================== アナリティクスモデル ====================
class AppAnalytics(models.Model):
    """アプリ使用統計データモデル"""
    
    app = models.OneToOneField(
        AppGallery,
        on_delete=models.CASCADE,
        related_name='analytics',
        verbose_name='アプリ'
    )
    
    # 基本統計情報
    view_count = models.IntegerField('閲覧数', default=0)
    like_count = models.IntegerField('いいね数', default=0)
    comment_count = models.IntegerField('コメント数', default=0)
    share_count = models.IntegerField('共有数', default=0)
    
    # 詳細アナリティクスデータ
    daily_views = models.JSONField(
        '日別閲覧数',
        default=dict,
        help_text='各日付の閲覧数を記録'
    )
    
    regional_views = models.JSONField(
        '地域別閲覧数',
        default=dict,
        help_text='各地域からの閲覧数を記録'
    )
    
    referrers = models.JSONField(
        'リファラー情報',
        default=dict,
        help_text='アクセス元サイトと回数を記録'
    )
    
    device_types = models.JSONField(
        'デバイスタイプ',
        default=dict,
        help_text='閲覧に使用されたデバイスタイプ（PC, モバイル, タブレット）'
    )
    
    # 管理フィールド
    last_updated = models.DateTimeField('最終更新日時', auto_now=True)
    created_at = models.DateTimeField('作成日時', auto_now_add=True)
    
    class Meta:
        verbose_name = 'アプリアナリティクス'
        verbose_name_plural = 'アプリアナリティクス'
    
    def __str__(self):
        return f"{self.app.title}のアナリティクス"
    
    def increment_view(self, request=None):
        """閲覧数をインクリメントし、関連するアナリティクスデータも更新する"""
        from datetime import datetime
        
        # 作者の閲覧はカウントしない
        if request and request.user.is_authenticated:
            if request.user == self.app.author:
                # 作者自身の閲覧の場合はカウントしない
                return
        
        # 閲覧数インクリメント
        self.view_count += 1
        
        # 日付ごとのビュー数を更新
        today = datetime.now().strftime('%Y-%m-%d')
        daily = self.daily_views.copy()
        daily[today] = daily.get(today, 0) + 1
        self.daily_views = daily
        
        if request:
            # リファラー情報の更新
            referrer = request.META.get('HTTP_REFERER', 'direct')
            referrers = self.referrers.copy()
            referrers[referrer] = referrers.get(referrer, 0) + 1
            self.referrers = referrers
            
            # デバイスタイプの更新
            user_agent = request.META.get('HTTP_USER_AGENT', '')
            device = 'unknown'
            if 'Mobile' in user_agent:
                device = 'mobile'
            elif 'Tablet' in user_agent:
                device = 'tablet'
            else:
                device = 'desktop'
            
            devices = self.device_types.copy()
            devices[device] = devices.get(device, 0) + 1
            self.device_types = devices
            
            # 地域情報の更新（IPアドレスから取得できる場合）
            ip = self.get_client_ip(request)
            if ip:
                # 簡易的な実装（実際にはGeoIPなどのライブラリを使用するとよい）
                region = 'unknown'
                regions = self.regional_views.copy()
                regions[region] = regions.get(region, 0) + 1
                self.regional_views = regions
        
        # 変更を保存
        self.save()
    
    def get_client_ip(self, request):
        """クライアントのIPアドレスを取得する"""
        x_forwarded_for = request.META.get('HTTP_X_FORWARDED_FOR')
        if x_forwarded_for:
            ip = x_forwarded_for.split(',')[0]
        else:
            ip = request.META.get('REMOTE_ADDR')
        return ip

# 既存のapp_detailビューを修正する代わりに、シグナルを使用してアクセス時にアナリティクスを更新
from django.db.models.signals import post_save
from django.dispatch import receiver

@receiver(post_save, sender=AppGallery)
def create_analytics(sender, instance, created, **kwargs):
    """AppGalleryが作成されたときに、対応するAnalyticsも作成する"""
    if created:
        AppAnalytics.objects.create(app=instance)
