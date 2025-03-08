from django.shortcuts import render, redirect, get_object_or_404
from django.contrib.auth.decorators import login_required
from django.contrib import messages
from .forms import ProfileForm, ProfileEditForm
import cloudinary
import cloudinary.uploader
from .models import Profile
from django.http import JsonResponse
from django.contrib.auth.models import User
from django.contrib.auth import get_user_model
from django.db.models import Count
from django.utils import timezone
import json
import logging
from apps_gallery.constants.app_info import APP_TYPES

logger = logging.getLogger(__name__)

# Create your views here.

@login_required
def profile_detail(request):
    """プロフィール詳細表示"""
    # ダッシュボードのプロフィールページにリダイレクト
    return redirect('dashboard:profile')

def user_profile_detail(request, user_id):
    """特定ユーザーのプロフィール詳細を表示"""
    User = get_user_model()
    profile_user = get_object_or_404(User, id=user_id)
    profile = get_object_or_404(Profile, user=profile_user)
    
    # テンプレートで使用するコンテキスト変数を設定
    is_own_profile = request.user.is_authenticated and request.user.id == user_id
    
    # ユーザーが投稿したアプリのタイプ別カウントを集計
    app_type_counts = {}
    if hasattr(profile_user, 'apps'):
        user_apps = profile_user.apps.all()
        for app in user_apps:
            if hasattr(app, 'app_types') and app.app_types:
                for app_type in app.app_types:
                    app_type_counts[app_type] = app_type_counts.get(app_type, 0) + 1
    
    # PCタイプなどの選択肢マッピング（実際のappのデータに合わせて調整必要）
    pc_types = {
        'desktop': 'デスクトップPC',
        'laptop': 'ノートPC',
        'other': 'その他'
    }
    
    device_types = {
        'windows': 'Windows PC',
        'mac': 'Mac',
        'linux': 'Linux PC',
        'other': 'その他'
    }
    
    cpu_types = {
        'intel_core_i3': 'Intel Core i3',
        'intel_core_i5': 'Intel Core i5',
        'intel_core_i7': 'Intel Core i7',
        'intel_core_i9': 'Intel Core i9',
        'amd_ryzen_3': 'AMD Ryzen 3',
        'amd_ryzen_5': 'AMD Ryzen 5',
        'amd_ryzen_7': 'AMD Ryzen 7',
        'amd_ryzen_9': 'AMD Ryzen 9',
        'apple_silicon': 'Apple Silicon',
        'other': 'その他'
    }
    
    memory_sizes = {
        '4gb': '4GB',
        '8gb': '8GB',
        '16gb': '16GB',
        '32gb': '32GB',
        '64gb': '64GB以上',
        'other': 'その他'
    }
    
    storage_types = {
        'hdd_under_1tb': 'HDD 1TB未満',
        'hdd_1tb_more': 'HDD 1TB以上',
        'ssd_under_512gb': 'SSD 512GB未満',
        'ssd_512gb_more': 'SSD 512GB以上',
        'nvme_under_512gb': 'NVMe SSD 512GB未満',
        'nvme_512gb_more': 'NVMe SSD 512GB以上',
        'other': 'その他'
    }
    
    monitor_counts = {
        'single': '1台',
        'dual': '2台',
        'triple': '3台',
        'quad': '4台以上',
        'other': 'その他'
    }
    
    internet_types = {
        'fiber': '光回線',
        'adsl': 'ADSL',
        'cable': 'ケーブルテレビ回線',
        'wimax': 'WiMAX',
        'mobile': 'モバイル回線',
        'other': 'その他'
    }
    
    context = {
        'user': profile_user,
        'profile': profile,
        'is_own_profile': is_own_profile,
        'pc_types': pc_types,
        'device_types': device_types,
        'cpu_types': cpu_types,
        'memory_sizes': memory_sizes,
        'storage_types': storage_types,
        'monitor_counts': monitor_counts,
        'internet_types': internet_types,
        'app_type_counts': app_type_counts,
        'APP_TYPES': APP_TYPES,
    }
    
    return render(request, 'profiles/profile_detail.html', context)

