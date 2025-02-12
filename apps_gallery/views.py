from django.shortcuts import render, redirect, get_object_or_404
from django.contrib import messages
from django.contrib.auth.decorators import login_required
from django.core.exceptions import PermissionDenied
from .models import AppGallery
from .forms import AppForm
from .constants import (
    APP_TYPES,
    APP_STATUS,
    PUBLISH_STATUS,
    GENRES
)
from django.http import JsonResponse
from django.views.decorators.http import require_http_methods
import json
import cloudinary
import cloudinary.uploader
from django.urls import reverse
import base64
import logging
from django.views.decorators.cache import cache_page
from django.utils.decorators import method_decorator
from django.views.generic import ListView
from django.templatetags.static import static
from django.conf import settings
from django.template.loader import render_to_string
import re
from unittest.mock import patch
import os
from bs4 import BeautifulSoup
import sys
from django.core.signals import request_started, request_finished
from django.dispatch import receiver
import time

print("\n============= START DEBUG =============")  # ここに配置！

# Create your views here.

logger = logging.getLogger('apps_gallery')  # apps_gallery用のロガーを取得

# ロガーの設定を追加
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s [%(levelname)s] %(message)s',
    handlers=[logging.StreamHandler()]
)

# シグナルハンドラーを設定
@receiver(request_started)
def on_request_started(sender, **kwargs):
    print("Request started", file=sys.stderr)

@receiver(request_finished)
def on_request_finished(sender, **kwargs):
    print("Request finished", file=sys.stderr)

def get_common_context(app=None, readonly=False, is_edit=False):
    context = {
        'readonly': readonly,
        'APP_TYPES': dict(APP_TYPES),
        'APP_STATUS': dict(APP_STATUS),
        'PUBLISH_STATUS': dict(PUBLISH_STATUS),
        'GENRES': dict(GENRES),
        'is_edit': is_edit,
        'hide_navbar': True
    }
    
    # appをそのまま渡す（拡張性を保つ）
    if app:
        context['app'] = app
    
    return context

@login_required  # ログインが必要
def create_view(request):
    """アプリの新規作成ビュー"""
    print("\n==== Create View Debug Info ====")
    print(f"Session screenshots: {request.session.get('temp_screenshots', [])}")
    print(f"Session thumbnail_index: {request.session.get('temp_thumbnail_index')}")
    
    if request.method == 'POST':
        try:
            form = AppForm(request.POST)
            if form.is_valid():
                app = form.save(commit=False)
                app.author = request.user
                
                # セッションから画像情報を取得
                temp_screenshots = request.session.get('temp_screenshots', [])
                temp_thumbnail = request.session.get('temp_thumbnail')
                
                if temp_screenshots:
                    # 新しいスクリーンショットリストを作成
                    new_screenshots = []
                    
                    for screenshot in temp_screenshots:
                        # 一時フォルダから本番フォルダへ画像を移動
                        try:
                            # 新しい public_id を生成（app_idを含むパスに）
                            old_public_id = screenshot['public_id']
                            new_public_id = old_public_id.replace('app_screenshots/temp', f'app_screenshots/app_{app.id}')
                            
                            # Cloudinaryで画像を移動（rename）
                            result = cloudinary.uploader.rename(old_public_id, new_public_id)
                            
                            # 新しい情報でスクリーンショットを更新
                            new_screenshot = {
                                'public_id': result['public_id'],
                                'url': result['secure_url'],
                                'description': screenshot.get('description', '')
                            }
                            new_screenshots.append(new_screenshot)
                            
                        except Exception as e:
                            logging.error(f"Failed to move screenshot: {e}")
                            # エラーが発生しても処理を継続
                            continue
                    
                    # 新しいスクリーンショットリストを保存
                    app.screenshots = new_screenshots
                    
                    # セッションをクリア
                    del request.session['temp_screenshots']
                    request.session.modified = True
                
                # サムネイルの処理
                if temp_thumbnail:
                    old_public_id = temp_thumbnail['public_id']
                    new_public_id = old_public_id.replace('app_screenshots/temp', f'app_screenshots/app_{app.id}')
                    
                    try:
                        result = cloudinary.uploader.rename(old_public_id, new_public_id)
                        app.thumbnail = {
                            'public_id': result['public_id'],
                            'url': result['secure_url']
                        }
                    except Exception as e:
                        logging.error(f"Failed to move thumbnail: {e}")
                
                app.save()
                
                # セッションをクリア
                if 'temp_screenshots' in request.session:
                    del request.session['temp_screenshots']
                if 'temp_thumbnail' in request.session:
                    del request.session['temp_thumbnail']
                request.session.modified = True
                
                return JsonResponse({
                    'success': True,
                    'redirect_url': reverse('apps_gallery:detail', kwargs={'pk': app.pk})
                })
            
            # フォームのエラーをより詳細に記録
            print("Form validation errors:", form.errors)
            return JsonResponse({
                'success': False,
                'errors': form.errors,
                'error_details': {field: errors[0] for field, errors in form.errors.items()}
            }, status=400)
            
        except Exception as e:
            print(f"全体的なエラー: {e}")
            return JsonResponse({
                'success': False,
                'error': str(e),
                'error_trace': str(e.__traceback__)
            }, status=500)
    
    # セッションの状態を確認
    screenshots = request.session.get('temp_screenshots', [])
    thumbnail_index = request.session.get('temp_thumbnail_index', 0)
    
    context = get_common_context()
    context.update({
        'is_new': True,
        'is_create': True,
        'form': AppForm(),
        'screenshots': screenshots,
        'thumbnail_index': thumbnail_index,
        'app': {
            'screenshots': screenshots,
            'thumbnail_index': thumbnail_index
        }
    })
    
    # デバッグ情報を追加
    print("\n=== Debug Info ===")
    print(f"Template Size: {len(str(context))}")
    
    # 各テンプレートのサイズを確認
    for template in ['create_edit_detail.html', 'tabs/01_screenshots_tab.html', 
                    'tabs/02_basic_tab.html', 'tabs/03_appeal_tab.html']:
        content = render_to_string(f'apps_gallery/{template}', context)
        print(f"\nTemplate {template}:")
        print(f"Size: {len(content)} bytes")
        if len(content) > 100000:  # 100KB以上のテンプレートを詳しく調査
            print("Large content found in:", template)
            print("First 200 chars:", content[:200])
    
    print("\n=== End Debug Info ===\n")
    
    return render(request, 'apps_gallery/create_edit_detail.html', context)

