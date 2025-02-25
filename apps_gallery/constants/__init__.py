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
    MONITORING_TOOLS,
)

# フロントエンド関連
from . import _08_frontend_constants as frontend_constants
FRONTEND_LANGUAGES = frontend_constants.FRONTEND_LANGUAGES
FRONTEND_FRAMEWORKS = frontend_constants.FRONTEND_FRAMEWORKS
CSS_FRAMEWORKS = frontend_constants.CSS_FRAMEWORKS

# データベース関連
from . import _09_database_constants as database_constants
DATABASE_TYPES = database_constants.DATABASE_TYPES
DATABASE_HOSTING = database_constants.DATABASE_HOSTING
ORMS = database_constants.ORMS

# セキュリティ関連
from . import _10_security_constants as security_constants
AUTHENTICATION_METHODS = security_constants.AUTHENTICATION_METHODS
SECURITY_MEASURES = security_constants.SECURITY_MEASURES

# 開発期間関連
from . import _11_development_constants as development_constants
DEVELOPMENT_PERIODS = development_constants.DEVELOPMENT_PERIODS

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
    
    # データベース関連
    'DATABASE_TYPES', 'DATABASE_HOSTING', 'ORMS',
    
    # セキュリティ関連
    'AUTHENTICATION_METHODS', 'SECURITY_MEASURES',
    
    # 開発期間関連
    'DEVELOPMENT_PERIODS'
]
