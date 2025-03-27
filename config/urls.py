"""
URL configuration for config project.

The `urlpatterns` list routes URLs to views. For more information please see:
    https://docs.djangoproject.com/en/5.1/topics/http/urls/
Examples:
Function views
    1. Add an import:  from my_app import views
    2. Add a URL to urlpatterns:  path('', views.home, name='home')
Class-based views
    1. Add an import:  from other_app.views import Home
    2. Add a URL to urlpatterns:  path('', Home.as_view(), name='home')
Including another URLconf
    1. Import the include() function: from django.urls import include, path
    2. Add a URL to urlpatterns:  path('blog/', include('blog.urls'))
"""
from django.contrib import admin
from django.urls import path, include
from django.http import HttpResponse
from django.conf import settings
from django.conf.urls.static import static
import logging

# ロギング設定
logger = logging.getLogger(__name__)

def health_check(request):
    logger.debug("ヘルスチェックリクエスト: %s", request.META.get('REMOTE_ADDR'))
    return HttpResponse("OK")

def debug_info(request):
    """詳細なデバッグ情報を表示"""
    logger.debug("デバッグ情報リクエスト: %s", request.META.get('REMOTE_ADDR'))
    info = {
        'path': request.path,
        'method': request.method,
        'headers': dict(request.headers),
        'GET': dict(request.GET),
        'POST': dict(request.POST) if request.method == 'POST' else {},
        'user': str(request.user),
        'settings': {
            'DEBUG': settings.DEBUG,
            'ALLOWED_HOSTS': settings.ALLOWED_HOSTS,
            'STATIC_ROOT': settings.STATIC_ROOT,
            'INSTALLED_APPS': settings.INSTALLED_APPS,
        }
    }
    response = "<html><body><pre>" + str(info) + "</pre></body></html>"
    return HttpResponse(response)

urlpatterns = [
    path('admin/', admin.site.urls),
    path('accounts/', include('accounts.urls')),  # accountsアプリのURL
    path('accounts/', include('django.contrib.auth.urls')),  # Django認証システムのURL
    path('apps_gallery/', include('apps_gallery.urls')),  # URLパスを apps から apps_gallery に変更
    path('', include('home.urls')),  # ホームページのURL追加
    path('health/', health_check, name='health_check'),
    path('debug/', debug_info, name='debug_info'),  # デバッグ情報
    path('dashboard/', include('dashboard.urls')),
    path('profiles/', include('profiles.urls')),
    path('chats/', include('chats.urls')),  # チャット機能のURL
    path('blogs/', include('blogs.urls')),  # ブログ機能のURL
]

# 開発環境のみメディアファイルを配信
if settings.DEBUG:
    urlpatterns += static(settings.MEDIA_URL, document_root=settings.MEDIA_ROOT)
