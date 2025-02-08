from django.urls import path
from . import views

app_name = 'apps_gallery'

urlpatterns = [
    path('create/', views.create_view, name='create'),  # アプリ作成
    path('edit/<int:pk>/', views.edit_app, name='edit'),  # アプリ編集（詳細の前に配置）
    path('<int:pk>/', views.app_detail, name='detail'),  # アプリ詳細（最後に配置）
    path('list/', views.app_list, name='list'),  # アプリ一覧
    path('delete/<int:pk>/', views.delete_app, name='delete'),  # アプリ削除（詳細の前に配置）
    path('upload-screenshot/', views.upload_screenshot, name='upload_screenshot'),
    path('delete-screenshot/', views.delete_screenshot, name='delete_screenshot'),  # スクリーンショット削除
    path('set-thumbnail/', views.set_thumbnail, name='set_thumbnail'),  # サムネイル設定
    path('reset-screenshots/<int:pk>/', views.reset_screenshots, name='reset_screenshots'),
] 