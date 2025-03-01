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
    GENRES,
    EDITORS,
    VERSION_CONTROL,
    CI_CD,
    VIRTUALIZATION_TOOLS,
    TEAM_SIZES,
    COMMUNICATION_TOOLS,
    INFRASTRUCTURE,
    API_TOOLS,
    MONITORING_TOOLS,
    PC_TYPES,
    DEVICE_TYPES,
    CPU_TYPES,
    MEMORY_SIZES,
    STORAGE_TYPES,
    MONITOR_COUNTS,
    INTERNET_TYPES,
    MAKER_EXAMPLES,
    FRONTEND_LANGUAGES,
    FRONTEND_FRAMEWORKS,
    CSS_FRAMEWORKS,
    DATABASE_TYPES,
    DATABASE_HOSTING,
    ORMS,
    AUTHENTICATION_METHODS,
    SECURITY_MEASURES,
    DEVELOPMENT_PERIODS,
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
from .constants.architecture import (
    ARCHITECTURE_PATTERNS,
    DESIGN_PATTERNS,
    ARCHITECTURE_HINTS,
    TESTING_TOOLS,
    CODE_QUALITY_TOOLS
)
from .constants.backend_constants import BACKEND_STACK, BACKEND_PACKAGE_HINTS

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
    """共通のコンテキストを取得する"""
    context = {
        'app': app,
        'readonly': readonly,
        'is_edit': is_edit,
        'hide_navbar': True,
        'editors': dict(EDITORS),
        'version_control': dict(VERSION_CONTROL),
        'ci_cd': dict(CI_CD),
        'virtualization_tools': dict(VIRTUALIZATION_TOOLS),
        'team_sizes': dict(TEAM_SIZES),
        'communication_tools': dict(COMMUNICATION_TOOLS),
        'infrastructure': dict(INFRASTRUCTURE),
        'api_tools': dict(API_TOOLS),
        'monitoring_tools': dict(MONITORING_TOOLS),
        'pc_types': PC_TYPES,
        'device_types': DEVICE_TYPES,
        'cpu_types': CPU_TYPES,
        'memory_sizes': MEMORY_SIZES,
        'storage_types': STORAGE_TYPES,
        'monitor_counts': MONITOR_COUNTS,
        'internet_types': INTERNET_TYPES,
        'maker_examples': MAKER_EXAMPLES,
        'architecture_patterns': ARCHITECTURE_PATTERNS,
        'design_patterns': DESIGN_PATTERNS,
        'architecture_hints': ARCHITECTURE_HINTS,
        'security_measures': SECURITY_MEASURES,
        'testing_tools': TESTING_TOOLS,
        'code_quality_tools': CODE_QUALITY_TOOLS,
        'BACKEND_STACK': BACKEND_STACK,
        'BACKEND_PACKAGE_HINTS': BACKEND_PACKAGE_HINTS,
        'APP_TYPES': dict(APP_TYPES),
        'GENRES': dict(GENRES),
        'APP_STATUS': dict(APP_STATUS),
        'PUBLISH_STATUS': dict(PUBLISH_STATUS),
        'FRONTEND_LANGUAGES': dict(FRONTEND_LANGUAGES),
        'FRONTEND_FRAMEWORKS': dict(FRONTEND_FRAMEWORKS),
        'CSS_FRAMEWORKS': dict(CSS_FRAMEWORKS),
        'DATABASE_TYPES': dict(DATABASE_TYPES),
        'DATABASE_HOSTING': dict(DATABASE_HOSTING),
        'ORMS': dict(ORMS),
        'AUTHENTICATION_METHODS': dict(AUTHENTICATION_METHODS),
        'DEVELOPMENT_PERIODS': dict(DEVELOPMENT_PERIODS),
        'SECURITY_MEASURES': dict(SECURITY_MEASURES),
    }
    
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
                app.save()
                
                if request.headers.get('X-Requested-With') == 'XMLHttpRequest':
                    return JsonResponse({
                        'success': True,
                        'app_id': app.id,
                        'message': '保存しました'
                    })
                return redirect('apps_gallery:detail', pk=app.pk)
            else:
                if request.headers.get('X-Requested-With') == 'XMLHttpRequest':
                    return JsonResponse({
                        'success': False,
                        'error': '入力内容を確認してください',
                        'errors': form.errors
                    }, status=400)
                messages.error(request, '入力内容を確認してください')
        except Exception as e:
            if request.headers.get('X-Requested-With') == 'XMLHttpRequest':
                return JsonResponse({
                    'success': False,
                    'error': str(e)
                }, status=500)
            messages.error(request, f'エラーが発生しました：{str(e)}')
    
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
            'thumbnail_index': thumbnail_index,
            'security': {
                'authentication_methods': [],
                'measures': []
            }
        }
    })
    
    # デバッグ情報を追加
    print("\n=== Debug Info ===")
    print(f"Template Size: {len(str(context))}")
    
    # 各テンプレートのサイズを確認
    for template in ['create_edit.html', 'tabs/01_screenshots_tab.html', 
                    'tabs/02_basic_tab.html', 'tabs/03_appeal_tab.html']:
        content = render_to_string(f'apps_gallery/{template}', context)
        print(f"\nTemplate {template}:")
        print(f"Size: {len(content)} bytes")
        if len(content) > 100000:  # 100KB以上のテンプレートを詳しく調査
            print("Large content found in:", template)
            print("First 200 chars:", content[:200])
    
    print("\n=== End Debug Info ===\n")
    
    return render(request, 'apps_gallery/create_edit.html', context)

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
        
        return render(request, 'apps_gallery/create_edit.html', context)
        
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
    
    return render(request, 'apps_gallery/create_edit.html', context)

