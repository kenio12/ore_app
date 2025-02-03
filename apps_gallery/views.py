from django.shortcuts import render, redirect, get_object_or_404
from django.contrib import messages
from .models import AppGallery
from .constants import *  # 全ての定数をインポート

# Create your views here.

def create_view(request):
    return handle_app_form(request)  # handle_app_formを使用

def edit_app(request, pk):
    app = get_object_or_404(AppGallery, pk=pk)
    return handle_app_form(request, app)  # handle_app_formを使用

def handle_app_form(request, app=None):
    """アプリの作成・編集を処理する共通関数"""
    if request.method == 'POST':
        # 新規作成か更新かを判断
        if app is None:
            app = AppGallery()
        
        # POSTデータを処理
        app.title = request.POST.get('title')
        app.app_types = request.POST.getlist('types')
        app.genres = request.POST.getlist('genres')
        app.other_genre = request.POST.get('other_genre', '')
        app.dev_status = request.POST.get('dev_status')  # 開発状況
        app.status = request.POST.get('status')  # 公開状態
        app.app_url = request.POST.get('app_url', '')
        app.github_url = request.POST.get('github_url', '')
        app.overview = request.POST.get('overview', '')
        app.motivation = request.POST.get('motivation', '')
        app.target_users = request.POST.get('target_users', '')
        app.problems = request.POST.get('problems', '')
        app.final_appeal = request.POST.get('final_appeal', '')
        
        app.save()
        
        # メッセージを設定
        action = '更新' if app.pk else '作成'
        messages.success(request, f'アプリを{action}しました！')
        return redirect('apps_gallery:detail', pk=app.pk)

    # GETの場合はフォームを表示
    context = {
        'app': app,  # 新規作成の場合はNone
        'APP_TYPES': dict(APP_TYPES),
        'APP_STATUS': dict(APP_STATUS),
        'PUBLISH_STATUS': dict(PUBLISH_STATUS),  # 追加
        'GENRES': dict(GENRES),
        'is_edit': app is not None,
    }
    return render(request, 'apps_gallery/create_edit_detail.html', context)

def app_list(request):
    # とりあえず仮の実装
    return render(request, 'apps_gallery/list.html')

def app_detail(request, pk):
    app = get_object_or_404(AppGallery, pk=pk)
    context = {
        'app': app,
        'readonly': True,
        'APP_TYPES': dict(APP_TYPES),
        'APP_STATUS': dict(APP_STATUS),
        'PUBLISH_STATUS': dict(PUBLISH_STATUS),  # 追加
        'GENRES': dict(GENRES),
    }
    return render(request, 'apps_gallery/create_edit_detail.html', context)

def delete_app(request, pk):
    # とりあえず仮の実装
    return render(request, 'apps_gallery/delete.html')
