from django.urls import path, include
from . import views

app_name = 'accounts'

urlpatterns = [
    path('signup/', views.SignUpView.as_view(), name='signup'),
    path('verify/<str:uidb64>/<str:token>/', views.verify_email, name='verify_email'),
    path('', include('django.contrib.auth.urls')),  # 認証システムのURLを追加
] 