from django.shortcuts import render, redirect, get_object_or_404
from django.contrib import messages
from django.contrib.auth.decorators import login_required
from django.core.exceptions import PermissionDenied
from .models import AppGallery, AppAnalytics
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
from django.db import models

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
        
        # エラー発生時も同じ画面を表示
        try:
            context = get_common_context(app=app, is_edit=True)
            context['form'] = AppForm(instance=app)
            return render(request, 'apps_gallery/create_edit.html', context)
        except:
            # どうしてもダメな場合はホームに戻る
            logger.error("Failed to render edit page after error")
            return redirect('/')

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
    
    # アナリティクスを更新
    try:
        analytics, created = AppAnalytics.objects.get_or_create(app=app)
        analytics.increment_view(request)
    except Exception as e:
        # エラーが発生してもページを表示できるようにする
        logger.error(f"アナリティクス更新中にエラーが発生しました: {e}")
    
    context['show_action_buttons'] = True
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

def handle_development_environment_fields(request_data, app):
    """開発環境フィールドの処理"""
    logger.info("開発環境フィールドの処理を開始")
    
    # 開発環境JSONデータの初期化
    development_environment = {}
    
    # エディタ
    editors = request_data.getlist('development_environment_editors')
    if editors:
        development_environment['editors'] = editors
        logger.info(f"エディタ: {editors}")
    
    # バージョン管理
    version_control = request_data.getlist('development_environment_version_control')
    if version_control:
        development_environment['version_control'] = version_control
        logger.info(f"バージョン管理: {version_control}")
    
    # 仮想化ツール
    virtualization = request_data.getlist('development_environment_virtualization')
    if virtualization:
        development_environment['virtualization'] = virtualization
        logger.info(f"仮想化ツール: {virtualization}")
    
    # チームサイズ
    team_size = request_data.get('development_environment_team_size')
    if team_size:
        development_environment['team_size'] = team_size
        logger.info(f"チームサイズ: {team_size}")
    
    # コミュニケーションツール
    communication_tools = request_data.getlist('development_environment_communication_tools')
    if communication_tools:
        development_environment['communication_tools'] = communication_tools
        logger.info(f"コミュニケーションツール: {communication_tools}")
    
    # CI/CD
    ci_cd = request_data.getlist('development_environment_ci_cd')
    if ci_cd:
        development_environment['ci_cd'] = ci_cd
        logger.info(f"CI/CD: {ci_cd}")
    
    # インフラストラクチャ
    infrastructure = request_data.getlist('development_environment_infrastructure')
    if infrastructure:
        development_environment['infrastructure'] = infrastructure
        logger.info(f"インフラストラクチャ: {infrastructure}")
    
    # APIツール
    api_tools = request_data.getlist('development_environment_api_tools')
    if api_tools:
        development_environment['api_tools'] = api_tools
        logger.info(f"APIツール: {api_tools}")
    
    # モニタリングツール
    monitoring_tools = request_data.getlist('development_environment_monitoring_tools')
    if monitoring_tools:
        development_environment['monitoring_tools'] = monitoring_tools
        logger.info(f"モニタリングツール: {monitoring_tools}")
    
    # 収集したデータが空でなければ更新
    if development_environment:
        logger.info(f"収集した開発環境データ: {development_environment}")
        app.development_environment = development_environment
    else:
        logger.warning("開発環境データは空です")
    
    return app