@login_required
def edit_app(request, pk):
    """アプリの編集ビュー"""
    logger.info(f"Request method: {request.method}")
    
    try:
        # select_relatedを削除し、シンプルに取得
        app = get_object_or_404(AppGallery, pk=pk)
        
        # 権限チェック
        if app.author != request.user:
            logger.warning(f"Unauthorized access attempt by user {request.user} for app {pk}")
            raise PermissionDenied
        
        logger.info(f"App found: {app.title}")
        logger.info(f"Screenshots: {app.screenshots}")
        
        if request.method == 'POST':
            form = AppForm(request.POST, instance=app)
            if form.is_valid():
                app = form.save()
                messages.success(request, '保存しました')
                return JsonResponse({'success': True})
            else:
                return JsonResponse({'success': False, 'errors': form.errors})
        
        context = get_common_context(app=app, is_edit=True)
        context['form'] = AppForm(instance=app)
        
        return render(request, 'apps_gallery/create_edit_detail.html', context)
        
    except Exception as e:
        logger.error(f"Error in edit_app: {str(e)}")
        if request.headers.get('X-Requested-With') == 'XMLHttpRequest':
            return JsonResponse({
                'success': False,
                'error': str(e)
            }, status=500)
        messages.error(request, 'エラーが発生しました')
        return redirect('apps_gallery:list')

@login_required
def handle_app_form(request, app=None, context=None):
    if context is None:
        context = {}
    
    # デバッグ用：POSTデータの内容を確認
    if request.method == 'POST':
        print("受信したPOSTデータ:", request.POST)
        
        try:
            if app and app.author != request.user:
                raise PermissionDenied("このアプリを編集する権限がありません。")

            form = AppForm(request.POST, instance=app)
            if form.is_valid():
                app = form.save(commit=False)
                if not app.author:
                    app.author = request.user
                app.save()

                if request.headers.get('X-Requested-With') == 'XMLHttpRequest':
                    return JsonResponse({
                        'success': True,
                        'message': '保存しました',
                        'redirect_url': reverse('apps_gallery:detail', kwargs={'pk': app.pk})
                    })
                return redirect('apps_gallery:detail', pk=app.pk)
            else:
                # フォームのエラー内容をログに出力
                print("フォームバリデーションエラー:", form.errors)
                
                # エラーメッセージを整形
                error_messages = []
                for field, errors in form.errors.items():
                    field_name = {
                        'title': 'タイトル',
                        'overview': '概要',
                        'motivation': '開発動機',
                        'dev_status': '開発状況',
                        'status': '公開状態',
                    }.get(field, field)
                    
                    for error in errors:
                        error_messages.append(f"{field_name}：{error}")
                
                error_text = '以下の項目を確認してください：\n' + '\n'.join(error_messages)
                print("送信するエラーメッセージ:", error_text)

                if request.headers.get('X-Requested-With') == 'XMLHttpRequest':
                    return JsonResponse({
                        'success': False,
                        'error': error_text
                    }, status=400)
                
                messages.error(request, error_text)
                
        except Exception as e:
            print(f"予期せぬエラー: {str(e)}")
            import traceback
            print(f"エラーの詳細:\n{traceback.format_exc()}")
            
            error_message = f'エラーが発生しました：{str(e)}'
            if request.headers.get('X-Requested-With') == 'XMLHttpRequest':
                return JsonResponse({
                    'success': False,
                    'error': error_message
                }, status=500)
            messages.error(request, error_message)

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

