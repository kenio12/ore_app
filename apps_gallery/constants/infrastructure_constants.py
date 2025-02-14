"""
インフラ関連の定数
"""

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