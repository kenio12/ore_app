from django.shortcuts import render, get_object_or_404, redirect
from django.contrib.auth.decorators import login_required
from django.http import JsonResponse, HttpResponseRedirect
from django.views.decorators.http import require_POST
from django.contrib import messages
from django.core.paginator import Paginator, EmptyPage, PageNotAnInteger
from django.db.models import Count
from django.utils.text import slugify
from django.utils import timezone
import re
import cloudinary
import cloudinary.uploader
import os

from .models import Post, Comment, Like, AppGallery
from .forms import BlogPostForm, CommentForm

def post_list(request):
    """ブログ投稿一覧ページ"""
    # タグによるフィルタリングがある場合は、そのまま表示
    tag = request.GET.get('tag')
    if tag:
        # 公開済みの投稿のみ表示
        posts = Post.objects.filter(is_published=True)
        posts = posts.filter(tags__name=tag)
        
        # ページネーション
        paginator = Paginator(posts, 10)  # 1ページあたり10件
        page = request.GET.get('page')
        try:
            posts = paginator.page(page)
        except PageNotAnInteger:
            # ページ番号が整数でない場合は、最初のページを表示
            posts = paginator.page(1)
        except EmptyPage:
            # ページ番号が範囲外の場合は、最後のページを表示
            posts = paginator.page(paginator.num_pages)
        
        context = {
            'posts': posts,
            'tag': tag,  # テンプレートにタグ名を渡す
        }
        
        return render(request, 'blogs/post_list.html', context)
    else:
        # タグによるフィルタリングがない場合は、ホームページのブログタブにリダイレクト
        return HttpResponseRedirect('/#blogs-tab')

def post_detail(request, slug):
    """ブログ投稿詳細ページ"""
    post = get_object_or_404(Post, slug=slug, is_published=True)
    
    # コメント
    comments = post.comments.filter(is_approved=True)
    new_comment = None
    
    if request.method == 'POST':
        comment_form = CommentForm(data=request.POST)
        if comment_form.is_valid():
            new_comment = comment_form.save(commit=False)
            new_comment.post = post
            new_comment.author = request.user
            new_comment.save()
            messages.success(request, 'コメントを投稿しました。')
            return redirect('blogs:post_detail', slug=post.slug)
    else:
        comment_form = CommentForm()
    
    # 関連投稿
    post_tags_ids = post.tags.values_list('id', flat=True) if hasattr(post, 'tags') else []
    similar_posts = []
    if post_tags_ids:
        similar_posts = Post.objects.filter(tags__in=post_tags_ids, is_published=True).exclude(id=post.id)
        similar_posts = similar_posts.annotate(same_tags=Count('tags')).order_by('-same_tags', '-published_at')[:3]
    
    # いいね状態
    user_liked = Like.objects.filter(post=post, user=request.user).exists() if request.user.is_authenticated else False
    
    context = {
        'post': post,
        'comments': comments,
        'comment_form': comment_form,
        'similar_posts': similar_posts,
        'user_liked': user_liked,
    }
    return render(request, 'blogs/post_detail.html', context)

@login_required
def post_create(request):
    """ブログ投稿作成ページ"""
    if request.method == 'POST':
        form = BlogPostForm(request.POST, request.FILES, user=request.user)
        
        # 関連アプリが選択されている場合、タイトルを自動生成
        if 'related_app' in request.POST and request.POST['related_app']:
            related_app_id = request.POST['related_app']
            try:
                related_app = AppGallery.objects.get(id=related_app_id)
                
                # 同じアプリに関連する投稿の数を取得
                app_posts = Post.objects.filter(related_app=related_app).count()
                next_number = app_posts + 1
                
                # タイトルを自動生成
                auto_title = f"{related_app.title}の開発ブログ その{next_number}"
                
                # POSTデータを更新
                post_data = request.POST.copy()
                post_data['title'] = auto_title
                
                # 新しいフォームを作成
                form = BlogPostForm(post_data, request.FILES, user=request.user)
            except AppGallery.DoesNotExist:
                pass
        
        # Ajaxリクエストの場合は、フォームだけを返す
        if request.headers.get('X-Requested-With') == 'XMLHttpRequest':
            return render(request, 'blogs/post_form.html', {
                'form': form,
                'title': '新規投稿',
            })
        
        # デバッグ情報を出力
        print("POSTデータ:", request.POST)
        print("FILESデータ:", request.FILES)
        
        if form.is_valid():
            post = form.save(commit=False)
            post.author = request.user
            
            # アイキャッチ画像のURLは直接フォームから取得されるので、
            # 追加の処理は不要
            
            post.save()
            # タグとM2Mフィールドを保存
            form.save_m2m()
            messages.success(request, '投稿を作成しました。')
            return HttpResponseRedirect('/#blogs-tab')
        else:
            # バリデーションエラーを出力
            print("フォームエラー:", form.errors)
            for field, errors in form.errors.items():
                for error in errors:
                    messages.error(request, f"{field}: {error}")
    else:
        form = BlogPostForm(user=request.user)
    
    return render(request, 'blogs/post_form.html', {
        'form': form,
        'title': '新規投稿',
    })

