from django.db import models
from django.conf import settings

# Create your models here.

class Message(models.Model):
    sender = models.ForeignKey(
        settings.AUTH_USER_MODEL, 
        on_delete=models.CASCADE, 
        related_name='sent_messages',
        verbose_name='送信者'
    )
    recipient = models.ForeignKey(
        settings.AUTH_USER_MODEL, 
        on_delete=models.CASCADE, 
        related_name='received_messages',
        verbose_name='受信者'
    )
    conversation = models.ForeignKey(
        'Conversation',
        on_delete=models.CASCADE,
        related_name='messages',
        verbose_name='会話',
        null=True
    )
    content = models.TextField('メッセージ内容')
    timestamp = models.DateTimeField('送信日時', auto_now_add=True)
    is_read = models.BooleanField('既読', default=False)
    
    # メッセージタイプの選択肢
    MESSAGE_TYPE_CHOICES = [
        ('normal', '通常メッセージ'),
        ('enter', '入室通知'),
        ('leave', '退室通知'),
    ]
    
    # メッセージタイプフィールド（デフォルトは通常メッセージ）
    message_type = models.CharField(
        'メッセージタイプ', 
        max_length=10, 
        choices=MESSAGE_TYPE_CHOICES, 
        default='normal'
    )
    
    class Meta:
        ordering = ['timestamp']
        verbose_name = 'メッセージ'
        verbose_name_plural = 'メッセージ'
    
    def __str__(self):
        return f"{self.sender.username} から {self.recipient.username} へのメッセージ"

# 会話スレッドを管理するモデル
class Conversation(models.Model):
    participants = models.ManyToManyField(
        settings.AUTH_USER_MODEL,
        related_name='conversations',
        verbose_name='参加者'
    )
    updated_at = models.DateTimeField('最終更新日時', auto_now=True)
    created_at = models.DateTimeField('作成日時', auto_now_add=True)
    
    class Meta:
        ordering = ['-updated_at']
        verbose_name = '会話'
        verbose_name_plural = '会話'
    
    def __str__(self):
        return f"会話 {self.id}: {', '.join([user.username for user in self.participants.all()])}"
    
    def get_messages(self):
        """この会話に属するすべてのメッセージを取得"""
        participant_ids = self.participants.values_list('id', flat=True)
        return Message.objects.filter(
            sender__in=participant_ids,
            recipient__in=participant_ids
        ).order_by('timestamp')
    
    @classmethod
    def get_or_create_conversation(cls, user1, user2):
        """2人のユーザー間の会話を取得または作成"""
        # 両方のユーザーが参加している会話を検索
        conversations = cls.objects.filter(participants=user1).filter(participants=user2)
        
        if conversations.exists():
            return conversations.first()
        else:
            # 新しい会話を作成
            conversation = cls.objects.create()
            conversation.participants.add(user1, user2)
            return conversation
