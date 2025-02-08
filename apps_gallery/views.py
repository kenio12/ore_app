from django.shortcuts import render, redirect, get_object_or_404
from django.contrib import messages
from django.contrib.auth.decorators import login_required
from django.core.exceptions import PermissionDenied
from .models import AppGallery
from .forms import AppForm  # この行を追加！
from .constants import *  # 全ての定数をインポート
from django.http import JsonResponse
from django.views.decorators.http import require_http_methods
import json
import cloudinary
import cloudinary.uploader
from django.urls import reverse
import base64
import logging

# Create your views here.

@login_required  # ログインが必要
def create_view(request):
    """アプリの新規作成ビュー"""
    if request.method == 'POST':
        try:
            form = AppForm(request.POST)
            if form.is_valid():
                app = form.save(commit=False)
                app.author = request.user
                
                # セッションから画像情報を取得して保存
                temp_screenshots = request.session.get('temp_screenshots', [])
                if temp_screenshots:
                    app.screenshots = temp_screenshots
                    # セッションをクリア
                    del request.session['temp_screenshots']
                    request.session.modified = True
                
                app.save()
                
                return JsonResponse({
                    'success': True,
                    'redirect_url': reverse('apps_gallery:detail', kwargs={'pk': app.pk})
                })
            
            return JsonResponse({
                'success': False,
                'errors': form.errors
            }, status=400)
            
        except Exception as e:
            print(f"全体的なエラー: {e}")
            return JsonResponse({
                'success': False,
                'error': str(e)
            }, status=500)
    
    form = AppForm()
    
    context = {
        'hide_navbar': True,
        'readonly': False,
        'APP_TYPES': dict(APP_TYPES),
        'APP_STATUS': dict(APP_STATUS),
        'PUBLISH_STATUS': dict(PUBLISH_STATUS),
        'GENRES': dict(GENRES),
        'is_edit': False,
        'is_create': True,  # 新規作成フラグを追加
        'form': form,
        'app': {'screenshots': request.session.get('temp_screenshots', [])}  # 辞書として渡す
    }
    return render(request, 'apps_gallery/create_edit_detail.html', context)

@login_required
def edit_app(request, pk):
    app = get_object_or_404(AppGallery, pk=pk)
    
    if request.method == 'POST':
        try:
            form_data = request.POST
            app.title = form_data.get('title', app.title)
            app.app_types = form_data.getlist('types', app.app_types)
            app.genres = form_data.getlist('genres', app.genres)
            app.dev_status = form_data.get('dev_status', app.dev_status)
            app.status = form_data.get('status', app.status)
            app.app_url = form_data.get('app_url', app.app_url)
            app.github_url = form_data.get('github_url', app.github_url)
            app.overview = form_data.get('overview', app.overview)
            app.motivation = form_data.get('motivation', app.motivation)
            app.catchphrases = form_data.getlist('catchphrases', app.catchphrases)
            app.target_users = form_data.getlist('target_users', app.target_users)
            app.problems = form_data.getlist('problems', app.problems)
            app.final_appeal = form_data.getlist('final_appeal', app.final_appeal)
            app.save()
            
            # AJAXリクエストの場合
            if request.headers.get('X-Requested-With') == 'XMLHttpRequest':
                return JsonResponse({
                    'success': True,
                    'redirect_url': reverse('apps_gallery:detail', kwargs={'pk': pk})
                })
            
            # 通常のフォーム送信の場合
            return redirect('apps_gallery:detail', pk=pk)
            
        except Exception as e:
            if request.headers.get('X-Requested-With') == 'XMLHttpRequest':
                return JsonResponse({
                    'success': False,
                    'error': str(e)
                }, status=500)
            messages.error(request, f'保存中にエラーが発生しました: {str(e)}')
    
    context = {
        'app': app,
        'readonly': False
    }
    return render(request, 'apps_gallery/create_edit_detail.html', context)

@login_required
def handle_app_form(request, app=None, context=None):
    """アプリの作成・編集を処理する共通関数"""
    try:
        if context is None:
            context = {}
        
        if app and app.author != request.user:
            raise PermissionDenied("このアプリを編集する権限がありません。")

        if 'readonly' not in context:
            context['readonly'] = False

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
            else:
                if request.headers.get('X-Requested-With') == 'XMLHttpRequest':
                    return JsonResponse({
                        'success': False,
                        'errors': form.errors
                    }, status=400)
    except Exception as e:
        print(f"Error in handle_app_form: {str(e)}")
        if request.headers.get('X-Requested-With') == 'XMLHttpRequest':
            return JsonResponse({
                'success': False,
                'error': '保存に失敗しました'
            }, status=500)
        messages.error(request, '保存に失敗しました')

    context.update({
        'app': app,
        'APP_TYPES': dict(APP_TYPES),
        'APP_STATUS': dict(APP_STATUS),
        'PUBLISH_STATUS': dict(PUBLISH_STATUS),
        'GENRES': dict(GENRES),
        'is_edit': app is not None,
        'readonly': context.get('readonly', False),
        'active_tab': request.GET.get('tab', '')
    })
    
    return render(request, 'apps_gallery/create_edit_detail.html', context)

