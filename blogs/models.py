from django.db import models
from django.conf import settings
from django.utils import timezone
from django.utils.text import slugify
from django.urls import reverse
from cloudinary.models import CloudinaryField
import uuid
import re
from taggit.managers import TaggableManager
from apps_gallery.models import AppGallery

class Post(models.Model):
    """ブログ投稿モデル"""
    title = models.CharField(max_length=200, verbose_name="タイトル")
    slug = models.SlugField(max_length=250, unique=True, blank=True)
    content = models.TextField(verbose_name="内容")
    author = models.ForeignKey(settings.AUTH_USER_MODEL, on_delete=models.CASCADE, related_name='blog_posts', verbose_name="投稿者")
    created_at = models.DateTimeField(auto_now_add=True, verbose_name="作成日時")
    updated_at = models.DateTimeField(auto_now=True, verbose_name="更新日時")
    published_at = models.DateTimeField(verbose_name="公開日時")
    is_published = models.BooleanField(default=True, verbose_name="公開状態")
    
    # アイキャッチ画像
    featured_image = models.URLField(verbose_name="アイキャッチ画像", blank=True, null=True)
    
    # 伝えたいこと
    message = models.CharField(max_length=100, verbose_name="伝えたいこと", blank=True, null=True)
    
    # タグ
    tags = TaggableManager(blank=True, verbose_name="タグ")
    
    # 関連アプリ
    related_app = models.ForeignKey(AppGallery, on_delete=models.SET_NULL, blank=False, null=True, related_name='blog_posts', verbose_name="関連アプリ")
    
    class Meta:
        ordering = ['-published_at']
        verbose_name = "ブログ投稿"
        verbose_name_plural = "ブログ投稿"
    
    def __str__(self):
        return self.title
    
    def save(self, *args, **kwargs):
        if not self.slug:
            # スラグがない場合は生成
            base_slug = slugify(self.title)
            if not base_slug:  # 日本語などでslugifyが空文字を返す場合
                # タイトルから英数字以外を削除し、スペースをハイフンに変換
                base_slug = re.sub(r'[^\w\s]', '', self.title)
                base_slug = re.sub(r'\s+', '-', base_slug)
                if not base_slug:  # それでも空の場合
                    base_slug = 'post'
            
            unique_id = str(uuid.uuid4())[:8]
            self.slug = f"{base_slug}-{unique_id}"
        super().save(*args, **kwargs)
    
    def get_absolute_url(self):
        return reverse('blogs:post_detail', args=[self.slug])
    
    @property
    def comment_count(self):
        return self.comments.count()
    
    @property
    def like_count(self):
        return self.likes.count()
    
    @property
    def featured_image_url(self):
        """アイキャッチ画像のURLを返す"""
        if self.featured_image:
            return self.featured_image
        return None


class Comment(models.Model):
    """コメントモデル"""
    post = models.ForeignKey(Post, on_delete=models.CASCADE, related_name='comments', verbose_name="投稿")
    author = models.ForeignKey(settings.AUTH_USER_MODEL, on_delete=models.CASCADE, related_name='blog_comments', verbose_name="投稿者")
    content = models.TextField(verbose_name="内容")
    created_at = models.DateTimeField(auto_now_add=True, verbose_name="作成日時")
    is_approved = models.BooleanField(default=True, verbose_name="承認状態")
    
    class Meta:
        ordering = ['created_at']
        verbose_name = "コメント"
        verbose_name_plural = "コメント"
    
    def __str__(self):
        return f"{self.author.username}のコメント: {self.content[:30]}"


class Like(models.Model):
    """いいねモデル"""
    post = models.ForeignKey(Post, on_delete=models.CASCADE, related_name='likes', verbose_name="投稿")
    user = models.ForeignKey(settings.AUTH_USER_MODEL, on_delete=models.CASCADE, related_name='blog_likes', verbose_name="ユーザー")
    created_at = models.DateTimeField(auto_now_add=True, verbose_name="作成日時")
    
    class Meta:
        unique_together = ('post', 'user')
        verbose_name = "いいね"
        verbose_name_plural = "いいね"
    
    def __str__(self):
        return f"{self.user.username}が{self.post.title}にいいね"