@login_required
@require_http_methods(["POST"])
def auto_save_app(request, app_id=None):
    """アプリの自動保存処理"""
    try:
        # リクエスト内容をログに出力
        logger.info(f"自動保存処理開始: app_id={app_id}")
        
        # POSTメソッドでない場合はエラー
        if request.method != 'POST':
            return JsonResponse({'error': 'POSTメソッドが必要です'}, status=400)
        
        # POSTデータをログに出力
        logger.info("======== POST データ ========")
        for key, value in request.POST.items():
            # 長すぎるデータや重要な情報は省略
            if key in ['csrfmiddlewaretoken']:
                logger.info(f"POST['{key}'] = {value}")
            else:
                logger.info(f"POST['{key}'] = {value}")
        logger.info("============================")
        
        # 自動保存かどうか
        is_auto_save = request.POST.get('is_auto_save') == 'true'
        
        # app_idが指定されている場合は既存アプリを編集
        if app_id:
            try:
                app = AppGallery.objects.get(pk=app_id)
                logger.info(f"既存アプリを自動保存: {app.title}")
                
                # 既存データをログに出力
                logger.info("======== 既存データ ========")
                existing_development_environment = app.development_environment
                existing_architecture = app.architecture if hasattr(app, 'architecture') else None
                existing_backend = app.backend if hasattr(app, 'backend') else None
                existing_frontend = app.frontend if hasattr(app, 'frontend') else None
                existing_database = app.database if hasattr(app, 'database') else None
                existing_security = app.security if hasattr(app, 'security') else None
                existing_development_story = app.development_story if hasattr(app, 'development_story') else None
                
                logger.info(f"既存 development_environment: {existing_development_environment}")
                logger.info(f"既存 architecture: {existing_architecture}")
                logger.info(f"既存 backend: {existing_backend}")
                logger.info(f"既存 frontend: {existing_frontend}")
                logger.info(f"既存 database: {existing_database}")
                logger.info(f"既存 security: {existing_security}")
                logger.info(f"既存 development_story: {existing_development_story}")
                logger.info("===========================")
            except AppGallery.DoesNotExist:
                # 存在しない場合は新規作成
                app = AppGallery()
                logger.info("新規アプリを自動保存")
                
                # 新規の場合は既存データなし
                existing_development_environment = {}
                existing_architecture = {}
                existing_backend = {}
                existing_frontend = {}
                existing_database = {}
                existing_security = {}
                existing_development_story = {}
        else:
            # app_idがない場合は新規作成
            app = AppGallery()
            logger.info("新規アプリを自動保存")
            
            # 新規の場合は既存データなし
            existing_development_environment = {}
            existing_architecture = {}
            existing_backend = {}
            existing_frontend = {}
            existing_database = {}
            existing_security = {}
            existing_development_story = {}
        
        # フォームのデータを取得
        form = AppForm(request.POST, request.FILES, instance=app)
        
        if is_auto_save:
            # 自動保存時は最低限のバリデーションのみ
            # フォームが有効なら関連データも保存
            if form.is_valid():
                # formを使って直接保存する（save_m2mは内部で処理される）
                app = form.save()
            else:
                # フォームエラーをログ出力（デバッグ用）
                logger.error(f"フォームエラー: {form.errors}")
                # フォームが無効な場合でも最低限のデータは保存する
                app.save()
            
            # 開発環境フィールドを処理
            app = handle_development_environment_fields(request.POST, app)
            
            # 各タブのフィールドを処理
            app = handle_architecture_fields(request.POST, app)
            app = handle_backend_fields(request.POST, app)
            app = handle_frontend_fields(request.POST, app)
            app = handle_hosting_fields(request.POST, app)
            app = handle_database_fields(request.POST, app)
            app = handle_security_fields(request.POST, app)
            app = handle_hardware_fields(request.POST, app)
            app = handle_development_story_fields(request.POST, app)
            
            # 既存のJSONフィールド値をマージ（上書きではなく、結合する）
            # アーキテクチャデータのマージ
            if existing_architecture and app.architecture:
                # 既存データと新データをマージ
                merged_architecture = existing_architecture.copy()
                merged_architecture.update(app.architecture)
                app.architecture = merged_architecture
            elif existing_architecture:
                app.architecture = existing_architecture
                
            # バックエンドデータのマージ
            if existing_backend and app.backend:
                # 既存データと新データをマージ
                merged_backend = existing_backend.copy()
                merged_backend.update(app.backend)
                app.backend = merged_backend
            elif existing_backend:
                app.backend = existing_backend
                
            # フロントエンドデータのマージ
            if existing_frontend and app.frontend:
                # 既存データと新データをマージ
                merged_frontend = existing_frontend.copy()
                merged_frontend.update(app.frontend)
                app.frontend = merged_frontend
            elif existing_frontend:
                app.frontend = existing_frontend
                
            # データベースデータのマージ
            if existing_database and app.database:
                # 既存データと新データをマージ
                merged_database = existing_database.copy()
                merged_database.update(app.database)
                app.database = merged_database
            elif existing_database:
                app.database = existing_database
                
            # セキュリティデータのマージ
            if existing_security and app.security:
                # 既存データと新データをマージ
                merged_security = existing_security.copy()
                merged_security.update(app.security)
                app.security = merged_security
            elif existing_security:
                app.security = existing_security

            # 開発ストーリーデータのマージ
            if existing_development_story and app.development_story:
                # 既存データと新データをマージ
                merged_story = existing_development_story.copy()
                merged_story.update(app.development_story)
                app.development_story = merged_story
            elif existing_development_story:
                app.development_story = existing_development_story
            
            # フォームデータに対応するフィールドがある場合のみ更新
            data = request.POST
            
            # architecture フィールドの処理
            if 'architecture' in data:
                try:
                    logger.info(f"architecture データを処理中: {data['architecture'][:100]}...")
                    app.architecture = json.loads(data['architecture']) if isinstance(data['architecture'], str) else data['architecture']
                except Exception as e:
                    logger.error(f"architecture の処理でエラー: {str(e)}")
            
            # backend フィールドの処理
            if 'backend' in data:
                try:
                    logger.info(f"backend データを処理中: {data['backend'][:100]}...")
                    app.backend = json.loads(data['backend']) if isinstance(data['backend'], str) else data['backend']
                except Exception as e:
                    logger.error(f"backend の処理でエラー: {str(e)}")
            
            # frontend フィールドの処理
            if 'frontend' in data:
                try:
                    logger.info(f"frontend データを処理中: {data['frontend'][:100]}...")
                    app.frontend = json.loads(data['frontend']) if isinstance(data['frontend'], str) else data['frontend']
                except Exception as e:
                    logger.error(f"frontend の処理でエラー: {str(e)}")
            
            # database フィールドの処理
            if 'database' in data:
                try:
                    logger.info(f"database データを処理中: {data['database'][:100]}...")
                    app.database = json.loads(data['database']) if isinstance(data['database'], str) else data['database']
                except Exception as e:
                    logger.error(f"database の処理でエラー: {str(e)}")
            
            # security フィールドの処理
            if 'security' in data:
                try:
                    logger.info(f"security データを処理中: {data['security'][:100]}...")
                    app.security = json.loads(data['security']) if isinstance(data['security'], str) else data['security']
                except Exception as e:
                    logger.error(f"security の処理でエラー: {str(e)}")
                    
            # development_story フィールドの処理
            if 'development_story' in data:
                try:
                    logger.info(f"development_story データを処理中: {data['development_story'][:100]}...")
                    app.development_story = json.loads(data['development_story']) if isinstance(data['development_story'], str) else data['development_story']
                except Exception as e:
                    logger.error(f"development_story の処理でエラー: {str(e)}")
                    
            # 変更を保存
            app.save()
            
            # デバッグ用にJSONフィールドの状態をログ出力
            logger.info("======== 保存後の状態 ========")
            logger.info(f"保存後 development_environment: {app.development_environment}")
            logger.info(f"保存後 architecture: {app.architecture if hasattr(app, 'architecture') else None}")
            logger.info(f"保存後 backend: {app.backend if hasattr(app, 'backend') else None}")
            logger.info(f"保存後 frontend: {app.frontend if hasattr(app, 'frontend') else None}")
            logger.info(f"保存後 database: {app.database if hasattr(app, 'database') else None}")
            logger.info(f"保存後 security: {app.security if hasattr(app, 'security') else None}")
            logger.info(f"保存後 development_story: {app.development_story if hasattr(app, 'development_story') else None}")
            logger.info("============================")
            
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
                
                # 開発環境フィールドを処理
                app = handle_development_environment_fields(request.POST, app)
                
                # 各タブのフィールドを処理
                app = handle_architecture_fields(request.POST, app)
                app = handle_backend_fields(request.POST, app)
                app = handle_frontend_fields(request.POST, app)
                app = handle_hosting_fields(request.POST, app)
                app = handle_database_fields(request.POST, app)
                app = handle_security_fields(request.POST, app)
                app = handle_hardware_fields(request.POST, app)
                app = handle_development_story_fields(request.POST, app)
                
                # 既存のJSONフィールド値をマージ（上書きではなく、結合する）
                # アーキテクチャデータのマージ
                if existing_architecture and app.architecture:
                    # 既存データと新データをマージ
                    merged_architecture = existing_architecture.copy()
                    merged_architecture.update(app.architecture)
                    app.architecture = merged_architecture
                elif existing_architecture:
                    app.architecture = existing_architecture
                    
                # バックエンドデータのマージ
                if existing_backend and app.backend:
                    # 既存データと新データをマージ
                    merged_backend = existing_backend.copy()
                    merged_backend.update(app.backend)
                    app.backend = merged_backend
                elif existing_backend:
                    app.backend = existing_backend
                    
                # フロントエンドデータのマージ
                if existing_frontend and app.frontend:
                    # 既存データと新データをマージ
                    merged_frontend = existing_frontend.copy()
                    merged_frontend.update(app.frontend)
                    app.frontend = merged_frontend
                elif existing_frontend:
                    app.frontend = existing_frontend
                    
                # データベースデータのマージ
                if existing_database and app.database:
                    # 既存データと新データをマージ
                    merged_database = existing_database.copy()
                    merged_database.update(app.database)
                    app.database = merged_database
                elif existing_database:
                    app.database = existing_database
                    
                # セキュリティデータのマージ
                if existing_security and app.security:
                    # 既存データと新データをマージ
                    merged_security = existing_security.copy()
                    merged_security.update(app.security)
                    app.security = merged_security
                elif existing_security:
                    app.security = existing_security
                
                # 開発ストーリーデータのマージ
                if existing_development_story and app.development_story:
                    # 既存データと新データをマージ
                    merged_story = existing_development_story.copy()
                    merged_story.update(app.development_story)
                    app.development_story = merged_story
                elif existing_development_story:
                    app.development_story = existing_development_story
                
                # フォームデータに対応するフィールドがある場合のみ更新
                data = request.POST
                
                # architecture フィールドの処理
                if 'architecture' in data:
                    try:
                        logger.info(f"architecture データを処理中: {data['architecture'][:100]}...")
                        app.architecture = json.loads(data['architecture']) if isinstance(data['architecture'], str) else data['architecture']
                    except Exception as e:
                        logger.error(f"architecture の処理でエラー: {str(e)}")
                
                # backend フィールドの処理
                if 'backend' in data:
                    try:
                        logger.info(f"backend データを処理中: {data['backend'][:100]}...")
                        app.backend = json.loads(data['backend']) if isinstance(data['backend'], str) else data['backend']
                    except Exception as e:
                        logger.error(f"backend の処理でエラー: {str(e)}")
                
                # frontend フィールドの処理
                if 'frontend' in data:
                    try:
                        logger.info(f"frontend データを処理中: {data['frontend'][:100]}...")
                        app.frontend = json.loads(data['frontend']) if isinstance(data['frontend'], str) else data['frontend']
                    except Exception as e:
                        logger.error(f"frontend の処理でエラー: {str(e)}")
                
                # database フィールドの処理
                if 'database' in data:
                    try:
                        logger.info(f"database データを処理中: {data['database'][:100]}...")
                        app.database = json.loads(data['database']) if isinstance(data['database'], str) else data['database']
                    except Exception as e:
                        logger.error(f"database の処理でエラー: {str(e)}")
                
                # security フィールドの処理
                if 'security' in data:
                    try:
                        logger.info(f"security データを処理中: {data['security'][:100]}...")
                        app.security = json.loads(data['security']) if isinstance(data['security'], str) else data['security']
                    except Exception as e:
                        logger.error(f"security の処理でエラー: {str(e)}")
                    
                # development_story フィールドの処理
                if 'development_story' in data:
                    try:
                        logger.info(f"development_story データを処理中: {data['development_story'][:100]}...")
                        app.development_story = json.loads(data['development_story']) if isinstance(data['development_story'], str) else data['development_story']
                    except Exception as e:
                        logger.error(f"development_story の処理でエラー: {str(e)}")
                    
                # 変更を保存
                app.save()
                
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

