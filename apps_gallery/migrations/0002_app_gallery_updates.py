from django.db import migrations, models
import django.db.models.deletion
from django.conf import settings

def migrate_status(apps, schema_editor):
    """
    既存のstatusフィールドから、dev_statusとstatusに分離するマイグレーション
    """
    AppGallery = apps.get_model('apps_gallery', 'AppGallery')
    for app in AppGallery.objects.all():
        if app.status in ['completed', 'in_development']:
            app.dev_status = app.status
            app.status = 'public'  # または適切なデフォルト値
        app.save()

class Migration(migrations.Migration):

    dependencies = [
        migrations.swappable_dependency(settings.AUTH_USER_MODEL),
        ('apps_gallery', '0001_initial'),
    ]

    operations = [
        # 作者フィールドの追加
        migrations.AddField(
            model_name='appgallery',
            name='author',
            field=models.ForeignKey(
                on_delete=django.db.models.deletion.CASCADE,
                related_name='apps',
                to=settings.AUTH_USER_MODEL,
                verbose_name='作者',
                # 既存のデータがある場合は、デフォルトのユーザーIDを設定する必要があります
                default=1  # 注意: 実際の環境に合わせて適切なユーザーIDを設定してください
            ),
            preserve_default=False,
        ),
        
        # 開発状況フィールドの追加
        migrations.AddField(
            model_name='appgallery',
            name='dev_status',
            field=models.CharField(
                choices=[('completed', '完成'), ('in_development', '開発中')],
                default='in_development',
                max_length=20,
                verbose_name='開発状況'
            ),
        ),
        
        # 既存のstatusフィールドの選択肢を変更
        migrations.AlterField(
            model_name='appgallery',
            name='status',
            field=models.CharField(
                choices=[('draft', '非公開'), ('public', '公開')],
                default='draft',
                max_length=20,
                verbose_name='公開状態'
            ),
        ),
        
        # データ移行の実行
        migrations.RunPython(migrate_status),
    ] 