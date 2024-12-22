from pydantic_settings import BaseSettings
import os
from fastapi_mail import ConnectionConfig

class Settings(BaseSettings):
    # プロジェクトの基本設定
    PROJECT_NAME: str = "ORE_APP"
    API_V1_STR: str = "/api/v1"
    
    # JWTの設定
    JWT_SECRET: str = os.getenv("JWT_SECRET")
    SECRET_KEY: str = os.getenv("SECRET_KEY")
    ALGORITHM: str = "HS256"
    ACCESS_TOKEN_EXPIRE_MINUTES: int = 240
    
    # データベース設定
    MONGODB_URI: str = os.getenv("MONGODB_URI")
    MONGODB_URL: str = os.getenv("MONGODB_URI")
    
    # Redis設定
    REDIS_URL: str = os.getenv("REDIS_URL", "redis://redis:6379")
    
    # メール設定
    MAIL_USERNAME: str = os.getenv("MAIL_USERNAME")
    MAIL_PASSWORD: str = os.getenv("MAIL_PASSWORD")
    MAIL_FROM: str = os.getenv("MAIL_FROM")
    MAIL_PORT: int = int(os.getenv("MAIL_PORT", "587"))
    MAIL_SERVER: str = os.getenv("MAIL_SERVER")
    
    # Cloudinary設定
    CLOUDINARY_CLOUD_NAME: str = os.getenv("CLOUDINARY_CLOUD_NAME")
    CLOUDINARY_API_KEY: str = os.getenv("CLOUDINARY_API_KEY")
    CLOUDINARY_API_SECRET: str = os.getenv("CLOUDINARY_API_SECRET")

    class Config:
        case_sensitive = True
        env_file = ".env"
        extra = "allow"

# グローバル設定オブジェクトを作成
settings = Settings()

# メール設定
MAIL_CONFIG = ConnectionConfig(
    MAIL_USERNAME=settings.MAIL_USERNAME,
    MAIL_PASSWORD=settings.MAIL_PASSWORD,
    MAIL_FROM=settings.MAIL_FROM,
    MAIL_PORT=settings.MAIL_PORT,
    MAIL_SERVER=settings.MAIL_SERVER,
    MAIL_STARTTLS=True,
    MAIL_SSL_TLS=False,
    USE_CREDENTIALS=True,
    VALIDATE_CERTS=True
) 