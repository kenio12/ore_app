"""
バックエンド関連の定数
"""

# 言語とフレームワークの関連付け
BACKEND_STACK = {
    'python': {
        'name': 'Python',
        'frameworks': {
            'django': 'Django',
            'flask': 'Flask',
            'fastapi': 'FastAPI',
            'other': 'その他'
        }
    },
    'php': {
        'name': 'PHP',
        'frameworks': {
            'laravel': 'Laravel',
            'symfony': 'Symfony',
            'cakephp': 'CakePHP',
            'other': 'その他'
        }
    },
    'nodejs': {
        'name': 'Node.js',
        'frameworks': {
            'express': 'Express',
            'nestjs': 'NestJS',
            'fastify': 'Fastify',
            'other': 'その他'
        }
    },
    'ruby': {
        'name': 'Ruby',
        'frameworks': {
            'rails': 'Ruby on Rails',
            'sinatra': 'Sinatra',
            'other': 'その他'
        }
    },
    'java': {
        'name': 'Java',
        'frameworks': {
            'spring': 'Spring',
            'springboot': 'Spring Boot',
            'other': 'その他'
        }
    },
    'csharp': {
        'name': 'C#',
        'frameworks': {
            'dotnet': '.NET',
            'aspnet': 'ASP.NET',
            'other': 'その他'
        }
    },
    'golang': {
        'name': 'Go',
        'frameworks': {
            'gin': 'Gin',
            'echo': 'Echo',
            'other': 'その他'
        }
    },
    'other': {
        'name': 'その他',
        'frameworks': {
            'other': 'その他'
        }
    }
}

# バックエンドパッケージのヒント
BACKEND_PACKAGE_HINTS = [
    'JWT認証',
    'ORM',
    'キャッシュ',
    'メール送信',
    '画像処理',
    'WebSocket',
    'GraphQL',
    'REST API',
    'タスクキュー'
] 