def app_detail(request, pk):
    app = get_object_or_404(AppGallery, pk=pk)
    
    print("\n=== Catchphrases Data ===")
    print(f"Catchphrase 1: {app.catchphrase_1}")
    print(f"Catchphrase 2: {app.catchphrase_2}")
    print(f"Catchphrase 3: {app.catchphrase_3}")
    
    print("\n=== Debug Info ===")
    print(f"App ID: {app.id}")
    print(f"Title: {app.title}")
    
    template_path = 'apps_gallery/create_edit_detail.html'
    context = get_common_context(app=app, readonly=True)
    return render(request, template_path, context)

@login_required
def delete_app(request, pk):
    """アプリの削除処理"""
    app = get_object_or_404(AppGallery, pk=pk)
    
    # 権限チェック
    if app.author != request.user:
        raise PermissionDenied("このアプリを削除する権限がありません。")
    
    if request.method == 'POST':
        try:
            # Cloudinaryの画像を削除
            if app.screenshots:
                for screenshot in app.screenshots:
                    if 'public_id' in screenshot:
                        cloudinary.uploader.destroy(screenshot['public_id'])
            
            # サムネイル画像の削除
            if app.thumbnail and 'public_id' in app.thumbnail:
                cloudinary.uploader.destroy(app.thumbnail['public_id'])
            
            # アプリのタイトルを保存（メッセージ用）
            app_title = app.title
            
            # アプリを削除
            app.delete()
            
            messages.success(request, f'「{app_title}」を削除しました。')
            return redirect('dashboard:apps')  # ダッシュボードのアプリ管理画面にリダイレクト
            
        except Exception as e:
            messages.error(request, f'削除中にエラーが発生しました: {str(e)}')
            return redirect('apps_gallery:detail', pk=pk)
    
    context = get_common_context(app=app, readonly=True)
    return render(request, 'dashboard/delete.html', context)