@login_required
def profile_edit(request):
    """プロフィール編集ビュー"""
    profile = request.user.profile
    
    if request.method == 'POST':
        # form = ProfileForm(request.POST, request.FILES, instance=profile)
        form = ProfileEditForm(request.POST, request.FILES, instance=profile)
        
        if form.is_valid():
            # ファイルアップロードの処理
            if 'avatar' in request.FILES:
                # ファイルを保存して結果のURLをJSONとして保存
                avatar_file = request.FILES['avatar']
                # ランダムなファイル名を生成
                import uuid
                filename = f"{uuid.uuid4()}.{avatar_file.name.split('.')[-1]}"
                # static/uploadにファイルを保存
                import os
                from django.conf import settings
                
                # 保存先ディレクトリが存在しない場合は作成
                upload_dir = os.path.join(settings.MEDIA_ROOT, 'upload', 'avatar')
                os.makedirs(upload_dir, exist_ok=True)
                
                # ファイルを保存
                with open(os.path.join(upload_dir, filename), 'wb+') as destination:
                    for chunk in avatar_file.chunks():
                        destination.write(chunk)
                
                # URLをプロフィールに保存
                profile.avatar = {
                    'url': f'/media/upload/avatar/{filename}',
                    'filename': filename
                }
            
            # 他のフィールドを保存
            profile.bio = form.cleaned_data['bio']
            profile.social_github = form.cleaned_data['social_github']
            profile.social_twitter = form.cleaned_data['social_twitter']
            
            # 新しく追加したフィールドを保存
            profile.job_status = form.cleaned_data['job_status']
            profile.job_types = form.cleaned_data['job_types']  # MultipleChoiceFieldの値をリストとして保存
            profile.work_rate = form.cleaned_data['work_rate']
            
            # モデルを保存
            profile.save()
            
            # ハードウェア関連の情報を取得して保存
            hardware_specs = {}
            
            # メーカーと機種情報
            if 'maker' in request.POST and request.POST['maker']:
                hardware_specs['maker'] = request.POST['maker']
            
            if 'model' in request.POST and request.POST['model']:
                hardware_specs['model'] = request.POST['model']
                
            # PCタイプ
            if 'pc_type' in request.POST and request.POST['pc_type']:
                hardware_specs['pc_type'] = request.POST['pc_type']
                
            # デバイスタイプ
            if 'device_type' in request.POST and request.POST['device_type']:
                hardware_specs['device_type'] = request.POST['device_type']
                
            # CPU
            if 'cpu_type' in request.POST and request.POST['cpu_type']:
                hardware_specs['cpu_type'] = request.POST['cpu_type']
                
            # メモリ
            if 'memory_size' in request.POST and request.POST['memory_size']:
                hardware_specs['memory_size'] = request.POST['memory_size']
                
            # ストレージ
            if 'storage_type' in request.POST and request.POST['storage_type']:
                hardware_specs['storage_type'] = request.POST['storage_type']
                
            # モニター数
            if 'monitor_count' in request.POST and request.POST['monitor_count']:
                hardware_specs['monitor_count'] = request.POST['monitor_count']
                
            # インターネット回線
            if 'internet_type' in request.POST and request.POST['internet_type']:
                hardware_specs['internet_type'] = request.POST['internet_type']
            
            # ハードウェア情報をプロフィールに保存
            profile.hardware_specs = hardware_specs
            profile.save()
            
            # 技術スキル情報を自動更新
            profile.update_skills_from_apps()
            
            messages.success(request, 'プロフィールを更新しました！')
            return redirect('profiles:profile_detail')
    else:
        # form = ProfileForm(instance=profile)
        form = ProfileEditForm(instance=profile)
        
        # 既存のjob_typesの値をフォームにセット
        if profile.job_types:
            form.initial['job_types'] = profile.job_types
    
    # 各種選択肢を取得
    pc_types = get_pc_types()
    device_types = get_device_types()
    cpu_types = get_cpu_types()
    memory_sizes = get_memory_sizes()
    storage_types = get_storage_types()
    monitor_counts = get_monitor_counts()
    internet_types = get_internet_types()
    
    context = {
        'form': form,
        'pc_types': pc_types,
        'device_types': device_types,
        'cpu_types': cpu_types,
        'memory_sizes': memory_sizes,
        'storage_types': storage_types,
        'monitor_counts': monitor_counts,
        'internet_types': internet_types,
        'maker_examples': get_maker_examples(),
    }
    return render(request, 'profiles/profile_edit.html', context)

