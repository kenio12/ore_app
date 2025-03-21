from django.urls import path
from . import views

app_name = 'blogs'

urlpatterns = [
    path('', views.post_list, name='post_list'),
    path('post/create/', views.post_create, name='post_create'),
    path('post/<slug:slug>/', views.post_detail, name='post_detail'),
    path('post/<slug:slug>/edit/', views.post_edit, name='post_edit'),
    path('post/<slug:slug>/delete/', views.post_delete, name='post_delete'),
    path('like/', views.like_toggle, name='like_toggle'),
    path('my-posts/', views.my_posts, name='my_posts'),
    path('upload-image/', views.upload_image, name='upload_image'),
] 