@login_required
@require_http_methods(["POST"])
def upload_screenshot(request):
    """スクリーンショットのアップロードのみを処理"""
    try:
        print("\n==== スクリーンショットアップロード処理開始 ====")
        
        if 'image' not in request.FILES:
            return JsonResponse({'error': '画像ファイルが必要です'}, status=400)

        image = request.FILES['image']
        app_id = request.POST.get('app_id')
        
        # 新規作成時の処理
        if not app_id or app_id == 'undefined':
            app = AppGallery.objects.create(
                author=request.user,
                status='draft',
                title='無題'  # 仮のタイトル
            )
            app_id = app.id
        else:
            app = get_object_or_404(AppGallery, pk=app_id)
            if app.author != request.user:
                return JsonResponse({'error': '権限がありません'}, status=403)

        # Cloudinaryにアップロード
        upload_result = cloudinary.uploader.upload(
            image,
            folder=f'app_screenshots/app_{app_id}'
        )

        screenshot_data = {
            'public_id': upload_result['public_id'],
            'url': upload_result['secure_url'],
            'description': ''
        }

        # スクリーンショットの保存
        screenshots = app.screenshots if app.screenshots else []
        screenshots.append(screenshot_data)
        app.screenshots = screenshots

        # サムネイルが未設定の場合、最初のスクリーンショットをサムネイルに設定
        if not app.thumbnail:
            app.thumbnail = screenshot_data

        app.save()

        return JsonResponse({
            'status': 'success',
            'screenshot': screenshot_data,
            'message': 'スクリーンショットをアップロードしました',
            'app_id': app.id,
            'redirect_url': reverse('apps_gallery:edit', kwargs={'pk': app.id})
        })

    except Exception as e:
        print(f"アップロードエラー: {str(e)}")
        return JsonResponse({
            'error': str(e),
            'details': 'アップロード中にエラーが発生しました'
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
                # 削除対象の画像情報を取得
                target_screenshot = screenshots[index]
                
                # Cloudinaryから画像を削除
                public_id = target_screenshot.get('public_id')
                if public_id:
                    cloudinary.uploader.destroy(public_id)
                
                # サムネイルかどうかをチェック
                was_thumbnail = (app.thumbnail and app.thumbnail.get('url') == target_screenshot.get('url'))
                
                # リストから該当の画像を削除
                screenshots.pop(index)
                app.screenshots = screenshots
                
                # サムネイルだった場合、新しいサムネイルを設定
                if was_thumbnail and screenshots:
                    # 残っている最初の画像をサムネイルに設定
                    app.thumbnail = {
                        'public_id': screenshots[0].get('public_id'),
                        'url': screenshots[0].get('url')
                    }
                elif not screenshots:
                    # 画像が全て削除された場合
                    app.thumbnail = None
                
                app.save()
                print(f"削除後のスクリーンショット数: {len(app.screenshots)}")
                print(f"新しいサムネイル: {app.thumbnail}")
        else:
            # 新規作成時（セッションから削除）
            screenshots = request.session.get('temp_screenshots', [])
            if 0 <= index < len(screenshots):
                # 削除対象の画像情報を取得
                target_screenshot = screenshots[index]
                
                # Cloudinaryから画像を削除
                public_id = target_screenshot.get('public_id')
                if public_id:
                    cloudinary.uploader.destroy(public_id)
                
                # セッションのサムネイル情報を取得
                temp_thumbnail = request.session.get('temp_thumbnail')
                was_thumbnail = (temp_thumbnail and temp_thumbnail.get('url') == target_screenshot.get('url'))
                
                screenshots.pop(index)
                request.session['temp_screenshots'] = screenshots
                
                # サムネイルだった場合、新しいサムネイルを設定
                if was_thumbnail and screenshots:
                    request.session['temp_thumbnail'] = {
                        'public_id': screenshots[0].get('public_id'),
                        'url': screenshots[0].get('url')
                    }
                elif not screenshots:
                    if 'temp_thumbnail' in request.session:
                        del request.session['temp_thumbnail']
                
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
def set_thumbnail(request):
    if request.method == 'POST':
        try:
            data = json.loads(request.body)
            app_id = data.get('app_id')
            index = int(data.get('index', 0))
            
            if app_id:
                app = AppGallery.objects.get(id=app_id, author=request.user)
                if app.screenshots:
                    # スクリーンショットの順序変更
                    screenshots = app.screenshots
                    selected_screenshot = screenshots.pop(index)
                    screenshots.insert(0, selected_screenshot)
                    app.screenshots = screenshots
                    
                    # thumbnailフィールドも更新
                    app.thumbnail = {
                        'public_id': selected_screenshot['public_id'],
                        'url': selected_screenshot['url']
                    }
                    
                    app.save()
                
            return JsonResponse({'success': True})
            
        except Exception as e:
            return JsonResponse({
                'error': str(e),
                'details': 'サムネイル設定中にエラーが発生しました'
            }, status=400)
            
    return JsonResponse({'error': '不正なリクエストです'}, status=400)

@login_required
def reset_screenshots(request, pk):
    """一時的に無効化"""
    return JsonResponse({
        'status': 'error',
        'message': 'この機能は現在メンテナンス中です'
    }, status=503)

@method_decorator(cache_page(60 * 15), name='dispatch')  # 15分キャッシュ
class HomeView(ListView):
    template_name = 'home/home.html'
    context_object_name = 'apps'
    
    def get_queryset(self):
        return AppGallery.objects.prefetch_related(
            'screenshots',
            'app_types',
            'genres'
        ).select_related(
            'thumbnail'
        ).all()

    def get_context_data(self, **kwargs):
        context = super().get_context_data(**kwargs)
        context['meta'] = {
            'title': 'アプリ開発者のポートフォリオ',
            'description': '私が開発したアプリケーションをご紹介します。',
            'keywords': 'アプリ開発,ポートフォリオ,プログラミング',
            'og_image': static('home/images/ogp.png'),
        }
        return context

def detail_view(request, pk):
    app = get_object_or_404(AppGallery, pk=pk)
    
    print("\n=== Catchphrases Data ===")
    print(f"Catchphrase 1: {app.catchphrase_1}")
    print(f"Catchphrase 2: {app.catchphrase_2}")
    print(f"Catchphrase 3: {app.catchphrase_3}")
    
    # デバッグ情報を追加
    print("\n=== Debug Info ===")
    print(f"App ID: {app.id}")
    print(f"Title: {app.title}")
    
    context = get_common_context(app=app, readonly=True)
    return render(request, 'apps_gallery/create_edit_detail.html', context)