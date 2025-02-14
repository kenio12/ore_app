from django.shortcuts import render, redirect, get_object_or_404
from django.contrib.auth.decorators import login_required
from django.contrib import messages
from django.http import JsonResponse
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
        # デバッグ用ログ追加
        print(f"User: {request.user}, App ID: {pk}")
        
        app = get_object_or_404(AppGallery, id=pk, author=request.user)
        
        # 基本情報チェックを一時的に無効化（デバッグ用）
        """
        if not all([app.title, app.overview, app.screenshots]):
            messages.warning(request, '先に基本情報を入力してください！')
            return redirect('apps_gallery:edit', pk=pk)
        """
        
        if request.method == 'POST':
            is_ajax = request.headers.get('X-Requested-With') == 'XMLHttpRequest'
            
            try:
                # POSTデータから各フィールドを取得して保存
                # ハードウェア情報
                app.pc_type = request.POST.get('pc_type', '')
                app.device_type = request.POST.get('device_type', '')
                app.os_type = request.POST.get('os_type', '')
                app.cpu_type = request.POST.get('cpu_type', '')
                app.memory_size = request.POST.get('memory_size', '')
                app.storage_type = request.POST.get('storage_type', '')
                app.monitor_count = request.POST.get('monitor_count', '')
                app.monitor_size = request.POST.get('monitor_size', '')
                app.maker = request.POST.get('maker', '')
                app.internet_type = request.POST.get('internet_type', '')

                # 開発環境情報
                app.editor = request.POST.get('editor', '')
                app.version_control = request.POST.get('version_control', '')
                app.ci_cd = request.POST.get('ci_cd', '')
                app.virtualization = request.POST.get('virtualization', '')
                app.team_size = request.POST.get('team_size', '')
                app.communication_tool = request.POST.get('communication_tool', '')
                app.infrastructure = request.POST.get('infrastructure', '')
                app.api_tool = request.POST.get('api_tool', '')
                app.monitoring_tool = request.POST.get('monitoring_tool', '')

                # アーキテクチャ情報
                app.architecture_pattern = request.POST.get('architecture_pattern', '')
                app.design_pattern = request.POST.get('design_pattern', '')
                
                # 保存
                app.save()
                
                if is_ajax:
                    return JsonResponse({'success': True})
                messages.success(request, '技術情報を保存しました！')
                return redirect('apps_gallery:technical_edit', pk=pk)
                
            except Exception as e:
                print(f"Error saving technical info: {str(e)}")  # デバッグ用
                if is_ajax:
                    return JsonResponse({'success': False, 'error': str(e)}, status=400)
                messages.error(request, f'保存中にエラーが発生しました：{str(e)}')
        
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
        
        print(f"Rendering technical edit template for app: {app.title}")  # デバッグ用
        return render(request, 'apps_gallery/technical/edit_detail_technical.html', context)
        
    except Exception as e:
        print(f"Error in technical_edit_view: {str(e)}")  # デバッグ用
        messages.error(request, f'エラーが発生しました：{str(e)}')
        return redirect('apps_gallery:edit', pk=pk)

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