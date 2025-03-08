from django.core.management.base import BaseCommand
from django.contrib.auth import get_user_model
from profiles.models import Profile

class Command(BaseCommand):
    help = 'すべてのユーザープロフィールのスキル情報を最新の集計ロジックで更新します'

    def add_arguments(self, parser):
        # 特定のユーザーだけ更新するオプション
        parser.add_argument(
            '--user',
            type=str,
            help='特定のユーザー名を指定して更新します',
        )

    def handle(self, *args, **options):
        username = options.get('user')
        
        if username:
            # 特定ユーザーのみ更新
            try:
                user = get_user_model().objects.get(username=username)
                self.stdout.write(f'ユーザー "{username}" のスキル情報を更新中...')
                user.profile.update_skills_from_apps()
                self.stdout.write(self.style.SUCCESS(f'ユーザー "{username}" のスキル情報を更新しました！'))
            except get_user_model().DoesNotExist:
                self.stdout.write(self.style.ERROR(f'ユーザー "{username}" が見つかりませんでした'))
        else:
            # 全ユーザーを更新
            users = get_user_model().objects.all()
            total = users.count()
            updated = 0
            
            self.stdout.write(f'全 {total} ユーザーのスキル情報を更新中...')
            
            for user in users:
                try:
                    user.profile.update_skills_from_apps()
                    updated += 1
                    if updated % 10 == 0:  # 10ユーザーごとに進捗を表示
                        self.stdout.write(f'進捗: {updated}/{total} 更新済み')
                except Exception as e:
                    self.stdout.write(self.style.ERROR(f'ユーザー "{user.username}" の更新中にエラー: {e}'))
            
            self.stdout.write(self.style.SUCCESS(f'完了！ {updated}/{total} のユーザープロフィールを更新しました！')) 