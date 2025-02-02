from django.shortcuts import render
from .constants.app_info import TABS, APP_TYPES, GENRES, APP_STATUS

# Create your views here.

def create_app(request):
    context = {
        'tabs': TABS,
        'app_types': APP_TYPES,
        'genres': GENRES,
        'app_status': APP_STATUS,
    }
    return render(request, 'apps_gallery/create.html', context)

def app_list(request):
    # とりあえず仮の実装
    return render(request, 'apps_gallery/list.html')

def app_detail(request, pk):
    # とりあえず仮の実装
    return render(request, 'apps_gallery/detail.html')

def edit_app(request, pk):
    # とりあえず仮の実装
    return render(request, 'apps_gallery/edit.html')

def delete_app(request, pk):
    # とりあえず仮の実装
    return render(request, 'apps_gallery/delete.html')
