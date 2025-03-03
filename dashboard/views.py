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
    """プロフィール設定ページ - ダッシュボード内に表示"""
    from django.apps import apps
    Profile = apps.get_model('profiles', 'Profile')
    
    # CPU・メモリ・ストレージなどのタイプ情報を取得
    from profiles.views import (
        get_pc_types, get_device_types, get_cpu_types, get_memory_sizes,
        get_storage_types, get_monitor_counts, get_internet_types
    )
    
    try:
        profile = Profile.objects.get(user=request.user)
    except Profile.DoesNotExist:
        profile = None
    
    context = {
        'user': request.user,
        'profile': profile,
        'pc_types': get_pc_types(),
        'device_types': get_device_types(),
        'cpu_types': get_cpu_types(),
        'memory_sizes': get_memory_sizes(),
        'storage_types': get_storage_types(),
        'monitor_counts': get_monitor_counts(),
        'internet_types': get_internet_types(),
    }
    
    return render(request, 'dashboard/profile.html', context)

@login_required
def account(request):
    """アカウント設定ページ"""
    return render(request, 'dashboard/account.html')

@login_required
def notifications(request):
    """通知設定ページ"""
    return render(request, 'dashboard/notifications.html')

@login_required
def analytics(request):
    """アナリティクスページ - ユーザーのアプリに関する統計情報を表示"""
    # ユーザーのアプリを取得
    user_apps = AppGallery.objects.filter(author=request.user)
    
    # 公開済みと未公開のアプリ数
    published_apps_count = user_apps.filter(status='public').count()
    unpublished_apps_count = user_apps.filter(status='draft').count()
    
    # 人気アプリを取得（アナリティクスが存在するもののみ）
    from django.db.models import F, Value, IntegerField
    from apps_gallery.models import AppAnalytics
    
    # 関連のanalytics.view_countが存在するアプリを取得
    popular_apps = []
    for app in user_apps.order_by('-created_at'):
        try:
            # analyticsオブジェクトを取得するか、存在しなければ作成
            analytics, created = AppAnalytics.objects.get_or_create(app=app)
            # ビューで使うデータにview_countを設定
            app.view_count = analytics.view_count
        except Exception as e:
            # エラーが発生した場合は0を設定
            app.view_count = 0
        
        popular_apps.append(app)
    
    context = {
        'user': request.user,
        'total_apps': user_apps.count(),
        'published_apps_count': published_apps_count,
        'unpublished_apps_count': unpublished_apps_count,
        'latest_apps': popular_apps,  # テンプレートで使用される変数名は維持
    }
    
    return render(request, 'dashboard/analytics.html', context)