# アナリティクス関連の新しいビュー
@login_required
def app_analytics(request, pk):
    """アプリのアナリティクスデータを表示するビュー"""
    app = get_object_or_404(AppGallery, pk=pk)
    
    # 所有者チェック - 自分のアプリのみ閲覧可能
    if app.author != request.user:
        raise PermissionDenied("このアプリのアナリティクスを閲覧する権限がありません。")
    
    # アナリティクスがなければ作成
    analytics, created = AppAnalytics.objects.get_or_create(app=app)
    
    # 過去30日間のデータを準備
    from datetime import datetime, timedelta
    import json
    
    today = datetime.now()
    date_labels = []
    view_data = []
    
    for i in range(30, -1, -1):
        date = (today - timedelta(days=i)).strftime('%Y-%m-%d')
        date_labels.append(date)
        view_data.append(analytics.daily_views.get(date, 0))
    
    # デバイスタイプの分布
    device_labels = list(analytics.device_types.keys())
    device_data = list(analytics.device_types.values())
    
    # リファラーの上位10件を取得
    referrers = sorted(
        analytics.referrers.items(),
        key=lambda x: x[1],
        reverse=True
    )[:10]
    
    context = {
        'app': app,
        'analytics': analytics,
        'date_labels': json.dumps(date_labels),
        'view_data': json.dumps(view_data),
        'device_labels': json.dumps(device_labels),
        'device_data': json.dumps(device_data),
        'top_referrers': referrers,
    }
    
    return render(request, 'apps_gallery/analytics.html', context)

