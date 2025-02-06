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
import base64

# Create your views here.

def create_view(request):
    """アプリの新規作成ビュー"""
    context = {
        'hide_navbar': True,
        'readonly': False,  # 明示的にFalseを設定
        'app': None,  # 新規作成時はNone
        'APP_TYPES': dict(APP_TYPES),
        'APP_STATUS': dict(APP_STATUS),
        'PUBLISH_STATUS': dict(PUBLISH_STATUS),
        'GENRES': dict(GENRES),
        'is_edit': False,  # 新規作成時はFalse
    }
    
    print("Debug - Create View Context:", {  # デバッグ用出力
        'readonly': context['readonly'],
        'app_exists': context['app'] is not None
    })
    
    if request.method == 'POST':
        # POSTリクエストの処理
        return handle_app_form(request, context=context)
    
    # GETリクエストの場合は新規作成フォームを表示
    return render(request, 'apps_gallery/create_edit_detail.html', context)

@login_required
def edit_app(request, pk):
    """アプリの編集ビュー"""
    app = get_object_or_404(AppGallery, pk=pk)
    
    # POSTリクエストの場合、フォームデータを処理
    if request.method == 'POST':
        # フォームデータを取得と保存処理
        app.title = request.POST.get('title', app.title)
        app.app_types = request.POST.getlist('types', app.app_types)
        app.genres = request.POST.getlist('genres', app.genres)
        app.dev_status = request.POST.get('dev_status', app.dev_status)
        app.status = request.POST.get('status', app.status)
        app.app_url = request.POST.get('app_url', app.app_url)
        app.github_url = request.POST.get('github_url', app.github_url)
        app.overview = request.POST.get('overview', app.overview)
        app.motivation = request.POST.get('motivation', app.motivation)
        app.catchphrases = request.POST.getlist('catchphrases')
        app.target_users = request.POST.get('target_users', app.target_users)
        app.problems = request.POST.get('problems', app.problems)
        app.final_appeal = request.POST.get('final_appeal', app.final_appeal)
        app.save()
        
        # AJAXリクエストの場合はJSONレスポンスを返す
        if request.headers.get('X-Requested-With') == 'XMLHttpRequest':
            return JsonResponse({
                'status': 'success',
                'redirect_url': reverse('apps_gallery:detail', kwargs={'pk': pk})
            })
        
        # 通常のリクエストの場合は詳細画面にリダイレクト
        return redirect('apps_gallery:detail', pk=pk)
    
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

    # 明示的にreadonlyを設定
    if 'readonly' not in context:
        context['readonly'] = False

    if request.method == 'POST':
        form = AppForm(request.POST, instance=app)
        if form.is_valid():
            app = form.save(commit=False)
            if not app.author:
                app.author = request.user
            app.save()
            
            # アプリ保存時の処理
            if 'temp_screenshots' in request.session:
                temp_screenshots = request.session['temp_screenshots']
                for screenshot in temp_screenshots:
                    # Base64データをデコード
                    image_data = base64.b64decode(screenshot['image_data'])
                    
                    # Cloudinaryにアップロード
                    upload_result = cloudinary.uploader.upload(
                        image_data,
                        folder='app_screenshots',
                        resource_type='image'
                    )
                    
                    # スクリーンショット情報を保存
                    screenshot_info = {
                        'public_id': upload_result['public_id'],
                        'url': upload_result['secure_url'],
                        'description': screenshot['description']  # 説明文も保存
                    }
                    
                    screenshots = app.screenshots or []
                    screenshots.append(screenshot_info)
                    app.screenshots = screenshots
                
                app.save()
                del request.session['temp_screenshots']

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
        'readonly': context.get('readonly', False),  # 既存のreadonlyを保持
        'active_tab': request.GET.get('tab', '')  # URLからタブ情報を取得
    })
    
    print("Debug - Context:", {  # デバッグ用出力
        'readonly': context['readonly'],
        'app_exists': app is not None,
        'screenshots_count': len(app.screenshots) if app and app.screenshots else 0
    })
    
    return render(request, 'apps_gallery/create_edit_detail.html', context)

def app_list(request):
    # とりあえず仮の実装
    return render(request, 'apps_gallery/list.html')