# ハードウェア情報の選択肢
def get_pc_types():
    return {
        'desktop': 'デスクトップPC',
        'laptop': 'ノートPC',
        'all_in_one': 'オールインワンPC',
        'mini_pc': 'ミニPC',
        'other': 'その他'
    }

def get_device_types():
    return {
        'windows': 'Windows PC',
        'mac': 'Mac',
        'chromebook': 'Chromebook',
        'linux': 'Linux PC',
        'other': 'その他'
    }

def get_cpu_types():
    return {
        'intel_core_i3': 'Intel Core i3',
        'intel_core_i5': 'Intel Core i5',
        'intel_core_i7': 'Intel Core i7',
        'intel_core_i9': 'Intel Core i9',
        'intel_other': 'Intel その他',
        'amd_ryzen_3': 'AMD Ryzen 3',
        'amd_ryzen_5': 'AMD Ryzen 5',
        'amd_ryzen_7': 'AMD Ryzen 7',
        'amd_ryzen_9': 'AMD Ryzen 9',
        'amd_other': 'AMD その他',
        'apple_m1': 'Apple M1',
        'apple_m2': 'Apple M2',
        'apple_m3': 'Apple M3',
        'qualcomm': 'Qualcomm Snapdragon',
        'other': 'その他'
    }

def get_memory_sizes():
    return {
        '4gb': '4GB',
        '8gb': '8GB',
        '16gb': '16GB',
        '32gb': '32GB',
        '64gb': '64GB以上',
        'other': 'その他'
    }

def get_storage_types():
    return {
        'hdd': 'HDD（ハードディスク）',
        'ssd': 'SSD',
        'nvme': 'NVMe SSD',
        'hybrid': 'ハイブリッド（HDD+SSD）',
        'other': 'その他'
    }

def get_monitor_counts():
    return {
        'single': '1台',
        'dual': '2台',
        'triple': '3台',
        'multiple': '4台以上',
        'none': '外部モニターなし'
    }

def get_internet_types():
    return {
        'fiber': '光回線',
        'cable': 'ケーブルテレビ回線',
        'adsl': 'ADSL回線',
        'mobile': 'モバイル回線',
        'other': 'その他'
    }

def get_maker_examples():
    return [
        'Apple', 'Dell', 'HP', 'Lenovo', 'ASUS', 
        'Acer', 'Microsoft', 'MSI', 'Toshiba', 'VAIO', 
        'Fujitsu', 'NEC', 'EPSON', 'Panasonic', 'マウスコンピューター'
    ]

def programmer_list(request):
    """プログラマー一覧ページ - シンプル版"""
    profiles = Profile.objects.all()
    
    # デバッグ用の出力
    print("=== プログラマー一覧デバッグ - シンプル版 ===")
    print(f"プロフィール数: {profiles.count()}")
    for profile in profiles:
        print(f"ユーザー: {profile.user.username}")
    print("=====================")
    
    return render(request, 'profiles/programmer_list.html', {
        'profiles': profiles,
    })

