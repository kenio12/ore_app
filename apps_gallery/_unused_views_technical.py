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
import json

@login_required
def technical_edit_view(request, pk):
    """技術情報の編集ビュー"""
    try:
        app = get_object_or_404(AppGallery, id=pk, author=request.user)
        
        if request.method == 'POST':
            # コンテントタイプのチェックを追加
            if request.content_type != 'application/json':
                return JsonResponse({
                    'success': False,
                    'error': f'Invalid content type: {request.content_type}'
                }, status=400)

            try:
                data = json.loads(request.body.decode('utf-8'))
                print("\n=== Debug Info ===")
                print(f"User: {request.user}, App ID: {pk}")
                print("Content-Type:", request.content_type)
                print("Received data:", data)

                # 現在のデータを保持
                current_data = {
                    'hardware_specs': dict(app.hardware_specs or {}),  # 新しいディクショナリを作成
                    'development_environment': dict(app.development_environment or {}),
                    'backend': dict(app.backend or {}),
                    'architecture': dict(app.architecture or {})
                }

                # hardware_specsの特別処理
                if 'hardware_specs' in data:
                    new_hardware = data['hardware_specs']
                    # 既存の値を保持
                    hardware_data = dict(current_data['hardware_specs'])
                    
                    # 新しい値がある場合のみ更新
                    for key, value in new_hardware.items():
                        if value:  # 値が空でない場合のみ更新
                            hardware_data[key] = value
                        elif key not in hardware_data:  # キーが存在しない場合のみ空値を設定
                            hardware_data[key] = ''
                    
                    current_data['hardware_specs'] = hardware_data

                # 他のフィールドの更新（空の値で上書きしない）
                for field in ['development_environment', 'backend', 'architecture']:
                    if field in data and data[field]:
                        for key, value in data[field].items():
                            if value:  # 値が空でない場合のみ更新
                                current_data[field][key] = value

                # データを保存
                app.hardware_specs = current_data['hardware_specs']
                app.development_environment = current_data['development_environment']
                app.backend = current_data['backend']
                app.architecture = current_data['architecture']
                app.save()

                print("\n=== After Save ===")
                print(f"Saved hardware: {app.hardware_specs}")
                print(f"Saved dev env: {app.development_environment}")
                print(f"Saved backend: {app.backend}")
                print(f"Saved architecture: {app.architecture}")

                return JsonResponse({
                    'success': True,
                    'data': current_data
                })

            except json.JSONDecodeError as e:
                return JsonResponse({
                    'success': False,
                    'error': f'Invalid JSON format: {str(e)}'
                }, status=400)
                
            except Exception as e:
                print(f"\n=== Error Saving Data ===")
                print(f"Error type: {type(e)}")
                print(f"Error: {str(e)}")
                return JsonResponse({
                    'success': False,
                    'error': f'Save error: {str(e)}'
                }, status=500)

        # GETリクエストの処理（変更なし）
        context = {
            'app': app,
            'hide_navbar': True,
            'readonly': False,
            'is_edit': True,
            'BACKEND_STACK': BACKEND_STACK,
            'BACKEND_PACKAGE_HINTS': BACKEND_PACKAGE_HINTS,
            # ハードウェア関連の定数
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
            # 開発環境関連の定数
            'editors': EDITORS,
            'version_control': VERSION_CONTROL,
            'ci_cd': CI_CD,
            'virtualization_tools': VIRTUALIZATION_TOOLS,
            'team_sizes': TEAM_SIZES,
            'communication_tools': COMMUNICATION_TOOLS,
            'infrastructure': INFRASTRUCTURE,
            'api_tools': API_TOOLS,
            'monitoring_tools': MONITORING_TOOLS,
            # アーキテクチャ関連の定数
            'architecture_patterns': ARCHITECTURE_PATTERNS,
            'design_patterns': DESIGN_PATTERNS,
        }
        
        # デバッグ用
        print("Context data:", {
            'hardware_specs': app.hardware_specs,
            'development_environment': app.development_environment,
            'backend': app.backend,
            'architecture': app.architecture
        })
        
        print(f"Rendering technical edit template for app: {app.title}")
        return render(request, 'apps_gallery/technical/technical_create_edit.html', context)
        
    except Exception as e:
        print(f"\n=== View Error ===")
        print(f"Error: {str(e)}")
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
    
    return render(request, 'apps_gallery/technical/technical_view_detail.html', context) 