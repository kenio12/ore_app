"""
アーキテクチャに関する定数
"""

# アーキテクチャパターン
ARCHITECTURE_PATTERNS = {
    # 基本構造
    'monolithic': 'モノリシック（一枚岩型）',
    'modular_monolithic': 'モジュラーモノリシック（機能分割型一枚岩）',
    'modular': 'モジュラー（機能分割型）',
    'layered': 'レイヤード（層状型）',
    
    # クリーン系
    'clean': 'クリーンアーキテクチャ',
    'onion': 'オニオンアーキテクチャ',
    'hexagonal': 'ヘキサゴナルアーキテクチャ',
    
    # 分散系
    'microservices': 'マイクロサービス',
    'serverless': 'サーバーレス',
    
    # その他
    'none': '特に意識していない',
    'other': 'その他'
}

# デザインパターン
DESIGN_PATTERNS = {
    # 基本パターン
    'none': '特に意識していない',
    'mvc_based': 'MVCベース（MVC/MVT/MVVMなど）',
    'layered': 'レイヤードパターン（層状設計）',
    
    # GoFパターン
    'singleton': 'Singleton（シングルトン）',
    'factory': 'Factory Method（ファクトリーメソッド）',
    'repository': 'Repository（リポジトリ）',
    'strategy': 'Strategy（ストラテジー）',
    'observer': 'Observer（オブザーバー）',
    'decorator': 'Decorator（デコレータ）',
    'facade': 'Facade（ファサード）',
    
    # 状態管理
    'bloc': 'BLoC（Business Logic Component）',
    'redux': 'Reduxパターン',
    'provider': 'Providerパターン',
    
    # その他
    'ddd': 'ドメイン駆動設計',
    'event_driven': 'イベント駆動',
    'other': 'その他'
}

# アーキテクチャの説明項目
ARCHITECTURE_HINTS = {
    'basic_structure': '基本構造',
    'data_flow': 'データの流れ',
    'folder_structure': 'フォルダ構成',
    'reason': '採用した理由'
}

# セキュリティ対策
SECURITY_MEASURES = {
    'auth_jwt': 'JWT認証',
    'auth_session': 'セッション認証',
    'auth_oauth': 'OAuth/SSO',
    'xss': 'XSS対策',
    'csrf': 'CSRF対策',
    'sql_injection': 'SQLインジェクション対策',
    'rate_limit': 'レート制限',
    'firewall': 'ファイアウォール',
    'security_headers': 'セキュリティヘッダー',
    'rbac': 'ロールベースアクセス制御',
    'encryption': 'データ暗号化',
    'other': 'その他'
}

# テストツール
TESTING_TOOLS = {
    'unit_test': 'ユニットテスト',
    'integration_test': '統合テスト',
    'e2e_test': 'E2Eテスト',
    'phpunit': 'PHPUnit',
    'jest': 'Jest',
    'cypress': 'Cypress',
    'coverage': 'カバレッジ測定',
    'other': 'その他'
}

# コード品質ツール
CODE_QUALITY_TOOLS = {
    'eslint': 'ESLint',
    'phpcs': 'PHP_CodeSniffer',
    'prettier': 'Prettier',
    'sonarqube': 'SonarQube',
    'code_review': 'コードレビュー',
    'static_analysis': '静的解析',
    'other': 'その他'
} 