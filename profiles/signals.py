from django.db.models.signals import post_save
from django.dispatch import receiver
from accounts.models import CustomUser
from .models import Profile

@receiver(post_save, sender=CustomUser)
def create_user_profile(sender, instance, created, **kwargs):
    """ユーザー作成時に自動的にプロフィールも作成"""
    if created:
        Profile.objects.create(user=instance)

@receiver(post_save, sender=CustomUser)
def save_user_profile(sender, instance, **kwargs):
    """ユーザー保存時にプロフィールも保存"""
    if not hasattr(instance, 'profile'):
        Profile.objects.create(user=instance)
    instance.profile.save() 