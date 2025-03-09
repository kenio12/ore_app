from django.core.management.base import BaseCommand
from django.contrib.auth import get_user_model
from profiles.models import Profile
from apps_gallery.models import AppGallery

User = get_user_model()

class Command(BaseCommand):
    help = 'ユーザープロフィールの統計情報を更新する'

    def add_arguments(self, parser):
        parser.add_argument(
            '--user',
            type=int,
            help='特定のユーザーIDのプロフィールのみを更新（指定しない場合は全ユーザー）',
        )

    def handle(self, *args, **options):
        user_id = options['user']
        
        if user_id:
            try:
                # 特定のユーザーのみ更新
                user = User.objects.get(id=user_id)
                self.update_user_profile(user)
                self.stdout.write(self.style.SUCCESS(f'ユーザー {user.username} のプロフィール統計を更新しました'))
            except User.DoesNotExist:
                self.stdout.write(self.style.ERROR(f'ユーザーID {user_id} は見つかりません'))
        else:
            # 全ユーザーの統計を更新
            users = User.objects.all()
            users_count = users.count()
            
            self.stdout.write(f'全{users_count}人のユーザープロフィール統計を更新します...')
            
            updated_count = 0
            for user in users:
                if self.update_user_profile(user):
                    updated_count += 1
                    
            self.stdout.write(self.style.SUCCESS(f'{updated_count}人のユーザープロフィールを更新しました'))
    
    def update_user_profile(self, user):
        """特定のユーザーのプロフィール統計を更新する"""
        try:
            profile = user.profile
            # アプリが存在するか確認
            if AppGallery.objects.filter(author=user).exists():
                # プロフィールの統計情報を更新
                profile.update_skills_from_apps()
                return True
            else:
                self.stdout.write(f'ユーザー {user.username} はアプリを投稿していません。スキップします')
                return False
        except Profile.DoesNotExist:
            self.stdout.write(self.style.WARNING(f'ユーザー {user.username} にプロフィールがありません'))
            return False
        except Exception as e:
            self.stdout.write(self.style.ERROR(f'エラー発生：{str(e)}'))
            return False 