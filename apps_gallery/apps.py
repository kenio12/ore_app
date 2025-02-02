from django.apps import AppConfig


class AppsGalleryConfig(AppConfig):
    default_auto_field = 'django.db.models.BigAutoField'
    name = 'apps_gallery'
    verbose_name = 'アプリギャラリー'  # 日本語名も追加

    def ready(self):
        """アプリケーションの初期化時に実行される処理"""
        # テンプレートタグを登録
        from django.template.defaulttags import register
        import apps_gallery.templatetags.app_filters  # カスタムフィルターを読み込む
