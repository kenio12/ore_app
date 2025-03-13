# Generated by Django 5.1.6 on 2025-03-12 14:09

from django.db import migrations, models


class Migration(migrations.Migration):

    dependencies = [
        ('chats', '0002_message_conversation'),
    ]

    operations = [
        migrations.AddField(
            model_name='message',
            name='message_type',
            field=models.CharField(choices=[('normal', '通常メッセージ'), ('enter', '入室通知'), ('leave', '退室通知')], default='normal', max_length=10, verbose_name='メッセージタイプ'),
        ),
    ]
