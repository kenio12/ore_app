"""
10 セキュリティ関連の定数
"""

# 認証方式
AUTHENTICATION_METHODS = {
    'jwt': 'JWT',
    'oauth': 'OAuth',
    'session': 'セッション認証',
    'token': 'トークン認証',
    'basic': 'Basic認証',
    'other': 'その他'
}

# セキュリティ対策
SECURITY_MEASURES = {
    'csrf': 'CSRF対策',
    'xss': 'XSS対策',
    'sql_injection': 'SQLインジェクション対策',
    'rate_limiting': 'レートリミット',
    'encryption': 'データ暗号化',
    'firewall': 'ファイアウォール',
    'other': 'その他'
} 