from django.shortcuts import render
from django.contrib import messages
from apps_gallery.models import AppGallery
from apps_gallery.constants import *  # __init__.pyから全ての定数をインポート

def home(request):
    # 完成品かつ公開状態のアプリのみを取得
    apps = AppGallery.objects.filter(
        status='public',
        dev_status='completed'  # 完成品のみ
    ).order_by('-created_at')
    
    # デバッグ出力を追加
    print("=== Debug Output ===")
    for app in apps:
        print(f"App ID: {app.pk}")
        print(f"Title: {app.title}")
        print(f"Screenshots: {app.screenshots}")
        print("---")
    
    context = {
        'apps': apps,
        'APP_TYPES': dict(APP_TYPES),
        'GENRES': dict(GENRES),
    }
    return render(request, 'home/home.html', context)
