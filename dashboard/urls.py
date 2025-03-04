from django.urls import path
from . import views

app_name = 'dashboard'

urlpatterns = [
    path('', views.index, name='index'),
    path('apps/', views.apps, name='apps'),
    path('profile/', views.profile, name='profile'),
    path('account/', views.account, name='account'),
    path('account/change-password/', views.change_password, name='change_password'),
    path('account/change-email/', views.change_email, name='change_email'),
    path('account/terminate-session/', views.terminate_session, name='terminate_session'),
    path('account/terminate-all-sessions/', views.terminate_all_sessions, name='terminate_all_sessions'),
    path('account/delete/', views.delete_account, name='delete_account'),
    path('notifications/', views.notifications, name='notifications'),
    path('analytics/', views.analytics, name='analytics'),
] 