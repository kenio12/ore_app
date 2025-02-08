from django.core.management.base import BaseCommand
from django.utils import timezone
import cloudinary.api
from datetime import timedelta
from apps_gallery.models import AppGallery

class Command(BaseCommand):
    help = '未使用の画像をCloudinaryから削除'

    def handle(self, *args, **options):
        try:
            # 1. DBに登録されている有効な画像のpublic_idを収集
            valid_public_ids = set()
            for app in AppGallery.objects.all():
                for screenshot in app.screenshots:
                    if 'public_id' in screenshot:
                        valid_public_ids.add(screenshot['public_id'])
            
            # 2. 24時間以上前の未使用画像を取得
            yesterday = timezone.now() - timedelta(hours=24)
            
            # 3. Cloudinaryから画像一覧を取得（フォルダごとに）
            temp_result = cloudinary.api.resources(
                type='upload',
                prefix='app_screenshots/temp/',  # 一時フォルダ
                max_results=500
            )
            
            app_result = cloudinary.api.resources(
                type='upload',
                prefix='app_screenshots/app_',    # アプリフォルダ
                max_results=500
            )

            deleted_count = 0
            for resource in temp_result['resources']:
                public_id = resource['public_id']
                created_at = timezone.datetime.fromtimestamp(
                    resource['created_at'], tz=timezone.utc
                )
                
                # DBに登録されていない AND 24時間以上経過した画像を削除
                if public_id not in valid_public_ids and created_at < yesterday:
                    cloudinary.uploader.destroy(public_id)
                    deleted_count += 1
                    self.stdout.write(
                        self.style.SUCCESS(f'Deleted: {public_id}')
                    )

            for resource in app_result['resources']:
                public_id = resource['public_id']
                created_at = timezone.datetime.fromtimestamp(
                    resource['created_at'], tz=timezone.utc
                )
                
                # DBに登録されていない AND 24時間以上経過した画像を削除
                if public_id not in valid_public_ids and created_at < yesterday:
                    cloudinary.uploader.destroy(public_id)
                    deleted_count += 1
                    self.stdout.write(
                        self.style.SUCCESS(f'Deleted: {public_id}')
                    )

            self.stdout.write(
                self.style.SUCCESS(f'クリーンアップ完了: {deleted_count}個の未使用画像を削除しました')
            )

        except Exception as e:
            self.stdout.write(
                self.style.ERROR(f'Error: {str(e)}')
            ) 