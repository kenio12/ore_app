from django.shortcuts import render, redirect, get_object_or_404
from django.contrib import messages
from django.contrib.auth.decorators import login_required
from django.core.exceptions import PermissionDenied
from .models import AppGallery
from .forms import AppForm
from .constants import *
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
from django.urls import reverse

# Create your views here.

@login_required  # ログインが必要
def create_view(request):
    """アプリの新規作成ビュー"""
    # デバッグ出力を追加
    print("\n==== セッション状態のデバッグ情報 ====")
    temp_screenshots = request.session.get('temp_screenshots', [])
    print(f"セッション内の画像数: {len(temp_screenshots)}")
    for i, shot in enumerate(temp_screenshots, 1):
        print(f"\n画像 {i}:")
        print(f"Public ID: {shot.get('public_id')}")
        print(f"URL: {shot.get('url')}")
    
    # 3枚より多い場合は、古い画像を削除
    if len(temp_screenshots) > 3:
        print("\n3枚を超える画像を削除します...")
        # 古い画像をCloudinaryから削除（4枚目以降）
        for old_shot in temp_screenshots[3:]:
            if 'public_id' in old_shot:
                try:
                    cloudinary.uploader.destroy(old_shot['public_id'])
                    print(f"削除: {old_shot['public_id']}")
                except Exception as e:
                    logging.error(f"Failed to delete image from Cloudinary: {e}")
                    print(f"削除失敗: {e}")
        
        # セッションには最新3枚だけを残す
        temp_screenshots = temp_screenshots[:3]
        request.session['temp_screenshots'] = temp_screenshots
        request.session.modified = True
        print(f"\n更新後のセッション内画像数: {len(temp_screenshots)}")

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
    """アプリの編集ビュー"""
    app = get_object_or_404(AppGallery, pk=pk)
    
    if app.author != request.user:
        raise PermissionDenied("このアプリを編集する権限がありません。")
    
    if request.method == 'POST':
        try:
            form_data = request.POST
            
            # フォームデータで更新
            app.title = form_data.get('title', app.title)
            app.app_types = form_data.getlist('types', app.app_types)
            app.genres = form_data.getlist('genres', app.genres)
            app.dev_status = form_data.get('dev_status', app.dev_status)
            app.status = form_data.get('status', app.status)
            app.app_url = form_data.get('app_url', app.app_url)
            app.github_url = form_data.get('github_url', app.github_url)
            app.overview = form_data.get('overview', '')
            app.motivation = form_data.get('motivation', '')
            app.catchphrases = form_data.getlist('catchphrases')  # これはリスト
            app.target_users = form_data.get('target_users')      # 普通のテキスト
            app.problems = form_data.get('problems')              # 普通のテキスト
            app.final_appeal = form_data.get('final_appeal')      # 普通のテキスト
            
            app.save()
            
            print(f"保存後のスクリーンショット数: {len(app.screenshots)}")
            print(f"保存後のサムネイル: {app.thumbnail}")
            
            if request.headers.get('X-Requested-With') == 'XMLHttpRequest':
                return JsonResponse({
                    'success': True,
                    'redirect_url': reverse('apps_gallery:detail', kwargs={'pk': pk})
                })
            
            return redirect('apps_gallery:detail', pk=pk)
            
        except Exception as e:
            print(f"編集保存エラー: {str(e)}")
            if request.headers.get('X-Requested-With') == 'XMLHttpRequest':
                return JsonResponse({
                    'success': False,
                    'error': str(e)
                }, status=500)
            messages.error(request, f'保存中にエラーが発生しました: {str(e)}')
    
    context = {
        'app': app,
        'readonly': False,
        'APP_TYPES': dict(APP_TYPES),
        'APP_STATUS': dict(APP_STATUS),
        'PUBLISH_STATUS': dict(PUBLISH_STATUS),
        'GENRES': dict(GENRES),
        'is_edit': True,
        'hide_navbar': True
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
    
    context = {
        'app': app,
        'hide_navbar': True
    }
    return render(request, 'dashboard/delete.html', context)

@login_required
@require_http_methods(["POST"])
def upload_screenshot(request):
    """スクリーンショットのアップロード処理"""
    try:
        print("\n==== アップロード処理開始 ====")
        print(f"リクエストメソッド: {request.method}")
        print(f"FILES: {request.FILES}")
        print(f"POST データ: {request.POST}")
        
        if 'image' not in request.FILES:
            return JsonResponse({'error': '画像ファイルが必要です'}, status=400)

        image = request.FILES['image']
        app_id = request.POST.get('app_id')
        
        print(f"アプリID: {app_id}")

        # Cloudinaryにアップロード
        upload_result = cloudinary.uploader.upload(
            image,
            folder='app_screenshots/temp' if not app_id else f'app_screenshots/app_{app_id}'
        )
        
        print(f"Cloudinaryアップロード結果: {upload_result}")

        screenshot_data = {
            'public_id': upload_result['public_id'],
            'url': upload_result['secure_url'],
            'description': ''
        }

        if app_id:
            # 既存アプリの場合
            app = get_object_or_404(AppGallery, pk=app_id)
            screenshots = app.screenshots if app.screenshots else []
            screenshots.append(screenshot_data)
            app.screenshots = screenshots  # リストを明示的に再代入
            app.save()
            print(f"アプリに画像を追加: {screenshot_data}")
            print(f"保存後のスクリーンショット数: {len(app.screenshots)}")
        else:
            # 新規作成の場合
            temp_screenshots = request.session.get('temp_screenshots', [])
            temp_screenshots.append(screenshot_data)
            request.session['temp_screenshots'] = temp_screenshots
            request.session.modified = True
            print(f"セッションに画像を追加: {screenshot_data}")
            print(f"セッション内の画像数: {len(request.session['temp_screenshots'])}")

        return JsonResponse({
            'status': 'success',
            'screenshot': screenshot_data,
            'message': '画像をアップロードしました'
        })

    except Exception as e:
        print(f"アップロードエラー: {str(e)}")
        print(f"エラーの詳細: {e.__class__.__name__}")
        import traceback
        print(f"スタックトレース: {traceback.format_exc()}")
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
@require_http_methods(["POST"])
def set_thumbnail(request):
    """サムネイル画像の設定処理"""
    try:
        data = json.loads(request.body)
        app_id = data.get('app_id')
        index = int(data.get('index', 0))

        if app_id:
            app = get_object_or_404(AppGallery, pk=app_id)
            if app.author != request.user:
                return JsonResponse({'error': '権限がありません'}, status=403)
            
            screenshots = app.screenshots or []
            if 0 <= index < len(screenshots):
                # 選択された画像をサムネイルとして設定
                selected = screenshots[index]
                app.thumbnail = selected
                app.save()
        else:
            # 新規作成時（セッションの画像を設定）
            screenshots = request.session.get('temp_screenshots', [])
            if 0 <= index < len(screenshots):
                selected = screenshots[index]
                request.session['temp_thumbnail'] = selected
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
