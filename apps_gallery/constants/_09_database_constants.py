"""
09 データベース関連の定数
"""

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