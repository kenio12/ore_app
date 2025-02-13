from django.shortcuts import render, redirect, get_object_or_404
from django.contrib.auth.decorators import login_required
from django.contrib import messages
from .models import AppGallery
from .constants.hardware import (
    PC_TYPES,
    DEVICE_TYPES,
    OS_TYPES,
    CPU_TYPES,
    MEMORY_SIZES,
    STORAGE_TYPES,
    MONITOR_COUNTS,
    MONITOR_SIZES,
    MAKER_EXAMPLES,
    INTERNET_TYPES
)
from .constants.development import (
    EDITORS,
    VERSION_CONTROL,
    CI_CD,
    VIRTUALIZATION_TOOLS,
    TEAM_SIZES,
    COMMUNICATION_TOOLS,
    INFRASTRUCTURE,
    API_TOOLS,
    MONITORING_TOOLS,
)
from .constants.architecture import (
    ARCHITECTURE_PATTERNS,
    DESIGN_PATTERNS,
)

@login_required
def technical_edit_view(request, pk):
    """技術情報の編集ビュー"""
    try:
        # アプリの存在確認と基本情報チェック
        app = AppGallery.objects.get(id=pk, author=request.user)
        
        # 基本情報が揃っているかチェック
        if not all([app.title, app.overview, app.screenshots]):
            messages.warning(request, '先に基本情報を入力してください！')
            return redirect('apps_gallery:edit', pk=pk)
        
        if request.method == 'POST':
            # POSTの処理はここに実装予定
            pass
        
        context = {
            'app': app,
            'hide_navbar': True,
            'readonly': False,
            'is_edit': True,
            # ハードウェア関連
            'pc_types': PC_TYPES,
            'device_types': DEVICE_TYPES,
            'os_types': OS_TYPES,
            'cpu_types': CPU_TYPES,
            'memory_sizes': MEMORY_SIZES,
            'storage_types': STORAGE_TYPES,
            'monitor_counts': MONITOR_COUNTS,
            'monitor_sizes': MONITOR_SIZES,
            'maker_examples': MAKER_EXAMPLES,
            'internet_types': INTERNET_TYPES,
            # 開発環境関連
            'editors': EDITORS,
            'version_control': VERSION_CONTROL,
            'ci_cd': CI_CD,
            'virtualization_tools': VIRTUALIZATION_TOOLS,
            # 追加の開発環境関連コンテキスト
            'team_sizes': TEAM_SIZES,
            'communication_tools': COMMUNICATION_TOOLS,
            'infrastructure': INFRASTRUCTURE,
            'api_tools': API_TOOLS,
            'monitoring_tools': MONITORING_TOOLS,
            # アーキテクチャ関連
            'architecture_patterns': ARCHITECTURE_PATTERNS,
            'design_patterns': DESIGN_PATTERNS,
        }
        
        # デバッグ用
        print("Context for technical edit:", context)
        
        return render(request, 'apps_gallery/technical/edit_detail_technical.html', context)
        
    except AppGallery.DoesNotExist:
        messages.error(request, 'アプリが見つかりません。')
        return redirect('home:home')

def technical_detail_view(request, pk):
    """技術情報の詳細ビュー"""
    app = get_object_or_404(AppGallery, pk=pk)
    
    context = {
        'app': app,
        'readonly': True,
        'hide_navbar': True,
        # ハードウェア関連
        'cpu_types': CPU_TYPES,
        'memory_sizes': MEMORY_SIZES,
        'storage_types': STORAGE_TYPES,
        # 開発環境関連
        'editors': EDITORS,
        'version_control': VERSION_CONTROL,
        'ci_cd': CI_CD,
        'virtualization_tools': VIRTUALIZATION_TOOLS,
        # 追加の開発環境関連コンテキスト
        'team_sizes': TEAM_SIZES,
        'communication_tools': COMMUNICATION_TOOLS,
        'infrastructure': INFRASTRUCTURE,
        'api_tools': API_TOOLS,
        'monitoring_tools': MONITORING_TOOLS,
        # アーキテクチャ関連
        'architecture_patterns': ARCHITECTURE_PATTERNS,
        'design_patterns': DESIGN_PATTERNS,
    }
    
    # デバッグ用
    print("Context for technical detail:", context)
    
    return render(request, 'apps_gallery/technical/edit_detail_technical.html', context) 