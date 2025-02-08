from django.core.management.base import BaseCommand
from django.utils import timezone
import cloudinary.api
from datetime import timedelta

class Command(BaseCommand):
    help = '未使用の画像をCloudinaryから削除'

    def handle(self, *args, **options):
        try:
            # 24時間以上前の未使用画像を取得
            yesterday = timezone.now() - timedelta(hours=24)
            
            # Cloudinaryから画像一覧を取得
            result = cloudinary.api.resources(
                type='upload',
                prefix='app_screenshots/',  # アプリのフォルダを指定
                max_results=500
            )

            for resource in result['resources']:
                # アプリに紐付いていない古い画像を削除
                created_at = timezone.datetime.fromtimestamp(
                    resource['created_at'], tz=timezone.utc
                )
                if created_at < yesterday:
                    cloudinary.uploader.destroy(resource['public_id'])
                    self.stdout.write(
                        self.style.SUCCESS(f'Deleted: {resource["public_id"]}')
                    )

        except Exception as e:
            self.stdout.write(
                self.style.ERROR(f'Error: {str(e)}')
            ) 