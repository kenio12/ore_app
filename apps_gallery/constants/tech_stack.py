"""
技術スタック（フロントエンド、バックエンド、データベース）に関する定数
"""

# フロントエンド言語
FRONTEND_LANGUAGES = {
    'javascript': 'JavaScript',
    'typescript': 'TypeScript',
    'html': 'HTML',
    'css': 'CSS',
    'other': 'その他'
}

# フロントエンドフレームワーク
FRONTEND_FRAMEWORKS = {
    'react': 'React',
    'vue': 'Vue.js',
    'angular': 'Angular',
    'svelte': 'Svelte',
    'next': 'Next.js',
    'nuxt': 'Nuxt.js',
    'other': 'その他'
}

# CSSフレームワーク
CSS_FRAMEWORKS = {
    'tailwind': 'Tailwind CSS',
    'bootstrap': 'Bootstrap',
    'material': 'Material UI',
    'chakra': 'Chakra UI',
    'bulma': 'Bulma',
    'sass': 'Sass/SCSS',
    'other': 'その他'
}

# バックエンド言語
BACKEND_LANGUAGES = {
    'php': 'PHP',
    'python': 'Python',
    'ruby': 'Ruby',
    'java': 'Java',
    'csharp': 'C#',
    'golang': 'Go',
    'nodejs': 'Node.js',
    'other': 'その他'
}

# バックエンドフレームワーク
BACKEND_FRAMEWORKS = {
    'laravel': 'Laravel',
    'symfony': 'Symfony',
    'django': 'Django',
    'flask': 'Flask',
    'rails': 'Ruby on Rails',
    'spring': 'Spring',
    'express': 'Express',
    'dotnet': '.NET',
    'other': 'その他'
}

# データベース
DATABASE_TYPES = {
    'mysql': 'MySQL',
    'postgresql': 'PostgreSQL',
    'mongodb': 'MongoDB',
    'redis': 'Redis',
    'sqlite': 'SQLite',
    'mariadb': 'MariaDB',
    'oracle': 'Oracle',
    'mssql': 'SQL Server',
    'other': 'その他'
}

# ORM
ORMS = {
    'eloquent': 'Eloquent',
    'doctrine': 'Doctrine',
    'prisma': 'Prisma',
    'sequelize': 'Sequelize',
    'typeorm': 'TypeORM',
    'mongoose': 'Mongoose',
    'other': 'その他'
}

# キャッシュ
CACHES = {
    'redis': 'Redis',
    'memcached': 'Memcached',
    'file': 'ファイルキャッシュ',
    'database': 'データベースキャッシュ',
    'other': 'その他'
}

# データベースホスティングサービス
DATABASE_HOSTING = {
    'aws_rds': 'AWS RDS',
    'gcp_sql': 'Google Cloud SQL',
    'azure_db': 'Azure Database',
    'heroku_postgres': 'Heroku Postgres',
    'mongodb_atlas': 'MongoDB Atlas',
    'planetscale': 'PlanetScale',
    'supabase': 'Supabase',
    'other': 'その他'
}

# バックエンドパッケージのヒント
BACKEND_PACKAGE_HINTS = [
    'JWT認証',
    'ORM',
    'キャッシュ',
    'メール送信',
    '画像処理'
] 