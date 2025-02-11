from django.db import migrations, models

def split_catchphrases(apps, schema_editor):
    AppGallery = apps.get_model('apps_gallery', 'AppGallery')
    for app in AppGallery.objects.all():
        try:
            if hasattr(app, 'catchphrases'):
                if isinstance(app.catchphrases, str):
                    app.catchphrase_1 = app.catchphrases
                elif isinstance(app.catchphrases, list):
                    if len(app.catchphrases) > 0:
                        app.catchphrase_1 = app.catchphrases[0]
                    if len(app.catchphrases) > 1:
                        app.catchphrase_2 = app.catchphrases[1]
                    if len(app.catchphrases) > 2:
                        app.catchphrase_3 = app.catchphrases[2]
            app.save()
        except Exception as e:
            print(f"Error migrating app {app.id}: {str(e)}")

class Migration(migrations.Migration):

    dependencies = [
        ('apps_gallery', '0003_appgallery_thumbnail_alter_appgallery_dev_status'),
    ]

    operations = [
        migrations.AddField(
            model_name='appgallery',
            name='catchphrase_1',
            field=models.CharField(verbose_name='キャッチコピー1', max_length=100, blank=True),
        ),
        migrations.AddField(
            model_name='appgallery',
            name='catchphrase_2',
            field=models.CharField(verbose_name='キャッチコピー2', max_length=100, blank=True),
        ),
        migrations.AddField(
            model_name='appgallery',
            name='catchphrase_3',
            field=models.CharField(verbose_name='キャッチコピー3', max_length=100, blank=True),
        ),
        migrations.RunPython(split_catchphrases),
        migrations.RemoveField(
            model_name='appgallery',
            name='catchphrases',
        ),
    ] 