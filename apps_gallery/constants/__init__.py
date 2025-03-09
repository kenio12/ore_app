"""
定数モジュールの初期化
"""

# 基本情報
from .app_info import (
    アプリ種類, APP_TYPES,
    APP_STATUS, PUBLISH_STATUS,
    ジャンル, GENRES,
    ジャンル表示,
    convert_genre_key,
    モデルフィールド, MODEL_FIELDS,
    アプリ設定, APP_DEFAULTS,
    タブ, TABS
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
フロントエンド言語 = frontend_constants.フロントエンド言語
フロントエンドフレームワーク = frontend_constants.フロントエンドフレームワーク
CSSフレームワーク = frontend_constants.CSSフレームワーク

# 英語の変数名も保持
FRONTEND_LANGUAGES = frontend_constants.FRONTEND_LANGUAGES
FRONTEND_FRAMEWORKS = frontend_constants.FRONTEND_FRAMEWORKS
CSS_FRAMEWORKS = frontend_constants.CSS_FRAMEWORKS
MARKUP_LANGUAGES = frontend_constants.MARKUP_LANGUAGES

# バックエンド関連
from .tech_stack import BACKEND_LANGUAGES, BACKEND_FRAMEWORKS
バックエンドスタック = {
    "languages": BACKEND_LANGUAGES,
    "frameworks": BACKEND_FRAMEWORKS
}

# 英語の変数名を追加
BACKEND_STACK = {
    "languages": BACKEND_LANGUAGES,
    "frameworks": BACKEND_FRAMEWORKS
}

# データベース関連
from . import _09_database_constants as database_constants
データベース種類 = database_constants.データベース種類
データベースホスティング = database_constants.データベースホスティング
オーアールエム = database_constants.オーアールエム

# 英語の変数名を追加
DATABASE_TYPES = データベース種類
DATABASE_HOSTING = データベースホスティング
ORMS = オーアールエム

# セキュリティ関連
from . import _10_security_constants as security_constants
認証方式 = security_constants.認証方式
セキュリティ対策 = security_constants.セキュリティ対策

# 英語の変数名を追加
AUTHENTICATION_METHODS = 認証方式
SECURITY_MEASURES = セキュリティ対策

# 開発期間関連
from . import _11_development_constants as development_constants
開発期間 = development_constants.開発期間

# 英語の変数名を追加
DEVELOPMENT_PERIODS = 開発期間

# 全ての定数をエクスポート
__all__ = [
    # アプリ情報関連
    'アプリ種類', 'APP_TYPES', 'ジャンル', 'GENRES', 'ジャンル表示', 'convert_genre_key',
    'APP_STATUS', 'PUBLISH_STATUS', 'モデルフィールド', 'MODEL_FIELDS', 'アプリ設定', 'APP_DEFAULTS',
    'タブ', 'TABS',
    
    # ハードウェア
    'DEVICE_TYPES', 'OS_TYPES', 'CPU_TYPES', 'MEMORY_SIZES',
    'STORAGE_TYPES', 'MONITOR_COUNTS', 'MONITOR_SIZES',
    'PC_TYPES', 'MAKER_EXAMPLES', 'INTERNET_TYPES',
    
    # 開発環境
    'TEAM_SIZES', 'VIRTUALIZATION_TOOLS', 'EDITORS',
    'VERSION_CONTROL', 'COMMUNICATION_TOOLS', 'INFRASTRUCTURE',
    'CI_CD', 'API_TOOLS', 'MONITORING_TOOLS',
    
    # フロントエンド関連
    'フロントエンド言語', 'フロントエンドフレームワーク', 'CSSフレームワーク',
    'FRONTEND_LANGUAGES', 'FRONTEND_FRAMEWORKS', 'CSS_FRAMEWORKS', 'MARKUP_LANGUAGES',
    
    # バックエンド関連
    'BACKEND_LANGUAGES', 'BACKEND_FRAMEWORKS', 'バックエンドスタック', 'BACKEND_STACK',
    
    # データベース関連
    'データベース種類', 'データベースホスティング', 'オーアールエム',
    'DATABASE_TYPES', 'DATABASE_HOSTING', 'ORMS',
    
    # セキュリティ関連
    '認証方式', 'セキュリティ対策',
    'AUTHENTICATION_METHODS', 'SECURITY_MEASURES',
    
    # 開発期間関連
    '開発期間', 'DEVELOPMENT_PERIODS'
]
