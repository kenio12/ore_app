from django.db import models
from django.conf import settings
from django.db.models.signals import post_save
from django.dispatch import receiver

class Profile(models.Model):
    user = models.OneToOneField(settings.AUTH_USER_MODEL, on_delete=models.CASCADE, related_name='profile')
    avatar = models.JSONField(null=True, blank=True)
    bio = models.TextField('自己紹介', max_length=500, blank=True)
    social_github = models.CharField('GitHub', max_length=100, blank=True)
    social_twitter = models.CharField('X（旧Twitter）', max_length=100, blank=True)
    created_at = models.DateTimeField('作成日時', auto_now_add=True)
    updated_at = models.DateTimeField('更新日時', auto_now=True)

    def __str__(self):
        return f"{self.user.username}のプロフィール"

    @property
    def avatar_url(self):
        """アバター画像のURLを返す。Cloudinaryに対応。"""
        if self.avatar and 'url' in self.avatar:
            return self.avatar['url']
        return None

# ユーザーが作成されたときに自動的にプロフィールも作成する
@receiver(post_save, sender=settings.AUTH_USER_MODEL)
def create_or_update_user_profile(sender, instance, created, **kwargs):
    if created:
        Profile.objects.create(user=instance)
    else:
        # プロフィールが既に存在している場合は更新
        if hasattr(instance, 'profile'):
            instance.profile.save()
