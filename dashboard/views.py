from django.shortcuts import render, redirect
from django.contrib.auth.decorators import login_required
from apps_gallery.models import AppGallery

@login_required
def index(request):
    """ダッシュボードのメインページ"""
    context = {
        'user': request.user,
        'recent_apps': AppGallery.objects.filter(author=request.user).order_by('-created_at')[:5],
        'total_apps': AppGallery.objects.filter(author=request.user).count(),
    }
    return render(request, 'dashboard/index.html', context)

@login_required
def apps(request):
    """アプリ管理ページ"""
    context = {
        'apps': AppGallery.objects.filter(author=request.user).order_by('-created_at')
    }
    return render(request, 'dashboard/apps.html', context)

@login_required
def profile(request):
    """プロフィール設定ページ - profiles アプリへリダイレクト"""
    return redirect('profiles:profile_detail')

@login_required
def account(request):
    """アカウント設定ページ"""
    return render(request, 'dashboard/account.html')

@login_required
def notifications(request):
    """通知設定ページ"""
    return render(request, 'dashboard/notifications.html')
