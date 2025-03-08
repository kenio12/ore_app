from django.urls import path
from . import views

app_name = 'profiles'

urlpatterns = [
    path('profile/', views.profile_detail, name='profile_detail'),
    path('profile/edit/', views.profile_edit, name='profile_edit'),
    path('programmers/', views.programmer_list, name='programmer_list'),
    path('api/programmers/', views.programmers_data, name='programmers_data'),
    path('detail/<int:user_id>/', views.user_profile_detail, name='user_profile_detail'),
    path('contact/<int:user_id>/', views.contact_user, name='contact'),
    path('clear-messages/', views.clear_messages, name='clear_messages'),
] 