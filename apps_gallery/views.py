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
                app.save()
                
                # スクリーンショットの処理
                screenshots_data = []
                if request.session.get('temp_screenshots'):
                    print("セッション内のスクリーンショット:", request.session['temp_screenshots'])  # デバッグ出力
                    for screenshot in request.session['temp_screenshots']:
                        try:
                            image_data = screenshot.get('image')
                            if not image_data:
                                continue
                            
                            # Base64ヘッダーの処理
                            if 'data:image' in image_data:
                                # Base64ヘッダーを削除
                                image_data = image_data.split('base64,')[1]
                            
                            # Cloudinaryにアップロード
                            upload_result = cloudinary.uploader.upload(
                                f"data:image/jpeg;base64,{image_data}",
                                folder='app_screenshots'
                            )
                            
                            print("Cloudinaryアップロード結果:", upload_result)  # デバッグ出力
                            
                            screenshots_data.append({
                                'public_id': upload_result['public_id'],
                                'url': upload_result['secure_url'],
                                'description': screenshot.get('description', '')
                            })
                        except Exception as e:
                            print(f"画像処理エラー: {str(e)}")
                            continue
                    
                    if screenshots_data:
                        print("保存する画像データ:", screenshots_data)  # デバッグ出力
                        app.screenshots = screenshots_data
                        app.save()
                    
                    # セッションをクリア
                    del request.session['temp_screenshots']
                    request.session.modified = True
                
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
    
    # GET処理は変更なし
    form = AppForm()
    context = {
        'hide_navbar': True,
        'readonly': False,
        'APP_TYPES': dict(APP_TYPES),
        'APP_STATUS': dict(APP_STATUS),
        'PUBLISH_STATUS': dict(PUBLISH_STATUS),
        'GENRES': dict(GENRES),
        'is_edit': False,
        'form': form
    }
    return render(request, 'apps_gallery/create_edit_detail.html', context)

@login_required
def edit_app(request, pk):
    """アプリの編集ビュー"""
    app = get_object_or_404(AppGallery, pk=pk)
    
    if request.method == 'POST':
        try:
            # 既存のスクリーンショットを保持
            existing_screenshots = app.screenshots or []
            print("既存のスクリーンショット:", existing_screenshots)  # デバッグ出力
            
            # 基本情報の保存
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
            
            # スクリーンショットの処理
            if request.session.get('temp_screenshots'):
                print("セッション内のスクリーンショット:", request.session['temp_screenshots'])  # デバッグ出力
                for screenshot in request.session['temp_screenshots']:
                    try:
                        # Base64データの処理
                        image_data = screenshot.get('image')
                        if not image_data:
                            continue
                            
                        # Base64ヘッダーの処理
                        if 'data:image' in image_data:
                            # Base64ヘッダーを削除
                            image_data = image_data.split('base64,')[1]
                        
                        # Cloudinaryにアップロード
                        upload_result = cloudinary.uploader.upload(
                            f"data:image/jpeg;base64,{image_data}",  # Base64ヘッダーを追加
                            folder='app_screenshots'
                        )
                        
                        print("Cloudinaryアップロード結果:", upload_result)  # デバッグ出力
                        
                        # 新しい画像情報を追加
                        existing_screenshots.append({
                            'public_id': upload_result['public_id'],
                            'url': upload_result['secure_url'],
                            'description': screenshot.get('description', '')
                        })
                        
                    except Exception as e:
                        print(f"画像処理エラー: {str(e)}")
                        continue
                
                # 全ての画像を保存
                print("保存する画像データ:", existing_screenshots)  # デバッグ出力
                app.screenshots = existing_screenshots
                
                # セッションクリア
                del request.session['temp_screenshots']
                request.session.modified = True
            
            app.save()
            
            return JsonResponse({
                'success': True,
                'redirect_url': reverse('apps_gallery:detail', kwargs={'pk': pk})
            })
            
        except Exception as e:
            print(f"保存エラー: {str(e)}")
            return JsonResponse({
                'success': False,
                'error': str(e)
            }, status=500)
    
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
    try:
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
                        image_data = base64.b64decode(screenshot['image'])
                        
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
            else:
                # フォームが無効な場合のエラーハンドリング
                if request.headers.get('X-Requested-With') == 'XMLHttpRequest':
                    return JsonResponse({
                        'success': False,
                        'errors': form.errors
                    }, status=400)
    except Exception as e:
        # エラーログを出力
        print(f"Error in handle_app_form: {str(e)}")
        if request.headers.get('X-Requested-With') == 'XMLHttpRequest':
            return JsonResponse({
                'success': False,
                'error': '保存に失敗しました'
            }, status=500)
        messages.error(request, '保存に失敗しました')

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
    try:
        image = request.FILES.get('image')
        description = request.POST.get('description', '')
        
        if not image:
            return JsonResponse({'error': '画像が必要です'}, status=400)
            
        # セッションに保存
        temp_screenshots = request.session.get('temp_screenshots', [])
        
        # 画像データをBase64でエンコード
        image_data = base64.b64encode(image.read()).decode('utf-8')
        
        # 画像情報を保存
        screenshot_info = {
            'image': f'data:image/jpeg;base64,{image_data}',  # Base64ヘッダーを追加
            'description': description
        }
        
        temp_screenshots.append(screenshot_info)
        request.session['temp_screenshots'] = temp_screenshots
        request.session.modified = True

        return JsonResponse({
            'status': 'success',
            'message': '画像を一時保存しました',
            'description': description,
            'preview_data': f'data:image/jpeg;base64,{image_data}'  # プレビュー用にBase64ヘッダーを追加
        })

    except Exception as e:
        print(f"Screenshot upload error: {str(e)}")  # デバッグ用
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
