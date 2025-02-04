from django.shortcuts import render, redirect, get_object_or_404
from django.contrib import messages
from django.contrib.auth.decorators import login_required
from django.core.exceptions import PermissionDenied
from .models import AppGallery
from .constants import *  # 全ての定数をインポート

# Create your views here.

def create_view(request):
    # contextを作成して、hide_navbarをTrueに設定
    context = {
        'hide_navbar': True
    }
    return handle_app_form(request, context=context)  # contextを渡す

@login_required
def edit_app(request, pk):
    """アプリの編集ビュー"""
    app = get_object_or_404(AppGallery, pk=pk)
    
    # 作者でない場合は403エラー
    if app.author != request.user:
        raise PermissionDenied("このアプリを編集する権限がありません。")
    
    context = {
        'hide_navbar': True  # navbarを非表示に
    }
    return handle_app_form(request, app, context)

@login_required
def handle_app_form(request, app=None, context=None):
    """アプリの作成・編集を処理する共通関数"""
    # contextがNoneの場合は空のdictを作成
    if context is None:
        context = {}
    
    # 編集時は作者チェック
    if app and app.author != request.user:
        raise PermissionDenied("このアプリを編集する権限がありません。")

    if request.method == 'POST':
        # 新規作成か更新かを判断
        if app is None:
            app = AppGallery()
            app.author = request.user  # 新規作成時に作者を設定
        
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
        
        # キャッチコピーの処理を修正
        catchphrases = request.POST.getlist('catchphrases')
        # 空でないものだけを保存し、最大3つまでに制限
        app.catchphrases = [phrase for phrase in catchphrases if phrase.strip()][:3]
        
        app.save()
        
        # メッセージを設定
        action = '更新' if app.pk else '作成'
        messages.success(request, f'アプリを{action}しました！')
        # アンカー付きのURLにリダイレクト
        return redirect(f'apps_gallery:detail?tab=appeal#{request.POST.get("active_tab", "")}')

    # アクティブなタブ情報をcontextに追加
    context.update({
        'app': app,
        'APP_TYPES': dict(APP_TYPES),
        'APP_STATUS': dict(APP_STATUS),
        'PUBLISH_STATUS': dict(PUBLISH_STATUS),
        'GENRES': dict(GENRES),
        'is_edit': app is not None,
        'readonly': False,
        'active_tab': request.GET.get('tab', '')  # URLからタブ情報を取得
    })
    
    return render(request, 'apps_gallery/create_edit_detail.html', context)

def app_list(request):
    # とりあえず仮の実装
    return render(request, 'apps_gallery/list.html')

def app_detail(request, pk):
    """アプリの詳細ビュー"""
    app = get_object_or_404(AppGallery, pk=pk)
    context = {
        'app': app,
        'readonly': True,
        'APP_TYPES': dict(APP_TYPES),
        'APP_STATUS': dict(APP_STATUS),
        'PUBLISH_STATUS': dict(PUBLISH_STATUS),
        'GENRES': dict(GENRES),
        'hide_navbar': True  # navbarを非表示に
    }
    return render(request, 'apps_gallery/create_edit_detail.html', context)

def delete_app(request, pk):
    # とりあえず仮の実装
    return render(request, 'apps_gallery/delete.html')
