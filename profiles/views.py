from django.shortcuts import render, redirect
from django.contrib.auth.decorators import login_required
from django.contrib import messages
from .forms import ProfileForm
import cloudinary
import cloudinary.uploader

# Create your views here.

@login_required
def profile_detail(request):
    """プロフィール詳細表示"""
    context = {
        'user': request.user,
        'profile': request.user.profile,
        # ハードウェア情報の選択肢
        'pc_types': get_pc_types(),
        'device_types': get_device_types(),
        'cpu_types': get_cpu_types(),
        'memory_sizes': get_memory_sizes(),
        'storage_types': get_storage_types(),
        'monitor_counts': get_monitor_counts(),
        'internet_types': get_internet_types(),
        'maker_examples': get_maker_examples(),
    }
    return render(request, 'profiles/profile_detail.html', context)

@login_required
def profile_edit(request):
    """プロフィール編集"""
    profile = request.user.profile
    
    if request.method == 'POST':
        form = ProfileForm(request.POST, request.FILES, instance=profile)
        if form.is_valid():
            # フォームを一時保存（まだコミットしない）
            profile_obj = form.save(commit=False)
            
            # ハードウェア情報の保存
            hardware_specs = {}
            hardware_fields = [
                'maker', 'model', 'pc_type', 'device_type', 'cpu_type',
                'memory_size', 'storage_type', 'monitor_count', 'internet_type'
            ]
            
            for field in hardware_fields:
                if field in request.POST and request.POST[field]:
                    hardware_specs[field] = request.POST[field]
            
            if hardware_specs:
                profile_obj.hardware_specs = hardware_specs
            
            # アバター画像のアップロード処理
            if 'avatar' in request.FILES:
                image = request.FILES['avatar']
                # Cloudinaryにアップロード
                upload_result = cloudinary.uploader.upload(
                    image,
                    folder=f'profile_avatars/user_{request.user.id}'
                )
                
                # アップロード結果をJSONフィールドに保存
                profile_obj.avatar = {
                    'public_id': upload_result['public_id'],
                    'url': upload_result['secure_url']
                }
                
                messages.success(request, 'プロフィール画像を更新しました。')
            
            # 既存の画像を削除する場合
            elif 'avatar-clear' in request.POST and profile.avatar and 'public_id' in profile.avatar:
                # Cloudinaryから画像を削除
                cloudinary.uploader.destroy(profile.avatar['public_id'])
                profile_obj.avatar = None
            
            # 保存
            profile_obj.save()
            messages.success(request, 'プロフィールを更新しました。')
            return redirect('profiles:profile_detail')
    else:
        form = ProfileForm(instance=profile)
    
    context = {
        'form': form,
        # ハードウェア情報の選択肢
        'pc_types': get_pc_types(),
        'device_types': get_device_types(),
        'cpu_types': get_cpu_types(),
        'memory_sizes': get_memory_sizes(),
        'storage_types': get_storage_types(),
        'monitor_counts': get_monitor_counts(),
        'internet_types': get_internet_types(),
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
