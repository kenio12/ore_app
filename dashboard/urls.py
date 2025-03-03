from django.urls import path
from . import views

app_name = 'dashboard'

urlpatterns = [
    path('', views.index, name='index'),
    path('apps/', views.apps, name='apps'),
    path('profile/', views.profile, name='profile'),
    path('account/', views.account, name='account'),
    path('notifications/', views.notifications, name='notifications'),
    path('analytics/', views.analytics, name='analytics'),
] 