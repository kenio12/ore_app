from django.urls import path
from . import views

app_name = 'profiles'

urlpatterns = [
    path('', views.profile_detail, name='profile_detail'),
    path('edit/', views.profile_edit, name='profile_edit'),
    path('programmers/', views.programmer_list, name='programmer_list'),
    path('programmers-data/', views.programmers_data, name='programmers_data'),
] 