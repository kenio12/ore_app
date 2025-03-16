from django import forms
from django.db import models
from django.db.models import Max
from django.db.models.functions import Cast, Substr
from django.utils.text import slugify
from .models import Post, Comment
from apps_gallery.models import AppGallery
import re
from django.utils import timezone

class BlogPostForm(forms.ModelForm):
    """ブログ投稿フォーム"""
    class Meta:
        model = Post
        fields = ('title', 'slug', 'content', 'featured_image', 'message', 'tags', 'related_app', 'published_at', 'is_published')
        widgets = {
            'title': forms.TextInput(attrs={'class': 'form-control', 'placeholder': 'タイトルを入力してください'}),
            'slug': forms.TextInput(attrs={'class': 'form-control', 'placeholder': 'URLに使用される識別子（例: my-first-post）'}),
            'content': forms.Textarea(attrs={'class': 'form-control', 'rows': 10, 'placeholder': '内容を入力してください'}),
            'featured_image': forms.HiddenInput(),  # 隠しフィールドに変更
            'message': forms.TextInput(attrs={'class': 'form-control', 'placeholder': 'この記事で伝えたいことを一言で（例：Djangoの基本を学ぼう！）'}),
            'tags': forms.TextInput(attrs={'class': 'form-control', 'placeholder': 'タグをカンマ区切りで入力（例: Django, Python, 初心者）'}),
            'related_app': forms.Select(attrs={'class': 'form-select'}),
            'published_at': forms.DateTimeInput(attrs={'class': 'form-control', 'type': 'datetime-local'}),
            'is_published': forms.HiddenInput(),  # 実際の値はラジオボタンから取得
        }
        labels = {
            'title': 'タイトル',
            'slug': 'スラッグ',
            'content': '内容',
            'featured_image': 'アイキャッチ画像',
            'message': '伝えたいこと',
            'tags': 'タグ',
            'related_app': '関連アプリ',
            'published_at': '公開日時',
            'is_published': '公開状態',
        }
        help_texts = {
            'slug': 'URLに使用される識別子です。英数字、ハイフン、アンダースコアのみ使用できます。',
            'featured_image': 'ブログ投稿のメイン画像を選択してください。',
            'message': '記事で伝えたいことを一言で表現してください（100文字以内）。',
            'tags': 'カンマ区切りでタグを入力してください。',
            'related_app': 'まず開発予定のアプリの登録から始めよう！関連するアプリを選択してください（必須）。',
            'published_at': '投稿の公開日時を設定します。開発日記の場合は実際の開発日を選択してください。',
        }
    
    def __init__(self, *args, **kwargs):
        user = kwargs.pop('user', None)
        super().__init__(*args, **kwargs)
        
        # ユーザーが作成したアプリのみを選択肢として表示
        if user:
            self.fields['related_app'].queryset = AppGallery.objects.filter(author=user)
        else:
            self.fields['related_app'].queryset = AppGallery.objects.none()
        
        # 関連アプリを必須フィールドに設定
        self.fields['related_app'].required = True
        
        # 公開日時のデフォルト値を設定
        if not self.instance.pk or not self.instance.published_at:
            self.initial['published_at'] = timezone.now().strftime('%Y-%m-%dT%H:%M')
        
        # 関連アプリが選択されている場合、タイトルを自動生成
        if self.instance.related_app:
            # 同じアプリに関連する投稿の数を取得
            app_posts = Post.objects.filter(related_app=self.instance.related_app).count()
            # 次の番号を設定（新規作成の場合は+1、編集の場合はそのまま）
            if not self.instance.pk:
                next_number = app_posts + 1
            else:
                # 既存の投稿の場合、タイトルから番号を抽出
                match = re.search(r'その(\d+)$', self.instance.title)
                if match:
                    next_number = int(match.group(1))
                else:
                    next_number = app_posts
            
            # タイトルを設定
            self.initial['title'] = f"{self.instance.related_app.title}の開発ブログ その{next_number}"
            
            # タイトルフィールドを読み取り専用に設定
            self.fields['title'].widget.attrs['readonly'] = True
    
    def clean_title(self):
        title = self.cleaned_data['title']
        if len(title) < 3:
            raise forms.ValidationError('タイトルは3文字以上で入力してください。')
        return title
    
    def clean_is_published(self):
        # ラジオボタンから送信された値を処理
        is_published = self.cleaned_data.get('is_published')
        if isinstance(is_published, str):
            return is_published.lower() == 'true'
        return bool(is_published)
    
    def clean_published_at(self):
        published_at = self.cleaned_data.get('published_at')
        if not published_at:
            # 公開日時が指定されていない場合は現在時刻を設定
            return timezone.now()
        return published_at
    
    def save(self, commit=True):
        instance = super().save(commit=False)
        
        # 関連アプリがある場合、タイトルが空ならタイトルを自動生成
        if instance.related_app and not instance.title:
            # 同じアプリに関連する投稿の最大番号を取得
            app_posts = Post.objects.filter(related_app=instance.related_app)
            max_number = app_posts.count()
            
            # 次の番号を設定
            next_number = max_number + 1
            instance.title = f"{instance.related_app.title}の開発ブログ その{next_number}"
        
        # スラッグを生成（定形パターン）
        if not instance.slug:
            # 関連アプリがある場合はそのスラッグをベースにする
            if instance.related_app:
                # 同じアプリに関連する投稿を検索
                related_posts = Post.objects.filter(related_app=instance.related_app)
                
                if related_posts.exists():
                    # 既存の投稿がある場合は、最初の投稿のスラッグをベースにする
                    first_post = related_posts.order_by('created_at').first()
                    
                    # 自分自身は除外
                    if first_post.id == instance.id:
                        # 自分が最初の投稿の場合はスラッグが必須
                        if not instance.slug:
                            raise forms.ValidationError('初回の投稿ではスラッグを入力してください。')
                    else:
                        # スラッグからベース部分を抽出
                        base_slug = first_post.slug
                        if '-blog-' in base_slug:
                            base_slug = base_slug.split('-blog-')[0]
                        
                        # 同じベーススラッグを持つ投稿の最大番号を取得
                        max_number = 0
                        for post in related_posts:
                            if post.slug.startswith(f"{base_slug}-blog-"):
                                match = re.search(f"{re.escape(base_slug)}-blog-(\d+)$", post.slug)
                                if match:
                                    number = int(match.group(1))
                                    max_number = max(max_number, number)
                        
                        # 次の番号を設定
                        next_number = max_number + 1
                        instance.slug = f"{base_slug}-blog-{next_number}"
                else:
                    # 初回の投稿の場合はスラッグが必須（フォームで入力済み）
                    if not instance.slug:
                        raise forms.ValidationError('初回の投稿ではスラッグを入力してください。')
            else:
                # 関連アプリがない場合は通常のスラッグ生成
                slug = slugify(instance.title)
                if not slug:  # 日本語などでslugifyが空文字を返す場合
                    # 英数字以外を削除し、スペースをハイフンに変換
                    slug = re.sub(r'[^\w\s]', '', instance.title)
                    slug = re.sub(r'\s+', '-', slug)
                    if not slug:  # それでも空の場合
                        slug = 'post'
                
                # 重複チェック
                original_slug = slug
                counter = 1
                while Post.objects.filter(slug=slug).exclude(id=instance.id).exists():
                    slug = f"{original_slug}-{counter}"
                    counter += 1
                
                instance.slug = slug
        
        if commit:
            instance.save()
            # タグを保存（save_m2mが必要）
            self.save_m2m()
        return instance


class CommentForm(forms.ModelForm):
    """コメントフォーム"""
    class Meta:
        model = Comment
        fields = ('content',)
        widgets = {
            'content': forms.Textarea(attrs={'class': 'form-control', 'rows': 3, 'placeholder': 'コメントを入力してください'}),
        }
        labels = {
            'content': 'コメント',
        }
    
    def clean_content(self):
        content = self.cleaned_data['content']
        if len(content) < 5:
            raise forms.ValidationError('コメントは5文字以上で入力してください。')
        return content 