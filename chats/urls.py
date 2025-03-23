from django.urls import path
from . import views

app_name = 'chats'

urlpatterns = [
    path('', views.chat_list, name='chat_list'),
    path('new/<int:user_id>/', views.chat_detail, name='new_chat'),
    path('<int:conversation_id>/', views.chat_detail, name='chat_detail'),
    path('api/send-message/<int:conversation_id>/', views.send_message, name='send_message'),
    path('api/get-messages/<int:conversation_id>/', views.get_messages, name='get_messages'),
    path('api/get-older-messages/<int:conversation_id>/', views.get_older_messages, name='get_older_messages'),
    path('api/unread-messages/', views.get_unread_messages, name='get_unread_messages'),
    path('api/leave-chat/<int:conversation_id>/', views.leave_chat, name='leave_chat'),
    path('api/mark-read/<int:message_id>/', views.mark_message_read, name='mark_message_read'),
    path('api/message-stream/', views.message_stream, name='message_stream'),
] 