@login_required
def post_edit(request, slug):
    """ブログ投稿編集ページ"""
    post = get_object_or_404(Post, slug=slug, author=request.user)
    
    if request.method == 'POST':
        form = BlogPostForm(request.POST, request.FILES, instance=post, user=request.user)
        
        # 関連アプリが選択されている場合、タイトルを自動生成
        if 'related_app' in request.POST and request.POST['related_app']:
            related_app_id = request.POST['related_app']
            try:
                related_app = AppGallery.objects.get(id=related_app_id)
                
                # 既存の投稿の場合、タイトルから番号を抽出
                current_title = post.title
                match = re.search(r'その(\d+)$', current_title)
                if match:
                    next_number = int(match.group(1))
                else:
                    # 同じアプリに関連する投稿の数を取得
                    app_posts = Post.objects.filter(related_app=related_app).count()
                    next_number = app_posts
                
                # タイトルを自動生成
                auto_title = f"{related_app.title}の開発ブログ その{next_number}"
                
                # POSTデータを更新
                post_data = request.POST.copy()
                post_data['title'] = auto_title
                
                # 新しいフォームを作成
                form = BlogPostForm(post_data, request.FILES, instance=post, user=request.user)
            except AppGallery.DoesNotExist:
                pass
        
        if form.is_valid():
            post = form.save(commit=False)
            post.updated_at = timezone.now()
            
            # アイキャッチ画像のURLは直接フォームから取得されるので、
            # 追加の処理は不要
            
            post.save()
            # タグとM2Mフィールドを保存
            form.save_m2m()
            messages.success(request, '投稿を更新しました。')
            return HttpResponseRedirect('/#blogs-tab')
    else:
        form = BlogPostForm(instance=post, user=request.user)
    
    return render(request, 'blogs/post_form.html', {
        'form': form,
        'title': '投稿の編集',
        'post': post,
    })

@login_required
def post_delete(request, slug):
    """ブログ投稿削除"""
    post = get_object_or_404(Post, slug=slug, author=request.user)
    
    if request.method == 'POST':
        post.delete()
        messages.success(request, '投稿を削除しました。')
        return redirect('blogs:my_posts')
    
    return render(request, 'blogs/post_confirm_delete.html', {'post': post})

@login_required
@require_POST
def like_toggle(request):
    """いいねの切り替え（Ajax）"""
    post_id = request.POST.get('post_id')
    post = get_object_or_404(Post, id=post_id)
    
    like, created = Like.objects.get_or_create(post=post, user=request.user)
    
    if not created:
        # すでにいいねしていた場合は削除
        like.delete()
        liked = False
    else:
        liked = True
    
    return JsonResponse({
        'liked': liked,
        'count': post.like_count,
    })

@login_required
def my_posts(request):
    """自分の投稿一覧"""
    posts = Post.objects.filter(author=request.user)
    
    # ページネーション
    paginator = Paginator(posts, 10)
    page = request.GET.get('page')
    try:
        posts = paginator.page(page)
    except PageNotAnInteger:
        posts = paginator.page(1)
    except EmptyPage:
        posts = paginator.page(paginator.num_pages)
    
    return render(request, 'blogs/my_posts.html', {'posts': posts})

@login_required
def upload_image(request):
    """画像アップロードエンドポイント"""
    if request.method == 'POST' and request.FILES.get('file'):
        try:
            # Cloudinaryの設定
            cloudinary.config(
                cloud_name=os.getenv('CLOUDINARY_CLOUD_NAME'),
                api_key=os.getenv('CLOUDINARY_API_KEY'),
                api_secret=os.getenv('CLOUDINARY_API_SECRET'),
                secure=True
            )
            
            # Cloudinaryにアップロード
            upload_result = cloudinary.uploader.upload(
                request.FILES['file'],
                folder='blog_featured_images'
            )
            
            # アップロード結果をJSONで返す
            return JsonResponse({
                'success': True,
                'secure_url': upload_result['secure_url']
            })
        except Exception as e:
            import traceback
            traceback.print_exc()
            return JsonResponse({
                'success': False,
                'error': str(e)
            }, status=500)
    
    return JsonResponse({
        'success': False,
        'error': 'Invalid request'
    }, status=400)
