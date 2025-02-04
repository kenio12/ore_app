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

        # リダイレクト先の判定
        if request.POST.get('redirect_to') == 'home':
            # ロボットアイコンからの保存時はホームへ
            return redirect('home:home')
        else:
            # 通常の保存時は詳細画面へ
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

@login_required
@require_http_methods(["POST"])
def upload_screenshot(request):
    try:
        image = request.FILES.get('image')
        app_id = request.POST.get('app_id')
        description = request.POST.get('description', '')

        if not image:
            return JsonResponse({'error': '画像が選択されていません'}, status=400)

        # アプリの取得
        app = get_object_or_404(AppGallery, pk=app_id)
        
        # 権限チェック
        if app.author != request.user:
            return JsonResponse({'error': '権限がありません'}, status=403)

        # 画像の枚数チェック
        if len(app.screenshots) >= 3:
            return JsonResponse({'error': '画像は最大3枚までです'}, status=400)

        # Cloudinaryにアップロード
        upload_result = cloudinary.uploader.upload(
            image,
            folder='app_screenshots'
        )

        # スクリーンショット情報を作成
        screenshot_info = {
            'url': upload_result['secure_url'],
            'public_id': upload_result['public_id'],
            'description': description
        }

        # 既存のスクリーンショットリストに追加
        screenshots = app.screenshots or []  # Noneの場合は空リストを使用
        screenshots.append(screenshot_info)
        app.screenshots = screenshots
        app.save()

        return JsonResponse({
            'message': 'アップロード成功',
            'screenshot': screenshot_info
        })

    except Exception as e:
        return JsonResponse({'error': str(e)}, status=500)

@login_required
@require_http_methods(["DELETE"])
def delete_screenshot(request, screenshot_id):
    try:
        app_id = request.GET.get('app_id')
        app = get_object_or_404(AppGallery, pk=app_id)

        # 権限チェック
        if app.author != request.user:
            return JsonResponse({'error': '権限がありません'}, status=403)

        # スクリーンショットの削除
        screenshots = app.screenshots or []
        screenshot = next((s for s in screenshots if s['public_id'] == screenshot_id), None)
        
        if screenshot:
            # Cloudinaryから画像を削除
            cloudinary.uploader.destroy(screenshot['public_id'])
            
            # リストから該当の画像情報を削除
            screenshots = [s for s in screenshots if s['public_id'] != screenshot_id]
            app.screenshots = screenshots
            app.save()
            
            return JsonResponse({'message': '削除成功'})
        else:
            return JsonResponse({'error': '指定された画像が見つかりません'}, status=404)

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
