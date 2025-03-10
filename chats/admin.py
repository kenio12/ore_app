from django.contrib import admin
from .models import Message, Conversation

@admin.register(Message)
class MessageAdmin(admin.ModelAdmin):
    list_display = ('sender', 'recipient', 'content', 'timestamp', 'is_read')
    list_filter = ('is_read', 'timestamp')
    search_fields = ('sender__username', 'recipient__username', 'content')
    readonly_fields = ('timestamp',)

@admin.register(Conversation)
class ConversationAdmin(admin.ModelAdmin):
    list_display = ('id', 'created_at', 'updated_at')
    readonly_fields = ('created_at', 'updated_at')
    filter_horizontal = ('participants',)
