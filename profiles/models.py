from django.db import models
from django.conf import settings
from django.db.models.signals import post_save
from django.dispatch import receiver
from django.utils import timezone

class Profile(models.Model):
    user = models.OneToOneField(settings.AUTH_USER_MODEL, on_delete=models.CASCADE, related_name='profile')
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
    
    # 専門分野（Webアプリやモバイルアプリなどのカテゴリー）
    specializations = models.JSONField(
        '専門分野', 
        default=list, 
        blank=True,
        help_text='開発の専門分野（Webアプリ、モバイルアプリなど）'
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
        """ユーザーが投稿したアプリから技術スキルを自動集計"""
        # ユーザーのアプリを取得
        user_apps = self.user.apps.all()
        
        if not user_apps:
            return
        
        # 技術情報を集計するための辞書
        skills_data = {
            'backend_languages': {},
            'backend_frameworks': {},
            'frontend_languages': {},
            'frontend_frameworks': {},
            'databases': {},
            'devops_tools': {},  # DevOps技術用の辞書を追加
            'cloud_services': {},  # クラウド技術用の辞書を追加
            'other_techs': {},
        }
        
        # 専門分野を集計するためのリスト
        specializations_count = {}
        
        # アプリごとに技術情報を集計
        for app in user_apps:
            # バックエンド情報
            if hasattr(app, 'backend') and app.backend:
                # 言語
                if 'language' in app.backend and app.backend['language']:
                    lang = app.backend['language']
                    skills_data['backend_languages'][lang] = skills_data['backend_languages'].get(lang, 0) + 1
                
                # フレームワーク
                if 'framework' in app.backend and app.backend['framework']:
                    fw = app.backend['framework']
                    skills_data['backend_frameworks'][fw] = skills_data['backend_frameworks'].get(fw, 0) + 1
            
            # フロントエンド情報
            if hasattr(app, 'frontend') and app.frontend:
                # 言語
                if 'language' in app.frontend and app.frontend['language']:
                    lang = app.frontend['language']
                    skills_data['frontend_languages'][lang] = skills_data['frontend_languages'].get(lang, 0) + 1
                    
                    # TypeScriptの特別処理（フロントエンド言語として登録）
                    if lang.lower() == 'typescript':
                        skills_data['frontend_languages']['TypeScript'] = skills_data['frontend_languages'].get('TypeScript', 0) + 1
                
                # フレームワーク
                if 'framework' in app.frontend and app.frontend['framework']:
                    fw = app.frontend['framework']
                    skills_data['frontend_frameworks'][fw] = skills_data['frontend_frameworks'].get(fw, 0) + 1
                    
                    # Next.js, Nuxt.jsの特別処理
                    if fw.lower() in ['next.js', 'nextjs']:
                        skills_data['frontend_frameworks']['Next.js'] = skills_data['frontend_frameworks'].get('Next.js', 0) + 1
                    elif fw.lower() in ['nuxt.js', 'nuxtjs']:
                        skills_data['frontend_frameworks']['Nuxt.js'] = skills_data['frontend_frameworks'].get('Nuxt.js', 0) + 1
            
            # データベース情報
            if hasattr(app, 'database') and app.database:
                if 'type' in app.database and app.database['type']:
                    db = app.database['type']
                    skills_data['databases'][db] = skills_data['databases'].get(db, 0) + 1
            
            # 開発環境情報からDevOps技術を抽出
            if hasattr(app, 'development_environment') and app.development_environment:
                # Dockerを使用しているかチェック
                if 'virtualization' in app.development_environment:
                    virtualization = app.development_environment['virtualization']
                    if virtualization:
                        if 'docker' in virtualization.lower():
                            skills_data['devops_tools']['Docker'] = skills_data['devops_tools'].get('Docker', 0) + 1
                        if 'docker-compose' in virtualization.lower() or 'docker compose' in virtualization.lower():
                            skills_data['devops_tools']['Docker Compose'] = skills_data['devops_tools'].get('Docker Compose', 0) + 1
                
                # CI/CDツールを抽出
                if 'ci_cd' in app.development_environment and app.development_environment['ci_cd']:
                    ci_cd = app.development_environment['ci_cd']
                    if ci_cd:
                        skills_data['devops_tools'][ci_cd] = skills_data['devops_tools'].get(ci_cd, 0) + 1
                
                # インフラ情報からクラウド技術を抽出
                if 'infrastructure' in app.development_environment and app.development_environment['infrastructure']:
                    infra = app.development_environment['infrastructure']
                    if infra:
                        # AWSやAzure、GCPなどのクラウドサービスが含まれているか確認
                        cloud_providers = ['aws', 'azure', 'gcp', 'google cloud', 'heroku', 'vercel', 'netlify']
                        for provider in cloud_providers:
                            if provider in infra.lower():
                                # プロバイダー名を整形（例：awsをAWS、gcpをGCP）
                                if provider == 'aws':
                                    clean_name = 'AWS'
                                elif provider == 'gcp' or provider == 'google cloud':
                                    clean_name = 'Google Cloud'
                                else:
                                    clean_name = provider.capitalize()
                                skills_data['cloud_services'][clean_name] = skills_data['cloud_services'].get(clean_name, 0) + 1
                
                # PythonAnywhereも追加
                if 'pythonanywhere' in str(app.development_environment).lower():
                    skills_data['cloud_services']['PythonAnywhere'] = skills_data['cloud_services'].get('PythonAnywhere', 0) + 1
            
            # アプリの種類から専門分野を推測
            if hasattr(app, 'app_types') and app.app_types:
                for app_type in app.app_types:
                    specializations_count[app_type] = specializations_count.get(app_type, 0) + 1
        
        # 各カテゴリで利用頻度の高い技術をスキルとして設定
        # 各辞書を頻度順にソート
        for category in skills_data:
            skills_data[category] = dict(sorted(
                skills_data[category].items(), 
                key=lambda item: item[1], 
                reverse=True
            ))
        
        # 既存のスキルとマージ
        current_skills = self.skills or {}
        
        # 自動生成したスキルを反映
        current_skills.update({
            'auto_generated': True,
            'last_updated': timezone.now().isoformat(),
            'data': skills_data
        })
        
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
