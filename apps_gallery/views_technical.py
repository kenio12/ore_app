from django.shortcuts import render, redirect, get_object_or_404
from django.contrib.auth.decorators import login_required
from django.contrib import messages
from .models import AppGallery

@login_required
def technical_edit_view(request, pk):
    """技術情報の編集ビュー"""
    try:
        # アプリの存在確認と基本情報チェック
        app = AppGallery.objects.get(id=pk, author=request.user)
        
        # 基本情報が揃っているかチェック
        if not all([app.title, app.overview, app.screenshots]):
            messages.warning(request, '先に基本情報を入力してください！')
            return redirect('apps_gallery:edit', pk=pk)
        
        if request.method == 'POST':
            # POSTの処理はここに実装予定
            pass
        
        context = {
            'app': app,
            'hide_navbar': True,
            'readonly': False,
            'is_edit': True,
        }
        return render(request, 'apps_gallery/technical/edit_detail_technical.html', context)
        
    except AppGallery.DoesNotExist:
        messages.error(request, 'アプリが見つかりません。')
        return redirect('home:home') 