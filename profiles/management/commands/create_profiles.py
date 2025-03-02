from django.core.management.base import BaseCommand
from django.conf import settings
from profiles.models import Profile

class Command(BaseCommand):
    help = '既存のユーザーにプロフィールを作成する'

    def handle(self, *args, **options):
        User = settings.AUTH_USER_MODEL
        from django.apps import apps
        User = apps.get_model('accounts', 'CustomUser')
        
        users_without_profile = 0
        profiles_created = 0
        
        for user in User.objects.all():
            try:
                # プロフィールにアクセスして存在するか確認
                profile = user.profile
                self.stdout.write(self.style.SUCCESS(f'ユーザー {user.username} はすでにプロフィールを持っています'))
            except Profile.DoesNotExist:
                # プロフィールが存在しない場合は作成
                Profile.objects.create(user=user)
                profiles_created += 1
                self.stdout.write(self.style.SUCCESS(f'ユーザー {user.username} のプロフィールを作成しました'))
            except Exception as e:
                # その他のエラー
                users_without_profile += 1
                self.stdout.write(self.style.ERROR(f'ユーザー {user.username} のプロフィール作成エラー: {e}'))
        
        self.stdout.write(self.style.SUCCESS(f'処理完了: {profiles_created} 件のプロフィールを作成しました'))
        if users_without_profile > 0:
            self.stdout.write(self.style.WARNING(f'{users_without_profile} 人のユーザーはプロフィールを作成できませんでした')) 