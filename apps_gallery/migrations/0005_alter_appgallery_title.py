# Generated by Django 5.1.6 on 2025-02-12 14:40

from django.db import migrations, models


class Migration(migrations.Migration):

    dependencies = [
        ('apps_gallery', '0004_split_catchphrases'),
    ]

    operations = [
        migrations.AlterField(
            model_name='appgallery',
            name='title',
            field=models.CharField(blank=True, max_length=100, null=True, verbose_name='アプリ名'),
        ),
    ]
