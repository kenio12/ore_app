from django.urls import path
from . import views

app_name = 'apps_gallery'

urlpatterns = [
    path('create/', views.create_view, name='create'),  # アプリ作成
    path('list/', views.app_list, name='list'),  # アプリ一覧
    path('<int:pk>/edit/', views.edit_app, name='edit'),  # アプリ編集（詳細の前に配置）
    path('<int:pk>/delete/', views.delete_app, name='delete'),  # アプリ削除（詳細の前に配置）
    path('<int:pk>/', views.app_detail, name='detail'),  # アプリ詳細（最後に配置）
] 