def app_detail(request, pk):
    """アプリの詳細ビュー"""
    app = get_object_or_404(AppGallery, pk=pk)
    
    # POSTリクエストの場合は編集モードに切り替え
    if request.method == 'POST':
        # AJAXリクエストの場合はJSONレスポンスを返す
        if request.headers.get('X-Requested-With') == 'XMLHttpRequest':
            return JsonResponse({
                'status': 'success',
                'redirect_url': reverse('apps_gallery:edit', kwargs={'pk': pk})
            })
        
        # 通常のリクエストの場合は編集画面にリダイレクト
        return redirect('apps_gallery:edit', pk=pk)
    
    context = {
        'app': app,
        'readonly': True,
        'APP_TYPES': dict(APP_TYPES),
        'APP_STATUS': dict(APP_STATUS),
        'PUBLISH_STATUS': dict(PUBLISH_STATUS),
        'GENRES': dict(GENRES),
        'hide_navbar': True
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
        description = request.POST.get('description', '')  # 説明文を取得
        
        if not image:
            return JsonResponse({'error': '画像が必要です'}, status=400)
            
        # セッションに保存
        temp_screenshots = request.session.get('temp_screenshots', [])
        
        # Base64でエンコード
        image_data = base64.b64encode(image.read()).decode('utf-8')
        
        # 画像情報と説明文を保存
        screenshot_info = {
            'image_data': image_data,
            'description': description  # 説明文も一緒に保存
        }
        
        temp_screenshots.append(screenshot_info)
        request.session['temp_screenshots'] = temp_screenshots
        request.session.modified = True

        return JsonResponse({
            'status': 'success',
            'message': '画像を一時保存しました',
            'description': description  # フロントエンドに説明文を返す
        })

    except Exception as e:
        print(f"Error in upload_screenshot: {str(e)}")
        return JsonResponse({
            'error': f'アップロード中にエラーが発生しました: {str(e)}'
        }, status=500)

@login_required
@require_http_methods(["DELETE"])
def delete_screenshot(request, screenshot_id):
    """スクリーンショット削除処理"""
    try:
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
        
        return JsonResponse({
            'status': 'success',
            'message': 'スクリーンショットが削除されました'
        })
        
    except Exception as e:
        print(f"Error in delete_screenshot: {str(e)}")  # デバッグ用
        return JsonResponse({'error': str(e)}, status=500)

@login_required
@require_http_methods(["POST"])
def set_thumbnail(request, screenshot_id):
    try:
        print(f"Setting thumbnail for screenshot: {screenshot_id}")  # デバッグログ
        
        app_id = request.GET.get('app_id')
        if not app_id:
            print("No app_id provided")  # デバッグログ
            return JsonResponse({'error': 'アプリIDが必要です'}, status=400)
            
        app = get_object_or_404(AppGallery, pk=app_id)
        print(f"Found app: {app.id}")  # デバッグログ

        # 権限チェック
        if app.author != request.user:
            return JsonResponse({'error': '権限がありません'}, status=403)

        # スクリーンショットの並び替え
        screenshots = app.screenshots or []
        print(f"Current screenshots: {screenshots}")  # デバッグログ
        
        # screenshot_idをそのまま使用（既にapp_screenshots/を含んでいる）
        target_screenshot = next((s for s in screenshots if s['public_id'] == screenshot_id), None)
        
        if target_screenshot:
            print(f"Found target screenshot: {target_screenshot}")  # デバッグログ
            # 選択された画像を先頭に移動
            screenshots.remove(target_screenshot)
            screenshots.insert(0, target_screenshot)
            app.screenshots = screenshots
            app.save()
            
            print("Thumbnail set successfully")  # デバッグログ
            return JsonResponse({
                'status': 'success',
                'message': 'サムネイル設定が完了しました'
            })
        else:
            print("Screenshot not found")  # デバッグログ
            return JsonResponse({
                'error': '指定された画像が見つかりません'
            }, status=404)

    except Exception as e:
        print(f"Error in set_thumbnail: {str(e)}")  # デバッグログ
        return JsonResponse({
            'error': f'サムネイル設定中にエラーが発生しました: {str(e)}'
        }, status=500)

@login_required
def reset_screenshots(request, pk):
    """スクリーンショット情報をリセット"""
    app = get_object_or_404(AppGallery, pk=pk)
    
    # 権限チェック
    if app.author != request.user:
        raise PermissionDenied("このアプリを編集する権限がありません。")
    
    # スクリーンショットをリセット
    app.screenshots = []
    app.save()
    
    return JsonResponse({'status': 'success', 'message': 'スクリーンショット情報をリセットしました'})
