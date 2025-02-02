from django.urls import path
from . import views

app_name = 'apps'  # これがnamespaceになる

urlpatterns = [
    path('create/', views.create_app, name='create'),  # アプリ作成
    path('list/', views.app_list, name='list'),  # アプリ一覧
    path('<int:pk>/', views.app_detail, name='detail'),  # アプリ詳細
    path('<int:pk>/edit/', views.edit_app, name='edit'),  # アプリ編集
    path('<int:pk>/delete/', views.delete_app, name='delete'),  # アプリ削除
] 