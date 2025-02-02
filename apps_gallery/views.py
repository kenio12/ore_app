from django.shortcuts import render, redirect, get_object_or_404
from django.contrib import messages
from .models import AppGallery
from .constants import *
from .constants.app_info import APP_TYPES, APP_STATUS, GENRES  # 定数をインポート

# Create your views here.

def create_view(request):
    # 新規作成の場合はappはNone
    return handle_app_form(request)

def edit_app(request, pk):
    # 編集の場合は既存のappを取得
    app = get_object_or_404(AppGallery, pk=pk)
    return handle_app_form(request, app)

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
        app.status = request.POST.get('status')
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
        'app_types': APP_TYPES,
        'app_status': APP_STATUS,
        'genres': GENRES,
        'is_edit': app is not None,  # 編集モードかどうか
    }
    return render(request, 'apps_gallery/create.html', context)  # create.htmlを共通テンプレートとして使用

def app_list(request):
    # とりあえず仮の実装
    return render(request, 'apps_gallery/list.html')

def app_detail(request, pk):
    app = get_object_or_404(AppGallery, pk=pk)
    context = {
        'app': app,
        'readonly': True,
        'APP_TYPES': APP_TYPES,
        'APP_STATUS': APP_STATUS,
        'GENRES': GENRES,
    }
    return render(request, 'apps_gallery/detail.html', context)

def delete_app(request, pk):
    # とりあえず仮の実装
    return render(request, 'apps_gallery/delete.html')
