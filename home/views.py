from django.shortcuts import render
from django.contrib import messages
from apps_gallery.models import AppGallery
from apps_gallery.constants import *  # __init__.pyから全ての定数をインポート

def home(request):
    # 最新の投稿順（-created_at）でアプリを取得
    apps = AppGallery.objects.all().order_by('-created_at')
    context = {
        'apps': apps,
        'APP_TYPES': APP_TYPES,
        'GENRES': GENRES,
    }
    return render(request, 'home/home.html', context)
