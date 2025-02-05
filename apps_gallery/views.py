from django.shortcuts import render, redirect, get_object_or_404
from django.contrib import messages
from django.contrib.auth.decorators import login_required
from django.core.exceptions import PermissionDenied
from .models import AppGallery
from .constants import *  # 全ての定数をインポート
from django.http import JsonResponse
from django.views.decorators.http import require_http_methods
import json
import cloudinary
import cloudinary.uploader
from django.urls import reverse

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
    
    # デバッグ用：保存されているスクリーンショットの内容を確認
    print("Current screenshots in database:", app.screenshots)
    
    # 作者でない場合は403エラー
    if app.author != request.user:
        raise PermissionDenied("このアプリを編集する権限がありません。")
    
    context = {
        'hide_navbar': True,
        'app': app,
        'APP_TYPES': dict(APP_TYPES),
        'APP_STATUS': dict(APP_STATUS),
        'PUBLISH_STATUS': dict(PUBLISH_STATUS),
        'GENRES': dict(GENRES),
        'is_edit': True,
        'readonly': False
    }
    return render(request, 'apps_gallery/create_edit_detail.html', context)

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
        form = AppForm(request.POST, instance=app)
        if form.is_valid():
            app = form.save(commit=False)
            if not app.author:
                app.author = request.user
            app.save()
            
            if request.headers.get('X-Requested-With') == 'XMLHttpRequest':
                return JsonResponse({
                    'success': True,
                    'redirect_url': reverse('apps_gallery:detail', kwargs={'pk': app.pk})
                })
            return redirect('apps_gallery:detail', pk=app.pk)

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

@login_required
@require_http_methods(["POST"])
def upload_screenshot(request):
    try:
        image = request.FILES.get('image')
        app_id = request.POST.get('app_id')
        description = request.POST.get('description', '')

        print(f"Debug - app_id: {app_id}")  # デバッグ用

        if not image:
            return JsonResponse({'error': '画像が選択されていません'}, status=400)

        if not app_id:
            # 新規作成時は一時的な処理を行う
            upload_result = cloudinary.uploader.upload(
                image,
                folder='app_screenshots_temp',  # 一時フォルダに保存
                transformation=[
                    {'width': 1000, 'height': 1000, 'crop': 'limit'},
                    {'quality': 'auto:eco'},
                    {'fetch_format': 'auto'}
                ]
            )
            return JsonResponse({
                'message': 'アップロード成功（一時保存）',
                'screenshot': {
                    'url': upload_result['secure_url'],
                    'public_id': upload_result['public_id'],
                    'description': description
                }
            })

        # 既存のアプリの場合
        app = get_object_or_404(AppGallery, pk=app_id)
        
        # 権限チェック
        if app.author != request.user:
            return JsonResponse({'error': '権限がありません'}, status=403)

        # 画像の枚数チェック
        if len(app.screenshots or []) >= 3:
            return JsonResponse({'error': '画像は最大3枚までです'}, status=400)

        # Cloudinaryにアップロード
        upload_result = cloudinary.uploader.upload(
            image,
            folder='app_screenshots',
            transformation=[
                {'width': 1000, 'height': 1000, 'crop': 'limit'},
                {'quality': 'auto:eco'},
                {'fetch_format': 'auto'}
            ]
        )

        # スクリーンショット情報を作成
        screenshot_info = {
            'url': upload_result['secure_url'],
            'public_id': upload_result['public_id'],
            'description': description
        }

        # 既存のスクリーンショットリストに追加
        screenshots = app.screenshots or []
        screenshots.append(screenshot_info)
        app.screenshots = screenshots
        app.save()

        return JsonResponse({
            'message': 'アップロード成功',
            'screenshot': screenshot_info
        })

    except Exception as e:
        print(f"Error in upload_screenshot: {str(e)}")  # デバッグ用
        return JsonResponse({'error': str(e)}, status=500)

@login_required
@require_http_methods(["DELETE"])
def delete_screenshot(request, screenshot_id):
    cleanup_mode = request.GET.get('cleanup', 'false').lower() == 'true'
    
    try:
        if cleanup_mode:
            # クリーンアップモードの場合、Cloudinaryの画像のみを削除
            try:
                cloudinary.uploader.destroy(screenshot_id)
                return JsonResponse({'status': 'success', 'message': 'Temporary screenshot cleaned up'})
            except Exception as e:
                return JsonResponse({'error': f'Cloudinaryからの削除に失敗: {str(e)}'}, status=500)
        else:
            # 通常の削除処理
            app_id = request.GET.get('app_id')
            if not app_id:
                return JsonResponse({'error': 'app_idが必要です'}, status=400)
            
            app = get_object_or_404(AppGallery, pk=app_id)
            
            # 権限チェック
            if app.author != request.user:
                return JsonResponse({'error': '権限がありません'}, status=403)
            
            # スクリーンショットの削除
            screenshots = app.screenshots or []
            updated_screenshots = [s for s in screenshots if s['public_id'] != screenshot_id]
            
            if len(screenshots) == len(updated_screenshots):
                return JsonResponse({'error': '指定された画像が見つかりません'}, status=404)
            
            # Cloudinaryから画像を削除
            try:
                cloudinary.uploader.destroy(screenshot_id)
            except Exception as e:
                print(f"Error deleting from Cloudinary: {e}")
            
            # アプリの screenshots を更新
            app.screenshots = updated_screenshots
            app.save()
            
            return JsonResponse({'status': 'success'})
            
    except Exception as e:
        return JsonResponse({'error': str(e)}, status=500)

@login_required
@require_http_methods(["POST"])
def set_thumbnail(request, screenshot_id):
    try:
        app_id = request.GET.get('app_id')
        app = get_object_or_404(AppGallery, pk=app_id)

        # 権限チェック
        if app.author != request.user:
            return JsonResponse({'error': '権限がありません'}, status=403)

        # スクリーンショットの並び替え
        screenshots = app.screenshots or []
        
        # screenshot_idをそのまま使用（既にapp_screenshots/を含んでいる）
        target_screenshot = next((s for s in screenshots if s['public_id'] == screenshot_id), None)
        
        if target_screenshot:
            # 選択された画像を先頭に移動
            screenshots.remove(target_screenshot)
            screenshots.insert(0, target_screenshot)
            app.screenshots = screenshots
            app.save()
            
            return JsonResponse({'message': 'サムネイル設定成功'})
        else:
            return JsonResponse({'error': '指定された画像が見つかりません'}, status=404)

    except Exception as e:
        print(f"Error in set_thumbnail: {str(e)}")  # デバッグ用
        return JsonResponse({'error': str(e)}, status=500)