def app_list(request):
    # とりあえず仮の実装
    return render(request, 'apps_gallery/list.html')

def app_detail(request, pk):
    """アプリの詳細ビュー"""
    app = get_object_or_404(AppGallery, pk=pk)
    
    # デバッグ用の詳細なログ出力
    print("\n==== 画像表示デバッグ情報 ====")
    print(f"App ID: {app.id}")
    print(f"App Title: {app.title}")
    print("Screenshots Data Type:", type(app.screenshots))
    print("Screenshots Raw Data:", app.screenshots)
    
    if app.screenshots:
        print("\n個別のスクリーンショット情報:")
        for i, screenshot in enumerate(app.screenshots, 1):
            print(f"\nスクリーンショット {i}:")
            print(f"データ型: {type(screenshot)}")
            for key, value in screenshot.items():
                print(f"{key}: {value}")
    
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
    """スクリーンショットのアップロード処理"""
    try:
        if 'image' not in request.FILES:
            return JsonResponse({'error': '画像ファイルが必要です'}, status=400)

        image_file = request.FILES['image']
        
        # Cloudinaryにアップロード
        upload_result = cloudinary.uploader.upload(
            image_file,
            folder='app_screenshots'
        )

        screenshot_data = {
            'public_id': upload_result['public_id'],
            'url': upload_result['secure_url'],
            'description': ''
        }

        # セッションに保存（新規作成時）
        temp_screenshots = request.session.get('temp_screenshots', [])
        temp_screenshots.append(screenshot_data)
        request.session['temp_screenshots'] = temp_screenshots
        request.session.modified = True

        return JsonResponse({
            'status': 'success',
            'screenshot': screenshot_data,
            'message': '画像をアップロードしました'
        })

    except Exception as e:
        print(f"Error in upload_screenshot: {str(e)}")  # エラーログを出力
        return JsonResponse({
            'error': 'アップロード中にエラーが発生しました',
            'details': str(e)
        }, status=500)

@login_required
@require_http_methods(["POST"])
def delete_screenshot(request):
    """スクリーンショットの削除処理"""
    try:
        data = json.loads(request.body)
        app_id = data.get('app_id')
        index = int(data.get('index', 0))

        if app_id:
            # 既存のアプリの場合
            app = get_object_or_404(AppGallery, pk=app_id)
            if app.author != request.user:
                return JsonResponse({'error': '権限がありません'}, status=403)
            
            screenshots = app.screenshots or []
            if 0 <= index < len(screenshots):
                # Cloudinaryから画像を削除
                public_id = screenshots[index].get('public_id')
                if public_id:
                    cloudinary.uploader.destroy(public_id)
                
                # リストから該当の画像を削除
                screenshots.pop(index)
                app.screenshots = screenshots
                app.save()
        else:
            # 新規作成時（セッションから削除）
            screenshots = request.session.get('temp_screenshots', [])
            if 0 <= index < len(screenshots):
                # Cloudinaryから画像を削除
                public_id = screenshots[index].get('public_id')
                if public_id:
                    cloudinary.uploader.destroy(public_id)
                
                screenshots.pop(index)
                request.session['temp_screenshots'] = screenshots
                request.session.modified = True

        return JsonResponse({
            'status': 'success',
            'message': 'スクリーンショットを削除しました'
        })

    except Exception as e:
        print(f"Error in delete_screenshot: {str(e)}")
        return JsonResponse({
            'error': '削除中にエラーが発生しました',
            'details': str(e)
        }, status=500)

@login_required
@require_http_methods(["POST"])
def set_thumbnail(request):
    """サムネイル画像の設定処理"""
    try:
        data = json.loads(request.body)
        app_id = data.get('app_id')
        index = int(data.get('index', 0))

        if app_id:
            # 既存のアプリの場合
            app = get_object_or_404(AppGallery, pk=app_id)
            if app.author != request.user:
                return JsonResponse({'error': '権限がありません'}, status=403)
            
            screenshots = app.screenshots or []
            if 0 <= index < len(screenshots):
                # 選択された画像を先頭に移動
                selected = screenshots.pop(index)
                screenshots.insert(0, selected)
                app.screenshots = screenshots
                app.save()
        else:
            # 新規作成時（セッションの画像を並び替え）
            screenshots = request.session.get('temp_screenshots', [])
            if 0 <= index < len(screenshots):
                selected = screenshots.pop(index)
                screenshots.insert(0, selected)
                request.session['temp_screenshots'] = screenshots
                request.session.modified = True

        return JsonResponse({
            'status': 'success',
            'message': 'サムネイルを設定しました'
        })

    except Exception as e:
        print(f"Error in set_thumbnail: {str(e)}")
        return JsonResponse({
            'error': 'サムネイル設定中にエラーが発生しました',
            'details': str(e)
        }, status=500)

@login_required
def reset_screenshots(request, pk):
    """一時的に無効化"""
    return JsonResponse({
        'status': 'error',
        'message': 'この機能は現在メンテナンス中です'
    }, status=503)
