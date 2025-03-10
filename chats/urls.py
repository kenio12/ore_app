from django.urls import path
from . import views

app_name = 'chats'

urlpatterns = [
    path('', views.chat_list, name='chat_list'),
    path('<int:conversation_id>/', views.chat_detail, name='chat_detail'),
    path('new/<int:user_id>/', views.chat_detail, name='new_chat'),
    path('api/send-message/', views.send_message, name='send_message'),
    path('api/get-messages/<int:conversation_id>/', views.get_messages, name='get_messages'),
    path('api/unread-count/', views.get_unread_count, name='get_unread_count'),
    path('api/unread-messages/', views.get_unread_messages, name='get_unread_messages'),
] 