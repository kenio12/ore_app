from django.urls import path, include
from . import views
from django.contrib.auth import views as auth_views
from django.views.decorators.csrf import ensure_csrf_cookie

app_name = 'accounts'

urlpatterns = [
    path('signup/', views.SignUpView.as_view(), name='signup'),
    path('verify/<str:code>/', views.verify_email, name='verify_email'),
    path('login/', ensure_csrf_cookie(auth_views.LoginView.as_view()), name='login'),
    path('', include('django.contrib.auth.urls')),  # 認証システムのURLを追加
] 