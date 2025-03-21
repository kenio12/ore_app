# Generated by Django 5.1.6 on 2025-02-13 14:55

from django.db import migrations, models


class Migration(migrations.Migration):

    dependencies = [
        ('apps_gallery', '0006_appgallery_hardware_specs'),
    ]

    operations = [
        migrations.AddField(
            model_name='appgallery',
            name='development_environment',
            field=models.JSONField(blank=True, default=dict, help_text='開発環境の情報（エディタ、バージョン管理、CI/CD、仮想化ツール、チームサイズ、コミュニケーションツール、インフラ、APIツール、モニタリングツール）', verbose_name='開発環境'),
        ),
        migrations.AlterField(
            model_name='appgallery',
            name='hardware_specs',
            field=models.JSONField(blank=True, default=dict, help_text='開発時に使用したパソコンのスペック情報（タイプ、メーカー、OS、CPU、メモリ、ストレージ、モニター構成、ネット環境）', verbose_name='開発環境のスペック'),
        ),
    ]