def programmers_data(request):
    """プログラマーデータをJSON形式で提供するAPI"""
    profiles = Profile.objects.all().order_by('-updated_at')
    
    # JSONレスポンス用のデータ整形
    profiles_data = []
    for profile in profiles:
        # 最終更新日から3日以内かどうかでアクティブ状態を判定
        from datetime import datetime, timedelta
        is_active = (datetime.now().date() - profile.updated_at.date()) <= timedelta(days=3)
        
        # ユーザーが投稿したアプリのタイプ別カウントを集計
        app_type_counts = {}
        if hasattr(profile.user, 'apps'):
            user_apps = profile.user.apps.all()
            for app in user_apps:
                if hasattr(app, 'app_types') and app.app_types:
                    for app_type in app.app_types:
                        app_type_counts[app_type] = app_type_counts.get(app_type, 0) + 1
        
        # アプリのカテゴリ（一意な値）を集計
        app_categories = []
        if hasattr(profile.user, 'apps'):
            for app in profile.user.apps.all():
                if hasattr(app, 'app_types') and app.app_types:
                    for app_type in app.app_types:
                        if app_type not in app_categories:
                            app_categories.append(app_type)
        
        # 言語・フレームワークのリスト（使用回数が多い順）を追加
        top_languages = []
        top_frameworks = []
        
        if profile.skills and 'data' in profile.skills:
            # バックエンド言語
            if 'backend_languages' in profile.skills['data']:
                backend_langs = profile.skills['data']['backend_languages']
                top_languages.extend(list(backend_langs.keys())[:3])
            
            # フロントエンド言語
            if 'frontend_languages' in profile.skills['data']:
                frontend_langs = profile.skills['data']['frontend_languages']
                top_languages.extend(list(frontend_langs.keys())[:3])
            
            # バックエンドフレームワーク
            if 'backend_frameworks' in profile.skills['data']:
                backend_fw = profile.skills['data']['backend_frameworks']
                top_frameworks.extend(list(backend_fw.keys())[:3])
            
            # フロントエンドフレームワーク
            if 'frontend_frameworks' in profile.skills['data']:
                frontend_fw = profile.skills['data']['frontend_frameworks']
                top_frameworks.extend(list(frontend_fw.keys())[:3])
        
        # 重複を削除
        top_languages = list(dict.fromkeys(top_languages))
        top_frameworks = list(dict.fromkeys(top_frameworks))
        
        profile_data = {
            'id': profile.id,
            'user_id': profile.user.id,
            'username': profile.user.username,
            'bio': profile.bio,
            'avatar_url': profile.avatar_url,
            'updated_at': profile.updated_at.strftime('%Y/%m/%d'),
            'is_active': is_active,
            'hardware_specs': profile.hardware_specs,
            'social_github': profile.social_github,
            'social_twitter': profile.social_twitter,
            
            # 新しく追加したフィールド
            'skills': profile.skills,
            'specializations': profile.specializations,
            'job_status': profile.job_status,
            'job_status_display': profile.get_job_status_display(),
            'job_types': profile.job_types,
            'work_rate': profile.work_rate,
            
            # ユーザーが投稿したアプリの数
            'app_count': profile.user.apps.count() if hasattr(profile.user, 'apps') else 0,
            
            # アプリのタイプ別カウント
            'app_type_counts': app_type_counts,
            
            # アプリのカテゴリリスト
            'app_categories': app_categories,
            
            # 上位の言語とフレームワーク
            'top_languages': top_languages,
            'top_frameworks': top_frameworks,
            
            # ユーザーが投稿したアプリの最新5件をIDのみ取得
            'recent_apps': list(profile.user.apps.order_by('-created_at').values_list('id', flat=True)[:5]) if hasattr(profile.user, 'apps') else [],
        }
        profiles_data.append(profile_data)
    
    return JsonResponse({
        'profiles': profiles_data,
        'count': len(profiles_data)
    })

@login_required
def contact_user(request, user_id):
    User = get_user_model()
    target_user = get_object_or_404(User, id=user_id)
    
    if request.method == 'POST':
        subject = request.POST.get('subject', '')
        message = request.POST.get('message', '')
        
        if not subject or not message:
            messages.error(request, "件名とメッセージを入力してください。")
            return redirect('profiles:contact', user_id=user_id)
        
        # ここでは実際にメッセージを保存しないでデモとして表示するだけ
        sender = request.user.username
        messages.success(
            request, 
            f"{target_user.username}さんへメッセージを送信しました！"
        )
        return redirect('profiles:user_profile_detail', user_id=user_id)
    
    context = {
        'target_user': target_user
    }
    
    return render(request, 'profiles/contact.html', context)

# セッションのメッセージを消去するビュー
def clear_messages(request):
    storage = messages.get_messages(request)
    for message in storage:
        pass  # メッセージをイテレートして消去
    storage.used = True  # ストレージを使用済みとしてマーク
    
    # リダイレクト先のURLを取得（デフォルトはホームページ）
    next_url = request.GET.get('next', '/')
    return redirect(next_url)
