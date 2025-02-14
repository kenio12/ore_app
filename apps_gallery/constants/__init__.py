"""
定数モジュールの初期化
"""

# 基本情報
from .app_info import (
    APP_TYPES,
    APP_STATUS,
    PUBLISH_STATUS,
    GENRES,
    MODEL_FIELDS,
    APP_DEFAULTS,
    TABS
)

# ハードウェア
from .hardware import (
    DEVICE_TYPES,
    OS_TYPES,
    CPU_TYPES,
    MEMORY_SIZES,
    STORAGE_TYPES,
    MONITOR_COUNTS,
    MONITOR_SIZES,
    PC_TYPES,
    MAKER_EXAMPLES,
    INTERNET_TYPES
)

# 開発環境
from .development import (
    TEAM_SIZES,
    VIRTUALIZATION_TOOLS,
    EDITORS,
    VERSION_CONTROL,
    COMMUNICATION_TOOLS,
    INFRASTRUCTURE,
    CI_CD,
    API_TOOLS,
    MONITORING_TOOLS
)

# フロントエンド関連
from .frontend_constants import (
    FRONTEND_LANGUAGES,
    FRONTEND_FRAMEWORKS,
    CSS_FRAMEWORKS
)

# バックエンド関連
from .backend_constants import (
    BACKEND_STACK,
    BACKEND_PACKAGE_HINTS
)

# データベース関連
from .database_constants import (
    DATABASE_TYPES,
    ORMS
)

# インフラ関連
from .infrastructure_constants import (
    CACHES,
    DATABASE_HOSTING
)

# アーキテクチャとセキュリティ
from .architecture import (
    ARCHITECTURE_PATTERNS,
    DESIGN_PATTERNS,
    ARCHITECTURE_HINTS,
    SECURITY_MEASURES,
    TESTING_TOOLS,
    CODE_QUALITY_TOOLS
)

# 開発期間
from .development_period import (
    DEVELOPMENT_PERIODS,
    DEVELOPMENT_PHASES
)

# クラウドストレージ
from .cloud_storage import (
    CLOUDINARY_TRANSFORMATION,
    CLOUDINARY_FOLDERS,
    LOG_LEVELS
)

# 全ての定数をエクスポート
__all__ = [
    # アプリ基本情報
    'APP_TYPES', 'APP_STATUS', 'PUBLISH_STATUS', 'GENRES', 'MODEL_FIELDS',
    'APP_DEFAULTS', 'TABS',
    
    # ハードウェア
    'DEVICE_TYPES', 'OS_TYPES', 'CPU_TYPES', 'MEMORY_SIZES',
    'STORAGE_TYPES', 'MONITOR_COUNTS', 'MONITOR_SIZES',
    'PC_TYPES', 'MAKER_EXAMPLES', 'INTERNET_TYPES',
    
    # 開発環境
    'TEAM_SIZES', 'VIRTUALIZATION_TOOLS', 'EDITORS',
    'VERSION_CONTROL', 'COMMUNICATION_TOOLS', 'INFRASTRUCTURE',
    'CI_CD', 'API_TOOLS', 'MONITORING_TOOLS',
    
    # フロントエンド関連
    'FRONTEND_LANGUAGES', 'FRONTEND_FRAMEWORKS', 'CSS_FRAMEWORKS',
    
    # バックエンド関連
    'BACKEND_STACK',
    'BACKEND_PACKAGE_HINTS',
    
    # データベース関連
    'DATABASE_TYPES', 'ORMS',
    
    # インフラ関連
    'CACHES', 'DATABASE_HOSTING',
    
    # アーキテクチャとセキュリティ
    'ARCHITECTURE_PATTERNS', 'DESIGN_PATTERNS', 'ARCHITECTURE_HINTS',
    'SECURITY_MEASURES', 'TESTING_TOOLS', 'CODE_QUALITY_TOOLS',
    
    # 開発期間
    'DEVELOPMENT_PERIODS', 'DEVELOPMENT_PHASES',
    
    # クラウドストレージ
    'CLOUDINARY_TRANSFORMATION', 'CLOUDINARY_FOLDERS', 'LOG_LEVELS'
]
