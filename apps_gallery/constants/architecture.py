"""
アーキテクチャとセキュリティに関する定数
"""

# アーキテクチャパターン
ARCHITECTURE_PATTERNS = {
    'mvc': 'MVC',
    'mvvm': 'MVVM',
    'clean': 'クリーンアーキテクチャ',
    'ddd': 'ドメイン駆動設計',
    'layered': 'レイヤードアーキテクチャ',
    'microservices': 'マイクロサービス',
    'serverless': 'サーバーレス',
    'event_driven': 'イベント駆動',
    'other': 'その他'
}

# デザインパターン
DESIGN_PATTERNS = {
    'singleton': 'Singleton',
    'factory': 'Factory',
    'observer': 'Observer',
    'strategy': 'Strategy',
    'repository': 'Repository',
    'decorator': 'Decorator',
    'facade': 'Facade',
    'other': 'その他'
}

# アーキテクチャのヒント
ARCHITECTURE_HINTS = [
    'architecture_structure': '全体的なアーキテクチャの構成',
    'adoption_reason': '採用した理由や背景',
    'design_patterns': '特徴的な設計パターン',
    'module_dependencies': 'モジュール間の依存関係',
    'data_flow': 'データフローの概要'
]

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