# Generated by Django 5.1.6 on 2025-03-10 12:10

from django.db import migrations, models


class Migration(migrations.Migration):

    dependencies = [
        ('accounts', '0001_initial'),
    ]

    operations = [
        migrations.AddField(
            model_name='customuser',
            name='verification_code',
            field=models.CharField(blank=True, max_length=50, null=True, verbose_name='確認コード'),
        ),
    ]
