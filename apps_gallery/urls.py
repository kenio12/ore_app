from django.urls import path
from . import views, views_technical  # views_technicalを追加

app_name = 'apps_gallery'

urlpatterns = [
    path('create/', views.create_view, name='create'),  # アプリ作成
    path('edit/<int:pk>/', views.edit_app, name='edit'),  # アプリ編集（詳細の前に配置）
    path('<int:pk>/', views.app_detail, name='detail'),  # アプリ詳細（最後に配置）
    path('delete/<int:pk>/', views.delete_app, name='delete'),  # アプリ削除（詳細の前に配置）
    path('upload-screenshot/', views.upload_screenshot, name='upload_screenshot'),
    path('delete-screenshot/', views.delete_screenshot, name='delete_screenshot'),  # スクリーンショット削除
    path('set-thumbnail/', views.set_thumbnail, name='set_thumbnail'),  # サムネイル設定
    path('reset-screenshots/<int:pk>/', views.reset_screenshots, name='reset_screenshots'),
    
    # 技術情報関連の新しいURL
    path('technical/edit/<int:pk>/', views_technical.technical_edit_view, name='technical_edit'),
    path('technical/detail/<int:pk>/', views_technical.technical_detail_view, name='technical_detail'),

    # 自動保存関連のURL
    path('auto-save/<int:app_id>/', views.auto_save_app, name='auto_save_with_id'),
    path('auto-save/', views.auto_save_app, name='auto_save'),  # 新規作成時用
    path('delete-empty/<int:app_id>/', views.delete_empty_app, name='delete_empty'),  # 空アプリ削除
] 