def handle_architecture_fields(request_data, app):
    """アーキテクチャフィールドの処理"""
    logger.info("アーキテクチャフィールドの処理を開始")
    
    # アーキテクチャJSONデータの初期化
    architecture = {}
    
    # アーキテクチャパターン
    patterns = request_data.getlist('architecture_patterns')
    if patterns:
        architecture['patterns'] = patterns
        logger.info(f"アーキテクチャパターン: {patterns}")
    
    # デザインパターン
    design_patterns = request_data.getlist('architecture_design_patterns')
    if design_patterns:
        architecture['design_patterns'] = design_patterns
        logger.info(f"デザインパターン: {design_patterns}")
    
    # 説明
    description = request_data.get('architecture_description')
    if description:
        architecture['description'] = description
        logger.info(f"説明: {description[:50]}...")  # 最初の50文字だけ表示
    
    # 収集したデータが空でなければ更新
    if architecture:
        logger.info(f"収集したアーキテクチャデータ: {architecture}")
        app.architecture = architecture
    else:
        logger.warning("アーキテクチャデータは空です")
    
    return app

def handle_backend_fields(request_data, app):
    """バックエンドフィールドの処理"""
    logger.info("バックエンドフィールドの処理を開始")
    
    # バックエンドJSONデータの初期化
    backend = {}
    
    # メイン言語
    main_language = request_data.get('backend_main_language')
    if main_language:
        backend['main_language'] = main_language
        logger.info(f"メイン言語: {main_language}")
    
    # フレームワーク
    framework = request_data.get('backend_framework')
    if framework:
        backend['framework'] = framework
        logger.info(f"フレームワーク: {framework}")
    
    # パッケージ
    packages = request_data.getlist('backend_packages')
    if packages:
        backend['packages'] = packages
        logger.info(f"パッケージ: {packages}")
    
    # 収集したデータが空でなければ更新
    if backend:
        logger.info(f"収集したバックエンドデータ: {backend}")
        app.backend = backend
    else:
        logger.warning("バックエンドデータは空です")
    
    return app

