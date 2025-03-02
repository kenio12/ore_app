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
        'profile': request.user.profile
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
    }
    return render(request, 'profiles/profile_edit.html', context)