@login_required
def app_detail(request, pk):
    """アプリの詳細表示ビュー（全タブ統合版）"""
    app = get_object_or_404(AppGallery, pk=pk)
    
    # セキュリティ情報が未設定の場合は初期化
    if not hasattr(app, 'security') or app.security is None:
        app.security = {
            'authentication_methods': [],
            'measures': []
        }
        app.save()
    
    print("\n=== Security Debug Info ===")
    print(f"Security Data: {app.security}")
    print(f"Authentication Methods: {app.security.get('authentication_methods', [])}")
    print(f"Security Measures: {app.security.get('measures', [])}")
    
    context = get_common_context(app=app, readonly=True)
    return render(request, 'apps_gallery/app_view_detail.html', context)

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

@require_http_methods(["POST"])
def save_technical(request, app_id):
    try:
        data = json.loads(request.body)
        print("\n=== Debug Info ===")
        print(f"User: {request.user}, App ID: {app_id}")
        print(f"Content-Type: {request.content_type}")
        print("Received data:", data)

        app = get_object_or_404(AppGallery, id=app_id)
        
        # アーキテクチャ情報の保存
        if 'architecture' in data:
            app.architecture = {
                'patterns': data['architecture'].get('patterns', []),
                'design_patterns': data['architecture'].get('design_patterns', []),
                'description': data['architecture'].get('description', '')  # 文字列として保存
            }
        
        # ハードウェア情報の保存
        if 'hardware_specs' in data:
            app.hardware_specs = data['hardware_specs']
            
        # 開発環境情報の保存
        if 'development_environment' in data:
            app.development_environment = data['development_environment']
            
        # バックエンド情報の保存
        if 'backend' in data:
            app.backend = data['backend']
            
        app.save()
        
        print("\n=== After Save ===")
        print(f"Saved hardware: {app.hardware_specs}")
        print(f"Saved dev env: {app.development_environment}")
        print(f"Saved backend: {app.backend}")
        print(f"Saved architecture: {app.architecture}")
        
        return JsonResponse({
            'success': True,
            'message': '保存しました',
            'data': {
                'architecture': app.architecture,
                'hardware_specs': app.hardware_specs,
                'development_environment': app.development_environment,
                'backend': app.backend
            }
        })
    except Exception as e:
        print("Error saving technical data:", str(e))
        return JsonResponse({
            'success': False,
            'message': str(e)
        }, status=400)

@login_required
@require_http_methods(["POST"])
def auto_save_app(request, app_id=None):
    """自動保存用APIエンドポイント"""
    logger.info(f"自動保存処理開始: app_id={app_id}")
    
    try:
        # 自動保存フラグをチェック
        is_auto_save = request.headers.get('X-Auto-Save') == 'true'
        
        if app_id and app_id != 'undefined':
            # 既存アプリの更新
            app = get_object_or_404(AppGallery, id=app_id, author=request.user)
            logger.info(f"既存アプリを自動保存: {app.title}")
        else:
            # 新規アプリの作成
            app = AppGallery(
                author=request.user,
                status='draft',
                title=request.POST.get('title', '無題')
            )
            logger.info("新規アプリを自動保存")
        
        # フォームのデータを取得
        form = AppForm(request.POST, request.FILES, instance=app)
        
        if is_auto_save:
            # 自動保存時は最低限のバリデーションのみ
            app.save()
            
            # フォームが有効なら関連データも保存
            if form.is_valid():
                form.save_m2m()  # Many-to-Manyの保存
            
            logger.info(f"自動保存完了: app_id={app.id}")
            
            return JsonResponse({
                'success': True,
                'app_id': app.id,
                'message': '自動保存しました'
            })
        else:
            # 通常の保存時は完全バリデーション
            if form.is_valid():
                app = form.save()
                return JsonResponse({
                    'success': True,
                    'app_id': app.id,
                    'message': '保存しました'
                })
            else:
                return JsonResponse({
                    'success': False,
                    'errors': form.errors
                })
                
    except Exception as e:
        logger.error(f"自動保存エラー: {str(e)}")
        import traceback
        logger.error(traceback.format_exc())
        return JsonResponse({
            'success': False, 
            'error': str(e)
        }, status=500)

@login_required
@require_http_methods(["POST"])
def delete_empty_app(request, app_id):
    """空のアプリを削除するAPIエンドポイント"""
    try:
        app = get_object_or_404(AppGallery, id=app_id, author=request.user)
        
        # 空かどうかをチェック（タイトルが'無題'のままで他の重要フィールドが空）
        is_empty = (
            app.title in ['無題', ''] and 
            not app.description and 
            not app.overview and
            len(app.screenshots or []) == 0
        )
        
        if is_empty:
            logger.info(f"空のアプリを削除: app_id={app_id}")
            app.delete()
            return JsonResponse({
                'success': True,
                'message': '空のアプリを削除しました'
            })
        
        return JsonResponse({
            'success': False,
            'message': 'アプリは空ではないため削除しませんでした'
        })
        
    except Exception as e:
        logger.error(f"空アプリ削除エラー: {str(e)}")
        return JsonResponse({
            'success': False,
            'error': str(e)
        }, status=500)