def handle_frontend_fields(request_data, app):
    """フロントエンドフィールドの処理"""
    logger.info("フロントエンドフィールドの処理を開始")
    
    # フロントエンドJSONデータの初期化
    frontend = {}
    
    # 言語
    languages = request_data.getlist('frontend_languages')
    if languages:
        frontend['languages'] = languages
        logger.info(f"言語: {languages}")
    
    # フレームワーク
    frameworks = request_data.getlist('frontend_frameworks')
    if frameworks:
        frontend['frameworks'] = frameworks
        logger.info(f"フレームワーク: {frameworks}")
    
    # CSSフレームワーク
    css_frameworks = request_data.getlist('css_frameworks')
    if css_frameworks:
        frontend['css_frameworks'] = css_frameworks
        logger.info(f"CSSフレームワーク: {css_frameworks}")
    
    # 収集したデータが空でなければ更新
    if frontend:
        logger.info(f"収集したフロントエンドデータ: {frontend}")
        app.frontend = frontend
    else:
        logger.warning("フロントエンドデータは空です")
    
    return app

def handle_hosting_fields(request_data, app):
    """ホスティングフィールドの処理"""
    logger.info("ホスティングフィールドの処理を開始")
    
    # ホスティングJSONデータの初期化
    hosting_data = {}
    
    # Webアプリケーションホスティングサービス
    services = request_data.getlist('hosting_services')
    if services:
        hosting_data['services'] = services
        logger.info(f"ホスティングサービス: {services}")
    
    # デプロイ方法
    deployment_methods = request_data.getlist('deployment_methods')
    if deployment_methods:
        hosting_data['deployment_methods'] = deployment_methods
        logger.info(f"デプロイ方法: {deployment_methods}")
    
    # 備考
    notes = request_data.get('hosting_notes')
    if notes:
        hosting_data['notes'] = notes
        logger.info(f"ホスティング備考: {notes}")
    
    # 収集したデータが空でなければ更新
    if hosting_data:
        logger.info(f"収集したホスティングデータ: {hosting_data}")
        app.hosting = hosting_data
    else:
        logger.warning("ホスティングデータは空です")
    
    return app

