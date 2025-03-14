from django.shortcuts import render
from django.contrib import messages
from django.urls import reverse
from apps_gallery.models import AppGallery
from apps_gallery.constants import *  # __init__.pyから全ての定数をインポート

def home(request):
    # 公開状態のアプリのみを取得（完成品条件を削除）
    apps = AppGallery.objects.filter(
        status='public'
        # dev_status='completed' の条件を削除
    ).order_by('-created_at')
    
    # アプリの情報を拡張
    apps_data = []
    for app in apps:
        # JSONFieldからデータを取得
        thumbnail_url = app.thumbnail.get('url') if app.thumbnail else None
        screenshots_urls = [s.get('url') for s in app.screenshots] if app.screenshots else []

        app_data = {
            'id': app.pk,
            'title': app.title,
            'overview': app.overview,
            'thumbnail': thumbnail_url,
            'screenshots': screenshots_urls,
            'app_types': [APP_TYPES[t] for t in app.app_types],
            'genres': [ジャンル表示[g] if g in ジャンル表示 else GENRES[g] for g in app.genres],
            'detail_url': reverse('apps_gallery:detail', args=[app.pk]),
            'edit_url': reverse('apps_gallery:edit', args=[app.pk]),
            'is_author': request.user == app.author,
            'author': str(app.author)  # 文字列に変換
        }
        apps_data.append(app_data)
    
    # デバッグ出力を追加
    print("=== Debug Output ===")
    for app in apps:
        print(f"App ID: {app.pk}")
        print(f"Title: {app.title}")
        print(f"Backend: {app.backend}")
        print(f"Thumbnail: {app.thumbnail}")
        print(f"Screenshots: {app.screenshots}")
        print("---")
    
    context = {
        'apps': apps,
        'apps_data': apps_data,
        'APP_TYPES': dict(APP_TYPES),
        'GENRES': dict(GENRES),
        'BACKEND_STACK': BACKEND_STACK,
    }
    return render(request, 'home/home.html', context)
