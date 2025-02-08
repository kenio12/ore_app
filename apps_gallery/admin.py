from django.contrib import admin
from .models import AppGallery

@admin.register(AppGallery)
class AppGalleryAdmin(admin.ModelAdmin):
    list_display = ('title', 'author', 'created_at', 'updated_at')
    list_filter = ('author', 'created_at')
    search_fields = ('title', 'overview')
    readonly_fields = ('created_at', 'updated_at')
    
    def get_readonly_fields(self, request, obj=None):
        # 作成時は読み取り専用フィールドを設定しない
        if obj is None:
            return ()
        return self.readonly_fields