def handle_database_fields(request_data, app):
    """データベースフィールドの処理"""
    logger.info("データベースフィールドの処理を開始")
    
    # データベースJSONデータの初期化
    database = {}
    
    # データベース種類
    types = request_data.getlist('database_types')
    if types:
        database['types'] = types
        logger.info(f"データベース種類: {types}")
    
    # ホスティングサービス
    hosting = request_data.getlist('database_hosting')
    if hosting:
        database['hosting'] = hosting
        logger.info(f"ホスティングサービス: {hosting}")
    
    # ORM
    orms = request_data.getlist('database_orms')
    if orms:
        database['orms'] = orms
        logger.info(f"ORM: {orms}")
    
    # 収集したデータが空でなければ更新
    if database:
        logger.info(f"収集したデータベースデータ: {database}")
        app.database = database
    else:
        logger.warning("データベースデータは空です")
    
    return app

def handle_security_fields(request_data, app):
    logger.info("Processing security fields...")
    
    security_data = {}
    
    # 認証方法の取得
    authentication_methods = request_data.getlist('authentication_methods', [])
    if authentication_methods:
        security_data['authentication_methods'] = authentication_methods
        logger.info(f"Authentication methods: {authentication_methods}")
    
    # セキュリティ対策の取得
    security_measures = request_data.getlist('security_measures', [])
    if security_measures:
        security_data['measures'] = security_measures
        logger.info(f"Security measures: {security_measures}")
    
    # データがあれば更新
    if security_data:
        app.security = security_data
        logger.info("Security data collected successfully")
    else:
        logger.warning("No security data found in request")
    
    return app

def handle_hardware_fields(request_data, app):
    """ハードウェア関連のフィールドを処理する"""
    logger.info("Processing hardware fields...")
    
    # 収集するデータ
    hardware_data = {}
    
    # ハードウェアメーカー
    maker = request_data.get('maker')
    if maker:
        hardware_data['maker'] = maker
        logger.info(f"Hardware maker: {maker}")
    
    # ハードウェアモデル
    model = request_data.get('model')
    if model:
        hardware_data['model'] = model
        logger.info(f"Hardware model: {model}")
    
    # 収集したデータがある場合はアプリに設定
    if hardware_data:
        app.hardware_specs = hardware_data
        logger.info("Hardware data collected successfully")
    else:
        logger.warning("No hardware data found in request")
    
    return app

def handle_development_story_fields(request_data, app):
    """開発ストーリー関連のフィールドを処理する"""
    logger.info("開発ストーリーフィールドの処理を開始")
    
    # 収集するデータ
    story_data = {}
    
    # 開発開始日
    start_date = request_data.get('development_start_date')
    if start_date:
        story_data['start_date'] = start_date
        logger.info(f"開発開始日: {start_date}")
    
    # 開発終了日
    end_date = request_data.get('development_end_date')
    if end_date:
        story_data['end_date'] = end_date
        logger.info(f"開発終了日: {end_date}")
    
    # 開発期間
    duration = request_data.get('development_duration')
    if duration:
        story_data['duration'] = duration
        logger.info(f"開発期間: {duration}")
    
    # 開発の動機
    motivation = request_data.get('development_motivation')
    if motivation:
        story_data['motivation'] = motivation
        logger.info(f"開発の動機: {motivation}")
    
    # 工夫したポイント
    innovations = request_data.get('development_innovations')
    if innovations:
        story_data['innovations'] = innovations
        logger.info(f"工夫したポイント: {innovations}")
    
    # 諦めた機能
    abandoned = request_data.get('development_abandoned')
    if abandoned:
        story_data['abandoned'] = abandoned
        logger.info(f"諦めた機能: {abandoned}")
    
    # 今後の予定
    future_plans = request_data.get('development_future_plans')
    if future_plans:
        story_data['future_plans'] = future_plans
        logger.info(f"今後の予定: {future_plans}")
    
    # 振り返り
    reflections = request_data.get('development_reflections')
    if reflections:
        story_data['reflections'] = reflections
        logger.info(f"振り返り: {reflections}")
    
    # 収集したデータがある場合はアプリに設定
    if story_data:
        app.development_story = story_data
        logger.info(f"収集した開発ストーリーデータ: {story_data}")
    else:
        logger.warning("開発ストーリーデータは空です")
    
    return app