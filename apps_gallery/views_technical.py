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
                # POSTデータを一度だけ読み取り
                data = json.loads(request.body.decode('utf-8'))
                
                # デバッグ用
                print("\n=== Debug Info ===")
                print(f"User: {request.user}, App ID: {pk}")
                print("Content-Type:", request.content_type)
                print("Received data:", data)
                
                # CSRFトークンの検証
                if not request.META.get('HTTP_X_CSRFTOKEN'):
                    return JsonResponse({
                        'success': False,
                        'error': 'CSRF token missing'
                    }, status=403)
                
                # ハードウェアデータの処理
                hardware_data = data.get('hardware_specs', {}).copy()
                if not isinstance(hardware_data, dict):
                    hardware_data = {}
                
                # 開発環境データの処理
                dev_env_data = data.get('development_environment', {}).copy()
                
                # team_sizeを配列から単一の値に変換
                if 'team_size' in dev_env_data and isinstance(dev_env_data['team_size'], list):
                    dev_env_data['team_size'] = dev_env_data['team_size'][0] if dev_env_data['team_size'] else ''
                
                # バックエンドデータの処理
                backend_data = data.get('backend', {}).copy()
                
                # packagesの初期化と処理
                if 'packages' not in backend_data:
                    backend_data['packages'] = []
                
                if 'packages[]' in backend_data:
                    package_value = backend_data.pop('packages[]')
                    if isinstance(package_value, str):
                        if package_value not in backend_data['packages']:
                            backend_data['packages'].append(package_value)
                    elif isinstance(package_value, list):
                        for pkg in package_value:
                            if pkg not in backend_data['packages']:
                                backend_data['packages'].append(pkg)
                
                # アーキテクチャデータの処理を追加
                architecture_data = data.get('architecture', {}).copy()
                
                # データを保存
                app.hardware_specs = hardware_data
                app.development_environment = dev_env_data
                app.backend = backend_data
                app.architecture = architecture_data
                app.save()

                # デバッグ用ログ追加
                print("\n=== After Save ===")
                print("Saved hardware:", app.hardware_specs)
                print("Saved dev env:", app.development_environment)
                print("Saved backend:", app.backend)
                print("Saved architecture:", app.architecture)
                
                return JsonResponse({
                    'success': True,
                    'message': '保存しました',
                    'data': {
                        'hardware_specs': app.hardware_specs,
                        'development_environment': app.development_environment,
                        'backend': app.backend,
                        'architecture': app.architecture
                    }
                })
                
            except json.JSONDecodeError as e:
                print(f"\n=== JSON Decode Error ===")
                print(f"Error: {str(e)}")
                print(f"Raw body: {request.body.decode('utf-8')}")
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
        return render(request, 'apps_gallery/technical/edit_detail_technical.html', context)
        
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
    
    return render(request, 'apps_gallery/technical/edit_detail_technical.html', context) 