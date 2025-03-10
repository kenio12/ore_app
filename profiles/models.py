from django.db import models
from django.conf import settings
from django.db.models.signals import post_save
from django.dispatch import receiver
from django.utils import timezone
import datetime

class Profile(models.Model):
    user = models.OneToOneField(
        settings.AUTH_USER_MODEL,
        on_delete=models.CASCADE,
        primary_key=True  # ユーザーを主キーに設定
    )
    avatar = models.JSONField(null=True, blank=True)
    bio = models.TextField('自己紹介', max_length=500, blank=True)
    social_github = models.CharField('GitHub', max_length=100, blank=True)
    social_twitter = models.CharField('X（旧Twitter）', max_length=100, blank=True)
    # ハードウェア環境の情報
    hardware_specs = models.JSONField('ハードウェア環境', default=dict, null=True, blank=True)
    
    # 技術スキル情報（自動生成と手動入力の両方に対応）
    skills = models.JSONField(
        '技術スキル', 
        default=dict, 
        blank=True,
        help_text='プログラミング言語、フレームワークなどの技術スキル'
    )
    
    # 開発アクティビティ期間の情報
    first_app_date = models.DateField(
        '最初のアプリ開発日', 
        null=True, 
        blank=True,
        help_text='ユーザーが開発した最初のアプリの開始日'
    )
    
    last_app_date = models.DateField(
        '最新のアプリ開発日', 
        null=True, 
        blank=True,
        help_text='ユーザーが開発した最新のアプリの終了日'
    )
    
    active_months = models.JSONField(
        '活発な開発期間', 
        default=list, 
        blank=True,
        help_text='ユーザーが活発に開発していた年月のリスト'
    )
    
    # 各アプリの開発期間情報
    app_development_periods = models.JSONField(
        'アプリ別開発期間', 
        default=list, 
        blank=True,
        help_text='各アプリの開発期間情報のリスト'
    )
    
    # 専門分野（Webアプリやモバイルアプリなどのカテゴリー）
    specializations = models.JSONField(
        '専門分野', 
        default=list, 
        blank=True,
        help_text='開発の専門分野（Webアプリ、モバイルアプリなど）'
    )
    
    # アプリの種類の統計情報
    app_types_stats = models.JSONField(
        'アプリ種類統計', 
        default=dict, 
        blank=True,
        help_text='ユーザーが開発したアプリの種類と数'
    )
    
    # アプリのジャンルの統計情報
    genres_stats = models.JSONField(
        'ジャンル統計', 
        default=dict, 
        blank=True,
        help_text='ユーザーが開発したアプリのジャンルと数'
    )
    
    # 開発状況の統計情報
    dev_status_stats = None
    
    # 公開状態の統計情報
    publish_status_stats = None
    
    # ハードウェア環境の統計情報
    pc_type_stats = models.JSONField(
        'PCタイプ統計', 
        default=dict, 
        blank=True,
        help_text='ユーザーが開発に使用しているPCタイプ（デスクトップ・ノートなど）の統計'
    )
    
    device_type_stats = models.JSONField(
        'デバイス種類統計', 
        default=dict, 
        blank=True,
        help_text='ユーザーが開発に使用しているデバイス種類（Windows、Mac、Linuxなど）の統計'
    )
    
    cpu_type_stats = models.JSONField(
        'CPU統計', 
        default=dict, 
        blank=True,
        help_text='ユーザーが開発に使用しているCPUタイプの統計'
    )
    
    memory_size_stats = models.JSONField(
        'メモリサイズ統計', 
        default=dict, 
        blank=True,
        help_text='ユーザーが開発に使用しているメモリサイズの統計'
    )
    
    storage_type_stats = models.JSONField(
        'ストレージタイプ統計', 
        default=dict, 
        blank=True,
        help_text='ユーザーが開発に使用しているストレージタイプの統計'
    )
    
    monitor_count_stats = models.JSONField(
        'モニター数統計', 
        default=dict, 
        blank=True,
        help_text='ユーザーが開発に使用しているモニター数の統計'
    )
    
    internet_type_stats = models.JSONField(
        'インターネット接続統計', 
        default=dict, 
        blank=True,
        help_text='ユーザーが開発に使用しているインターネット接続タイプの統計'
    )
    
    # 開発環境の統計情報
    editor_stats = models.JSONField(
        'エディタ統計', 
        default=dict, 
        blank=True,
        help_text='ユーザーが開発に使用しているエディタ/IDEの統計'
    )
    
    version_control_stats = models.JSONField(
        'バージョン管理統計', 
        default=dict, 
        blank=True,
        help_text='ユーザーが開発に使用しているバージョン管理システムの統計'
    )
    
    virtualization_stats = models.JSONField(
        '仮想化ツール統計', 
        default=dict, 
        blank=True,
        help_text='ユーザーが開発に使用している仮想化ツールの統計'
    )
    
    team_size_stats = models.JSONField(
        'チームサイズ統計', 
        default=dict, 
        blank=True,
        help_text='ユーザーが開発に参加しているチームのサイズ統計'
    )
    
    communication_tools_stats = models.JSONField(
        'コミュニケーションツール統計', 
        default=dict, 
        blank=True,
        help_text='ユーザーが開発で使用しているコミュニケーションツールの統計'
    )
    
    ci_cd_stats = models.JSONField(
        'CI/CDツール統計', 
        default=dict, 
        blank=True,
        help_text='ユーザーが開発で使用しているCI/CDツールの統計'
    )
    
    # APIツール統計情報を追加
    api_tools_stats = models.JSONField(
        'APIツール統計',
        default=dict,
        blank=True,
        help_text='ユーザーが開発で使用しているAPIツール（Postman、Swagger、cURLなど）の統計'
    )
    
    # モニタリングツール統計情報を追加
    monitoring_tools_stats = models.JSONField(
        'モニタリングツール統計',
        default=dict,
        blank=True,
        help_text='ユーザーが開発で使用しているモニタリングツール（Prometheus、Grafana、New Relicなど）の統計'
    )
    
    # アーキテクチャ情報の統計
    architecture_pattern_stats = models.JSONField(
        'アーキテクチャパターン統計', 
        default=dict, 
        blank=True,
        help_text='ユーザーが採用しているアーキテクチャパターンの統計'
    )
    
    design_pattern_stats = models.JSONField(
        'デザインパターン統計', 
        default=dict, 
        blank=True,
        help_text='ユーザーが採用しているデザインパターンの統計'
    )
    
    # バックエンド関連の統計情報
    backend_language_stats = models.JSONField(
        'バックエンド言語統計', 
        default=dict, 
        blank=True,
        help_text='ユーザーが使用しているバックエンド言語の統計'
    )
    
    backend_framework_stats = models.JSONField(
        'バックエンドフレームワーク統計', 
        default=dict, 
        blank=True,
        help_text='ユーザーが使用しているバックエンドフレームワークの統計'
    )
    
    backend_package_stats = models.JSONField(
        'バックエンドパッケージ統計', 
        default=dict, 
        blank=True,
        help_text='ユーザーが使用しているバックエンドパッケージ/ライブラリの統計'
    )
    
    # フロントエンド関連の統計情報
    markup_language_stats = models.JSONField(
        'マークアップ言語統計', 
        default=dict, 
        blank=True,
        help_text='ユーザーが使用しているマークアップ/スタイリング言語の統計'
    )
    
    frontend_language_stats = models.JSONField(
        'フロントエンド言語統計', 
        default=dict, 
        blank=True,
        help_text='ユーザーが使用しているフロントエンド言語の統計'
    )
    
    frontend_framework_stats = models.JSONField(
        'フロントエンドフレームワーク統計', 
        default=dict, 
        blank=True,
        help_text='ユーザーが使用しているフロントエンドフレームワークの統計'
    )
    
    css_framework_stats = models.JSONField(
        'CSSフレームワーク統計', 
        default=dict, 
        blank=True,
        help_text='ユーザーが使用しているCSSフレームワーク/スタイリングツールの統計'
    )
    
    ui_library_stats = models.JSONField(
        'UIライブラリ統計', 
        default=dict, 
        blank=True,
        help_text='ユーザーが使用しているUIライブラリ/コンポーネントフレームワークの統計'
    )
    
    # データベース関連の統計情報
    database_type_stats = models.JSONField(
        'データベース種類統計', 
        default=dict, 
        blank=True,
        help_text='ユーザーが使用しているデータベース種類の統計'
    )
    
    orm_tool_stats = models.JSONField(
        'ORM統計', 
        default=dict, 
        blank=True,
        help_text='ユーザーが使用しているORMツールの統計'
    )
    
    # ホスティング関連の統計情報
    hosting_service_stats = models.JSONField(
        'ホスティングサービス統計', 
        default=dict, 
        blank=True,
        help_text='ユーザーが使用しているホスティングサービスの統計'
    )
    
    deployment_method_stats = models.JSONField(
        'デプロイ方法統計', 
        default=dict, 
        blank=True,
        help_text='ユーザーが使用しているデプロイ方法の統計'
    )
    
    database_hosting_stats = models.JSONField(
        'データベースホスティング統計', 
        default=dict, 
        blank=True,
        help_text='ユーザーが使用しているデータベースホスティングサービスの統計'
    )
    
    frontend_hosting_stats = models.JSONField(
        'フロントエンドホスティング統計', 
        default=dict, 
        blank=True,
        help_text='ユーザーが使用しているフロントエンドホスティングサービスの統計'
    )
    
    # セキュリティ関連の統計情報
    auth_method_stats = models.JSONField(
        '認証方式統計', 
        default=dict, 
        blank=True,
        help_text='ユーザーが使用している認証方式の統計'
    )
    
    security_measure_stats = models.JSONField(
        'セキュリティ対策統計', 
        default=dict, 
        blank=True,
        help_text='ユーザーが実施しているセキュリティ対策の統計'
    )
    
    # 開発期間関連の統計情報
    development_duration_stats = models.JSONField(
        '開発期間統計', 
        default=dict, 
        blank=True,
        help_text='ユーザーのアプリ開発期間の統計情報'
    )
    
    total_development_days = models.IntegerField(
        '合計開発日数', 
        default=0,
        help_text='ユーザーの全アプリ開発の合計日数'
    )
    
    avg_development_days = models.FloatField(
        '平均開発日数', 
        default=0.0,
        help_text='ユーザーのアプリ一つあたりの平均開発日数'
    )
    
    development_duration_distribution = models.JSONField(
        '開発期間分布', 
        default=dict, 
        blank=True,
        help_text='短期(30日以内)・中期(31-180日)・長期(181日以上)の開発プロジェクト分布'
    )
    
    # 仕事依頼関連の情報
    job_status = models.CharField(
        '仕事依頼ステータス', 
        max_length=20, 
        choices=[
            ('available', '依頼受付中'),
            ('limited', '限定的に受付中'),
            ('unavailable', '依頼停止中'),
        ], 
        default='unavailable',
        help_text='現在の仕事依頼の受付状況'
    )
    
    job_types = models.JSONField(
        '受付可能な仕事タイプ', 
        default=list, 
        blank=True,
        help_text='受付可能な仕事の種類（フロントエンド開発、バックエンド開発など）'
    )
    
    work_rate = models.CharField(
        '希望単価/時給', 
        max_length=100, 
        blank=True,
        help_text='希望する報酬額や時給の目安'
    )
    
    created_at = models.DateTimeField('作成日時', auto_now_add=True)
    updated_at = models.DateTimeField('更新日時', auto_now=True)

    def __str__(self):
        return f"{self.user.username}のプロフィール"

    @property
    def avatar_url(self):
        """アバター画像のURLを返す。Cloudinaryに対応。"""
        if self.avatar and 'url' in self.avatar:
            return self.avatar['url']
        return None

    def update_skills_from_apps(self):
        """ユーザーが作成したアプリから技術スキルと専門分野を自動更新する"""
        # ユーザーが作成したアプリを全て取得
        from apps_gallery.models import AppGallery
        user_apps = AppGallery.objects.filter(author=self.user)
        
        if not user_apps.exists():
            return
        
        # スキル集計用の辞書
        skills_count = {}
        # 専門分野集計用の辞書
        specializations_count = {}
        # アプリの種類集計用の辞書
        app_types_count = {}
        # ジャンル集計用の辞書
        genres_count = {}
        # 開発状況集計用の辞書
        dev_status_count = {}
        # 公開状態集計用の辞書
        publish_status_count = {}
        # ハードウェア統計集計用の辞書
        pc_type_count = {}
        device_type_count = {}
        cpu_type_count = {}
        memory_size_count = {}
        storage_type_count = {}
        monitor_count_count = {}
        internet_type_count = {}
        
        # 開発環境統計集計用の辞書
        editor_count = {}
        version_control_count = {}
        virtualization_count = {}
        team_size_count = {}
        communication_tools_count = {}
        ci_cd_count = {}
        api_tools_count = {}
        monitoring_tools_count = {}
        
        # アーキテクチャ情報統計用の辞書
        architecture_pattern_stats = {}
        design_pattern_stats = {}
        
        # バックエンド情報統計用の辞書
        backend_language_count = {}
        backend_framework_count = {}
        backend_package_count = {}
        
        # フロントエンド情報統計用の辞書
        markup_language_count = {}
        frontend_language_count = {}
        frontend_framework_count = {}
        css_framework_count = {}
        ui_library_count = {}
        
        # データベース情報統計用の辞書
        database_type_count = {}
        orm_tool_count = {}
        
        # ホスティング情報集計用の辞書
        hosting_service_stats = {}
        deployment_method_stats = {}
        frontend_hosting_stats = {}
        database_hosting_stats = {}
        
        # セキュリティ情報集計用の辞書
        auth_method_stats = {}
        security_measure_stats = {}
        
        # 開発期間情報集計用の変数
        development_duration_stats = {}
        total_days = 0
        app_count_with_duration = 0
        
        # 開発期間分布集計用
        duration_distribution = {
            'short_term': 0,  # 30日以内
            'mid_term': 0,    # 31-180日
            'long_term': 0    # 181日以上
        }
        
        # データベースORM集計
        orm_count = {}
        
        # 開発日付情報を取得する変数
        first_app_date = None
        last_app_date = None
        development_months = set()  # 活発な開発月を記録するセット
        app_dev_periods = []  # 各アプリの開発期間情報を格納するリスト
        
        for app in user_apps:
            # ホスティング情報を収集（あれば）
            if hasattr(app, 'hosting') and app.hosting:
                # ホスティングサービス集計
                if 'services' in app.hosting and app.hosting['services']:
                    for service in app.hosting['services']:
                        hosting_service_stats[service] = hosting_service_stats.get(service, 0) + 1
                
                # デプロイ方法集計
                if 'deployment_methods' in app.hosting and app.hosting['deployment_methods']:
                    for method in app.hosting['deployment_methods']:
                        deployment_method_stats[method] = deployment_method_stats.get(method, 0) + 1
                
                # フロントエンドホスティング集計
                if 'frontend_hosting' in app.hosting and app.hosting['frontend_hosting']:
                    for hosting in app.hosting['frontend_hosting']:
                        frontend_hosting_stats[hosting] = frontend_hosting_stats.get(hosting, 0) + 1
            
            # データベースホスティング集計
            if hasattr(app, 'database') and app.database:
                if 'hosting' in app.database and app.database['hosting']:
                    for db_hosting in app.database['hosting']:
                        database_hosting_stats[db_hosting] = database_hosting_stats.get(db_hosting, 0) + 1
            
            # セキュリティ情報を収集（あれば）
            if hasattr(app, 'security') and app.security:
                # 認証方式集計
                if 'auth_methods' in app.security and app.security['auth_methods']:
                    for auth_method in app.security['auth_methods']:
                        auth_method_stats[auth_method] = auth_method_stats.get(auth_method, 0) + 1
                
                # セキュリティ対策集計
                if 'measures' in app.security and app.security['measures']:
                    for measure in app.security['measures']:
                        security_measure_stats[measure] = security_measure_stats.get(measure, 0) + 1
            
            # ======== バックエンド情報から技術スキルを抽出 ========
            if app.backend and isinstance(app.backend, dict):
                # メイン言語
                if 'main_language' in app.backend and app.backend['main_language']:
                    lang = app.backend['main_language']
                    backend_language_count[lang] = backend_language_count.get(lang, 0) + 1
                
                # フレームワーク
                if 'framework' in app.backend and app.backend['framework']:
                    framework = app.backend['framework']
                    backend_framework_count[framework] = backend_framework_count.get(framework, 0) + 1
                
                # パッケージ/ライブラリ
                if 'packages' in app.backend and app.backend['packages']:
                    packages = app.backend['packages']
                    if isinstance(packages, list):
                        for package in packages:
                            backend_package_count[package] = backend_package_count.get(package, 0) + 1
                    elif isinstance(packages, str):
                        packages = [p.strip() for p in packages.split(',')]
                        for package in packages:
                            if package:
                                backend_package_count[package] = backend_package_count.get(package, 0) + 1
            
            # ======== フロントエンド情報から技術スキルを抽出 ========
            if app.frontend and isinstance(app.frontend, dict):
                # 言語
                if 'languages' in app.frontend and app.frontend['languages']:
                    languages = app.frontend['languages']
                    if isinstance(languages, str):
                        languages = languages.split(',')
                    elif isinstance(languages, list):
                        languages = languages
                    else:
                        languages = []
                    
                    for lang in [l.strip() for l in languages if hasattr(l, 'strip')]:
                        skills_count[lang] = skills_count.get(lang, 0) + 1
                
                # フレームワーク
                if 'frameworks' in app.frontend and app.frontend['frameworks']:
                    frameworks = app.frontend['frameworks']
                    if isinstance(frameworks, str):
                        frameworks = frameworks.split(',')
                    elif isinstance(frameworks, list):
                        frameworks = frameworks
                    else:
                        frameworks = []
                    
                    for framework in [f.strip() for f in frameworks if hasattr(f, 'strip')]:
                        skills_count[framework] = skills_count.get(framework, 0) + 1
                
                # CSSフレームワーク
                if 'css_framework' in app.frontend and app.frontend['css_framework']:
                    css = app.frontend['css_framework']
                    skills_count[css] = skills_count.get(css, 0) + 1
            
            # ======== データベース情報から技術スキルを抽出 ========
            if app.database and isinstance(app.database, dict):
                # データベース種類
                if 'type' in app.database and app.database['type']:
                    db_type = app.database['type']
                    skills_count[db_type] = skills_count.get(db_type, 0) + 1
                
                # ORM
                if 'orm' in app.database and app.database['orm']:
                    orm = app.database['orm']
                    skills_count[orm] = skills_count.get(orm, 0) + 1
            
            # ======== アプリの種類を収集 ========
            if app.app_types and isinstance(app.app_types, list):
                for app_type in app.app_types:
                    app_types_count[app_type] = app_types_count.get(app_type, 0) + 1
                    # アプリの種類から専門分野も推測
                    if app_type in ['web_app', 'website']:
                        specializations_count['Webアプリ'] = specializations_count.get('Webアプリ', 0) + 1
                    elif app_type in ['mobile_app', 'pwa']:
                        specializations_count['モバイルアプリ'] = specializations_count.get('モバイルアプリ', 0) + 1
                    elif app_type in ['desktop_app']:
                        specializations_count['デスクトップアプリ'] = specializations_count.get('デスクトップアプリ', 0) + 1
                    elif app_type in ['game']:
                        specializations_count['ゲーム開発'] = specializations_count.get('ゲーム開発', 0) + 1
                    elif app_type in ['ai_app', 'data_visualization']:
                        specializations_count['AI/データ分析'] = specializations_count.get('AI/データ分析', 0) + 1
            
            # ======== ジャンルを収集 ========
            if app.genres and isinstance(app.genres, list):
                for genre in app.genres:
                    genres_count[genre] = genres_count.get(genre, 0) + 1
            
            # ======== 開発状況を収集 ========
            if app.dev_status:
                dev_status_count[app.dev_status] = dev_status_count.get(app.dev_status, 0) + 1
            
            # ======== 公開状態を収集 ========
            if app.status:
                publish_status_count[app.status] = publish_status_count.get(app.status, 0) + 1
                
            # ======== ハードウェア情報を収集 ========
            if app.hardware_specs and isinstance(app.hardware_specs, dict):
                # PCタイプ
                if 'pc_type' in app.hardware_specs and app.hardware_specs['pc_type']:
                    pc_type = app.hardware_specs['pc_type']
                    pc_type_count[pc_type] = pc_type_count.get(pc_type, 0) + 1
                
                # デバイスタイプ
                if 'device_type' in app.hardware_specs and app.hardware_specs['device_type']:
                    device_type = app.hardware_specs['device_type']
                    device_type_count[device_type] = device_type_count.get(device_type, 0) + 1
                
                # CPU
                if 'cpu_type' in app.hardware_specs and app.hardware_specs['cpu_type']:
                    cpu_type = app.hardware_specs['cpu_type']
                    cpu_type_count[cpu_type] = cpu_type_count.get(cpu_type, 0) + 1
                
                # メモリ
                if 'memory_size' in app.hardware_specs and app.hardware_specs['memory_size']:
                    memory_size = app.hardware_specs['memory_size']
                    memory_size_count[memory_size] = memory_size_count.get(memory_size, 0) + 1
                
                # ストレージ
                if 'storage_type' in app.hardware_specs and app.hardware_specs['storage_type']:
                    storage_type = app.hardware_specs['storage_type']
                    storage_type_count[storage_type] = storage_type_count.get(storage_type, 0) + 1
                
                # モニター数
                if 'monitor_count' in app.hardware_specs and app.hardware_specs['monitor_count']:
                    monitor_count = app.hardware_specs['monitor_count']
                    monitor_count_count[monitor_count] = monitor_count_count.get(monitor_count, 0) + 1
                
                # インターネット接続
                if 'internet_type' in app.hardware_specs and app.hardware_specs['internet_type']:
                    internet_type = app.hardware_specs['internet_type']
                    internet_type_count[internet_type] = internet_type_count.get(internet_type, 0) + 1
            
            # ======== 開発環境情報を収集 ========
            if app.development_environment and isinstance(app.development_environment, dict):
                # エディタ/IDE
                if 'editors' in app.development_environment and app.development_environment['editors']:
                    editors = app.development_environment['editors']
                    if isinstance(editors, list):
                        for editor in editors:
                            editor_count[editor] = editor_count.get(editor, 0) + 1
                
                # バージョン管理
                if 'version_control' in app.development_environment and app.development_environment['version_control']:
                    version_controls = app.development_environment['version_control']
                    if isinstance(version_controls, list):
                        for vc in version_controls:
                            version_control_count[vc] = version_control_count.get(vc, 0) + 1
                
                # 仮想化ツール
                if 'virtualization' in app.development_environment and app.development_environment['virtualization']:
                    virtualizations = app.development_environment['virtualization']
                    if isinstance(virtualizations, list):
                        for virt in virtualizations:
                            virtualization_count[virt] = virtualization_count.get(virt, 0) + 1
                
                # チームサイズ
                if 'team_size' in app.development_environment and app.development_environment['team_size']:
                    team_size = app.development_environment['team_size']
                    team_size_count[team_size] = team_size_count.get(team_size, 0) + 1
                
                # コミュニケーションツール
                if 'communication_tools' in app.development_environment and app.development_environment['communication_tools']:
                    comm_tools = app.development_environment['communication_tools']
                    if isinstance(comm_tools, list):
                        for tool in comm_tools:
                            communication_tools_count[tool] = communication_tools_count.get(tool, 0) + 1
                
                # CI/CD
                if 'ci_cd' in app.development_environment and app.development_environment['ci_cd']:
                    ci_cds = app.development_environment['ci_cd']
                    if isinstance(ci_cds, list):
                        for ci in ci_cds:
                            ci_cd_count[ci] = ci_cd_count.get(ci, 0) + 1
                
                # APIツール
                if 'api_tools' in app.development_environment and app.development_environment['api_tools']:
                    api_tools_list = app.development_environment['api_tools']
                    if isinstance(api_tools_list, list):
                        for tool in api_tools_list:
                            api_tools_count[tool] = api_tools_count.get(tool, 0) + 1
                
                # モニタリングツール
                if 'monitoring_tools' in app.development_environment and app.development_environment['monitoring_tools']:
                    monitoring_tools_list = app.development_environment['monitoring_tools']
                    if isinstance(monitoring_tools_list, list):
                        for tool in monitoring_tools_list:
                            monitoring_tools_count[tool] = monitoring_tools_count.get(tool, 0) + 1
            
            # ======== アーキテクチャ情報を収集 ========
            if app.architecture and isinstance(app.architecture, dict):
                # アーキテクチャパターン
                if 'patterns' in app.architecture and app.architecture['patterns']:
                    patterns = app.architecture['patterns']
                    if isinstance(patterns, list):
                        for pattern in patterns:
                            architecture_pattern_stats[pattern] = architecture_pattern_stats.get(pattern, 0) + 1
                
                # デザインパターン
                if 'design_patterns' in app.architecture and app.architecture['design_patterns']:
                    design_patterns = app.architecture['design_patterns']
                    if isinstance(design_patterns, list):
                        for pattern in design_patterns:
                            design_pattern_stats[pattern] = design_pattern_stats.get(pattern, 0) + 1
            
            # ======== フロントエンド情報を収集 ========
            if app.frontend and isinstance(app.frontend, dict):
                # マークアップ・スタイリング言語
                if 'markup_languages' in app.frontend and app.frontend['markup_languages']:
                    languages = app.frontend['markup_languages']
                    if isinstance(languages, list):
                        for lang in languages:
                            markup_language_count[lang] = markup_language_count.get(lang, 0) + 1
                
                # フロントエンド言語
                if 'languages' in app.frontend and app.frontend['languages']:
                    languages = app.frontend['languages']
                    if isinstance(languages, list):
                        for lang in languages:
                            frontend_language_count[lang] = frontend_language_count.get(lang, 0) + 1
                
                # フレームワーク
                if 'frameworks' in app.frontend and app.frontend['frameworks']:
                    frameworks = app.frontend['frameworks']
                    if isinstance(frameworks, list):
                        for framework in frameworks:
                            frontend_framework_count[framework] = frontend_framework_count.get(framework, 0) + 1
                
                # CSSフレームワーク
                if 'css_frameworks' in app.frontend and app.frontend['css_frameworks']:
                    css_frameworks = app.frontend['css_frameworks']
                    if isinstance(css_frameworks, list):
                        for css in css_frameworks:
                            css_framework_count[css] = css_framework_count.get(css, 0) + 1
                
                # UIライブラリ
                if 'ui_libraries' in app.frontend and app.frontend['ui_libraries']:
                    ui_libraries = app.frontend['ui_libraries']
                    if isinstance(ui_libraries, list):
                        for lib in ui_libraries:
                            ui_library_count[lib] = ui_library_count.get(lib, 0) + 1
            
            # ======== データベース情報を収集 ========
            if app.database and isinstance(app.database, dict):
                # データベース種類
                if 'types' in app.database and app.database['types']:
                    db_types = app.database['types']
                    if isinstance(db_types, list):
                        for db_type in db_types:
                            database_type_count[db_type] = database_type_count.get(db_type, 0) + 1
                
                # ORM
                if 'orm_tools' in app.database and app.database['orm_tools']:
                    orm_tools = app.database['orm_tools']
                    if isinstance(orm_tools, list):
                        for orm in orm_tools:
                            orm_tool_count[orm] = orm_tool_count.get(orm, 0) + 1
            
            # ======== 開発期間情報を収集 ========
            if hasattr(app, 'development_story') and app.development_story:
                # 開始日と終了日がある場合に期間を計算
                if 'start_date' in app.development_story and 'end_date' in app.development_story and app.development_story['start_date'] and app.development_story['end_date']:
                    try:
                        start_date = datetime.datetime.strptime(app.development_story['start_date'], '%Y-%m-%d').date()
                        end_date = datetime.datetime.strptime(app.development_story['end_date'], '%Y-%m-%d').date()
                        
                        # アプリごとの開発期間情報を記録
                        app_period = {
                            'app_id': app.id,
                            'title': app.title,
                            'start_date': app.development_story['start_date'],
                            'end_date': app.development_story['end_date'],
                            'duration': app.development_story.get('duration', ''),
                            'development_motivation': app.development_story.get('development_motivation', ''),
                            'development_innovations': app.development_story.get('development_innovations', ''),
                            'development_abandoned': app.development_story.get('development_abandoned', ''),
                            'development_future_plans': app.development_story.get('development_future_plans', ''),
                            'development_reflections': app.development_story.get('development_reflections', '')
                        }
                        app_dev_periods.append(app_period)
                        
                        # 最初のアプリ開発日を更新
                        if first_app_date is None or start_date < first_app_date:
                            first_app_date = start_date
                        
                        # 最新のアプリ開発日を更新
                        if last_app_date is None or end_date > last_app_date:
                            last_app_date = end_date
                        
                        # 開発期間中の各月を記録
                        current_date = start_date
                        while current_date <= end_date:
                            # YYYY年MM月 形式で記録
                            month_str = current_date.strftime('%Y年%m月')
                            development_months.add(month_str)
                            # 次の月に進む
                            next_month = current_date.month + 1
                            next_year = current_date.year
                            if next_month > 12:
                                next_month = 1
                                next_year += 1
                            current_date = current_date.replace(year=next_year, month=next_month, day=1)
                        
                        # 日数差を計算
                        duration_days = (end_date - start_date).days + 1  # 両端を含める
                        
                        if duration_days > 0:
                            # 開発状況ごとの日数を集計
                            if hasattr(app, 'dev_status') and app.dev_status:
                                dev_status = app.dev_status
                                development_duration_stats[dev_status] = development_duration_stats.get(dev_status, 0) + duration_days
                            
                            # 合計日数に加算
                            total_days += duration_days
                            app_count_with_duration += 1
                            
                            # 開発期間分布集計
                            if duration_days <= 30:
                                duration_distribution['short_term'] += 1
                            elif 31 <= duration_days <= 180:
                                duration_distribution['mid_term'] += 1
                            else:  # 181日以上
                                duration_distribution['long_term'] += 1
                    except (ValueError, TypeError):
                        # 日付形式が不正な場合はスキップ
                        pass
        
        # 技術スキルを更新
        if skills_count:
            # 現在のスキル情報をベースにする
            current_skills = self.skills if self.skills else {}
            # 新しいスキル情報で更新
            for skill, count in skills_count.items():
                # スキル名が空でなければ追加または更新
                if skill.strip():
                    # スキルが既に存在する場合は、自己評価は維持し、アプリからの推定フラグと頻度を更新
                    if skill in current_skills:
                        current_skills[skill]['frequency'] = count
                        current_skills[skill]['from_apps'] = True
                    else:
                        # 新しいスキルは初期値で追加
                        current_skills[skill] = {
                            'level': 1,  # 初期レベルは1
                            'frequency': count,
                            'from_apps': True  # アプリから自動生成されたことを示す
                        }
            
            # 更新したスキル情報を保存
            self.skills = current_skills
        
        # 専門分野を更新
        if specializations_count:
            # 頻度順にソート
            top_specializations = sorted(
                specializations_count.items(),
                key=lambda item: item[1],
                reverse=True
            )
            # 上位3つを専門分野として設定
            self.specializations = [item[0] for item in top_specializations[:3]]
        
        # アプリの種類統計を更新
        if app_types_count:
            self.app_types_stats = app_types_count
        
        # ジャンル統計を更新
        if genres_count:
            self.genres_stats = genres_count
        
        # 開発環境統計を更新
        if editor_count:
            self.editor_stats = editor_count
            
        if version_control_count:
            self.version_control_stats = version_control_count
            
        if virtualization_count:
            self.virtualization_stats = virtualization_count
            
        if team_size_count:
            self.team_size_stats = team_size_count
            
        if communication_tools_count:
            self.communication_tools_stats = communication_tools_count
            
        if ci_cd_count:
            self.ci_cd_stats = ci_cd_count
            
        if api_tools_count:
            self.api_tools_stats = api_tools_count
            
        if monitoring_tools_count:
            self.monitoring_tools_stats = monitoring_tools_count
        
        # アーキテクチャ情報の統計
        if architecture_pattern_stats:
            self.architecture_pattern_stats = architecture_pattern_stats
        
        if design_pattern_stats:
            self.design_pattern_stats = design_pattern_stats
        
        # バックエンド情報の統計
        if backend_language_count:
            self.backend_language_stats = backend_language_count
            
        if backend_framework_count:
            self.backend_framework_stats = backend_framework_count
            
        if backend_package_count:
            self.backend_package_stats = backend_package_count
            
        # フロントエンド情報の統計
        if markup_language_count:
            self.markup_language_stats = markup_language_count
            
        if frontend_language_count:
            self.frontend_language_stats = frontend_language_count
            
        if frontend_framework_count:
            self.frontend_framework_stats = frontend_framework_count
            
        if css_framework_count:
            self.css_framework_stats = css_framework_count
            
        if ui_library_count:
            self.ui_library_stats = ui_library_count
            
        # データベース情報の統計
        if database_type_count:
            self.database_type_stats = database_type_count
            
        if orm_tool_count:
            self.orm_tool_stats = orm_tool_count
        
        # ホスティング関連の統計情報
        if hosting_service_stats:
            self.hosting_service_stats = hosting_service_stats
        
        if deployment_method_stats:
            self.deployment_method_stats = deployment_method_stats
        
        if frontend_hosting_stats:
            self.frontend_hosting_stats = frontend_hosting_stats
        
        if database_hosting_stats:
            self.database_hosting_stats = database_hosting_stats
        
        # セキュリティ関連の統計情報
        if auth_method_stats:
            self.auth_method_stats = auth_method_stats
        
        if security_measure_stats:
            self.security_measure_stats = security_measure_stats
        
        # 開発期間関連の統計情報
        if development_duration_stats:
            self.development_duration_stats = development_duration_stats
        
        # 合計開発日数
        self.total_development_days = total_days
        
        # 平均開発日数
        if app_count_with_duration > 0:
            self.avg_development_days = round(total_days / app_count_with_duration, 1)
        else:
            self.avg_development_days = 0
        
        # 開発期間分布
        self.development_duration_distribution = duration_distribution
        
        # 開発日付情報を更新
        if first_app_date:
            self.first_app_date = first_app_date
            
        if last_app_date:
            self.last_app_date = last_app_date
            
        if development_months:
            # 月を時系列順（古い順）にソート
            self.active_months = sorted(list(development_months))
        
        # 各アプリ開発期間情報を更新
        if app_dev_periods:
            # 開発開始日の新しい順にソート
            self.app_development_periods = sorted(
                app_dev_periods,
                key=lambda x: datetime.datetime.strptime(x['start_date'], '%Y-%m-%d').date(),
                reverse=True
            )
        
        self.save()

# ユーザーが作成されたときに自動的にプロフィールも作成する
@receiver(post_save, sender=settings.AUTH_USER_MODEL)
def create_or_update_user_profile(sender, instance, created, **kwargs):
    if created:
        Profile.objects.create(user=instance)
    else:
        # プロフィールが既に存在している場合は更新
        if hasattr(instance, 'profile'):
            instance.profile.save()

# アプリが保存されたときにプロフィールのスキル情報を自動更新
@receiver(post_save, sender='apps_gallery.AppGallery')
def update_profile_skills(sender, instance, **kwargs):
    """アプリが保存されたときにプロフィールのスキルを更新"""
    try:
        # アプリの作者のプロフィールを取得
        profile = instance.author.profile
        # スキル情報を更新
        profile.update_skills_from_apps()
    except Exception as e:
        # エラーログを出力
        import logging
        logger = logging.getLogger(__name__)
        logger.error(f"プロフィールスキル更新エラー: {e}")
