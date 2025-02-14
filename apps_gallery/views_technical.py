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
from .constants.backend_constants import (
    BACKEND_STACK,
    BACKEND_PACKAGE_HINTS
)

@login_required
def technical_edit_view(request, pk):
    """技術情報の編集ビュー"""
    try:
        print("\n=== Debug Info ===")
        print(f"User: {request.user}, App ID: {pk}")
        
        app = get_object_or_404(AppGallery, id=pk, author=request.user)
        
        # デバッグ: バックエンドデータの確認
        print("Current backend data:", app.backend)
        print("BACKEND_STACK:", BACKEND_STACK)
        
        if request.method == 'POST':
            is_ajax = request.headers.get('X-Requested-With') == 'XMLHttpRequest'
            
            try:
                # フォームデータの処理
                if 'backend[main_language]' in request.POST:
                    # バックエンド情報の更新
                    app.backend = {
                        'main_language': request.POST.get('backend[main_language]'),
                        'framework': request.POST.get('backend[framework]'),
                        'packages': request.POST.getlist('backend[packages][]')
                    }
                    app.save()
                
                if is_ajax:
                    return JsonResponse({
                        'success': True,
                        'redirect_url': request.POST.get('next_url', None)
                    })
                
                messages.success(request, '技術情報を保存しました！')
                return redirect('apps_gallery:technical_edit', pk=pk)
                
            except Exception as e:
                print(f"Error saving technical info: {str(e)}")
                if is_ajax:
                    return JsonResponse({'success': False, 'error': str(e)}, status=400)
                messages.error(request, f'保存中にエラーが発生しました：{str(e)}')
        
        context = {
            'app': app,
            'hide_navbar': True,
            'readonly': False,  # 編集モードを明示的に指定
            'is_edit': True,   # 編集モードを明示的に指定
            'BACKEND_STACK': BACKEND_STACK,
            'BACKEND_PACKAGE_HINTS': BACKEND_PACKAGE_HINTS,
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
        
        print(f"Rendering technical edit template for app: {app.title}")
        return render(request, 'apps_gallery/technical/edit_detail_technical.html', context)
        
    except Exception as e:
        print(f"Error in technical_edit_view: {str(e)}")
        messages.error(request, f'エラーが発生しました：{str(e)}')
        return redirect('apps_gallery:edit', pk=pk)

def technical_detail_view(request, pk):
    """技術情報の詳細ビュー"""
    app = get_object_or_404(AppGallery, pk=pk)
    
    context = {
        'app': app,
        'readonly': True,
        'hide_navbar': True,
        # バックエンド関連（更新）
        'BACKEND_STACK': BACKEND_STACK,
        'BACKEND_PACKAGE_HINTS': BACKEND_PACKAGE_HINTS,
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