from enum import Enum
from pydantic import BaseModel, Field, validator
from datetime import datetime
from typing import Optional, List

class AppType(Enum):
    UNSPECIFIED = "UNSPECIFIED"
    WEB_APP = "WEB_APP"
    MOBILE_APP = "MOBILE_APP"
    DESKTOP_APP = "DESKTOP_APP"
    CLI_TOOL = "CLI_TOOL"
    GAME = "GAME"
    OTHER = "OTHER"

class AppStatus(Enum):
    IN_DEVELOPMENT = "IN_DEVELOPMENT"  # 開発中 🚧
    COMPLETED = "COMPLETED"            # 完成済 ✨

class HostingService(Enum):
    # 一般的なホスティング
    RENDER = "Render"
    VERCEL = "Vercel"
    NETLIFY = "Netlify"
    HEROKU = "Heroku"
    AWS = "AWS"
    GCP = "Google Cloud Platform"
    AZURE = "Azure"
    
    # データベースホスティング
    MONGODB_ATLAS = "MongoDB Atlas"
    SUPABASE = "Supabase"
    FIREBASE = "Firebase"
    PLANETSCALE = "PlanetScale"
    
    # 画像ストレージ
    CLOUDINARY = "Cloudinary"
    AWS_S3 = "AWS S3"
    FIREBASE_STORAGE = "Firebase Storage"
    
    # その他
    GITHUB_PAGES = "GitHub Pages"
    CUSTOM = "Custom"
    OTHER = "Other"

class TechStack(BaseModel):
    language: str
    framework: str
    hosting: HostingService
    custom_hosting: str | None = None  # カスタムのホスティング名
    reason: str

    @validator('custom_hosting')
    def validate_custom_hosting(cls, v, values):
        if values.get('hosting') == HostingService.CUSTOM and not v:
            raise ValueError('カスタムホスティングを選択した場合は、名前を入力してください')
        return v

class OperatingSystem(Enum):
    WINDOWS = "Windows"
    MACOS = "macOS"
    LINUX = "Linux"
    CHROME_OS = "Chrome OS"

class DevEnvironment(BaseModel):
    os: list[OperatingSystem] = []
    custom_os: list[str] = []  # カスタムOS名のリスト
    local_env: str | None = None
    services: list[str] = []
    ports: dict = Field(default_factory=dict)
    required_memory: str | None = None
    required_storage: str | None = None

    class Config:
        json_schema_extra = {
            "example": {
                "os": ["Windows", "macOS"],
                "custom_os": ["FreeBSD"],  # カスタムOS例
                "local_env": "Docker Compose",
                "services": ["web", "api", "redis"],
                "ports": {
                    "web": 5173,
                    "api": 8000,
                    "redis": 6379
                },
                "required_memory": "8GB以上推奨",
                "required_storage": "10GB以上必要"
            }
        }
        json_encoders = {
            OperatingSystem: lambda v: v.value
        }

class Infrastructure(BaseModel):
    image_storage: HostingService | None = None
    dev_environment: DevEnvironment = Field(default_factory=DevEnvironment)
    ci_cd: str | None = None
    uses_docker: bool = False
    docker_compose: bool = False
    container_services: list[str] = []

    class Config:
        json_schema_extra = {
            "example": {
                "image_storage": "Cloudinary",
                "dev_environment": {
                    "os": ["Windows", "macOS", "Linux", "Chrome OS"],
                    "local_env": "Docker Compose",
                    "services": ["web", "api", "redis"],
                    "ports": {
                        "web": 5173,
                        "api": 8000,
                        "redis": 6379
                    },
                    "required_memory": "8GB以上推奨",
                    "required_storage": "10GB以上必要"
                },
                "uses_docker": True,
                "docker_compose": True,
                "container_services": ["web", "api", "redis"]
            }
        }

class AppGenre(Enum):
    # 生活・実用
    PRODUCTIVITY = "生産性・仕事効率化"
    LIFESTYLE = "ライフスタイル"
    HEALTH = "健康・フィットネス"
    FINANCE = "金融・家計"
    
    # 趣味・エンタメ
    ENTERTAINMENT = "エンターテイメント"
    GAME = "ゲーム"
    MUSIC = "音楽"
    ART = "アート・創作"
    PHOTO_VIDEO = "写真・動画"
    
    # 学習・教育
    EDUCATION = "教育"
    LANGUAGE = "語学"
    PROGRAMMING = "プログラミング"
    REFERENCE = "参考書・辞書"
    
    # コミュニケーション
    SOCIAL = "ソーシャル"
    CHAT = "チャット・メッセージ"
    COMMUNITY = "コミュニティ"
    
    # ビジネス
    BUSINESS = "ビジネス"
    MARKETING = "マーケティング"
    ANALYTICS = "分析・統計"
    
    # その他
    UTILITY = "ユーティリティ"
    CUSTOM = "その他"

class ReactionType(Enum):
    # 一般的なリアクション
    LIKE = "👍 いいね！"
    AWESOME = "🔥 すごい！"
    
    # 応援・共感系
    FIGHT = "💪 ファイト！"
    SUPPORT = "🤝 一緒に頑張ろう！"
    UNDERSTAND = "😊 わかる！"
    
    # 技術系
    TECH = "💻 技術力高い！"
    IDEA = "💡 アイデアいい！"
    
    # その他
    CUSTOM = "その他"

class DiaryReaction(BaseModel):
    type: ReactionType
    custom_message: str | None = None  # カスタムリアクション用
    user_id: str
    created_at: datetime = Field(default_factory=datetime.utcnow)

    @validator('custom_message')
    def validate_custom_message(cls, v, values):
        if values.get('type') == ReactionType.CUSTOM and not v:
            raise ValueError('カスタムリアクションの場合はメッセージを入力してください')
        return v

class DevDiaryEntry(BaseModel):
    date: datetime = Field(default_factory=datetime.utcnow)
    content: str
    progress_rate: int | None = Field(None, ge=0, le=100)
    mood: str | None = None
    todos: list[str] = []
    reactions: list[DiaryReaction] = Field(default_factory=list)  # リアクション追加

    class Config:
        json_schema_extra = {
            "example": {
                "date": "2024-03-20T15:30:00",
                "content": "今日はログイン機能を実装した！",
                "progress_rate": 45,
                "mood": "😊 順調！",
                "todos": ["テストを書く", "デザインの修正"],
                "reactions": [
                    {
                        "type": "👍 いいね！",
                        "custom_message": None,
                        "user_id": "user123",
                        "created_at": "2024-03-20T15:30:00"
                    }
                ]
            }
        }

class AppCreate(BaseModel):
    title: str
    description: str
    github_url: str | None = None
    demo_url: str | None = None
    app_type: AppType
    status: AppStatus = AppStatus.IN_DEVELOPMENT
    genres: list[AppGenre] = []  # ジャンルを複数選択可能に
    custom_genres: list[str] = []  # カスタムジャンル用
    prefix_icon: str | None = None
    suffix_icon: str | None = None
    screenshots: list[str] = []
    
    # 開発日記
    dev_diary: list[DevDiaryEntry] = Field(default_factory=list)
    
    @validator('genres')
    def validate_genres(cls, v):
        if len(v) == 0:
            raise ValueError('少なくとも1つのジャンルを選択してください')
        return v

    @validator('custom_genres')
    def validate_custom_genres(cls, v, values):
        if AppGenre.CUSTOM in values.get('genres', []) and len(v) == 0:
            raise ValueError('カスタムジャンルを選択した場合は、少なくとも1つ入力してください')
        return v

    class Config:
        json_schema_extra = {
            "example": {
                "title": "My App",
                "prefix_icon": "🗡️",
                "suffix_icon": "🏴‍☠️",
                "description": "This is my awesome app",
                "status": "IN_DEVELOPMENT",
                "demo_url": "https://demo.example.com",
                "github_url": "https://github.com/example/myapp",
                "screenshots": [
                    "https://example.com/screenshots/1.jpg",
                    "https://example.com/screenshots/2.jpg"
                ],
                "frontend": {
                    "language": "TypeScript",
                    "framework": "Vue.js",
                    "hosting": "Render",
                    "reason": "モダンで使いやすいから"
                },
                "infrastructure": {
                    "image_storage": "Cloudinary",
                    "dev_environment": {
                        "os": ["Windows", "macOS", "Linux", "Chrome OS"],
                        "local_env": "Docker Compose",
                        "services": ["web", "api", "redis"],
                        "ports": {
                            "web": 5173,
                            "api": 8000,
                            "redis": 6379
                        },
                        "required_memory": "8GB以上推奨",
                        "required_storage": "10GB以上必要"
                    },
                    "uses_docker": True,
                    "docker_compose": True,
                    "container_services": ["web", "api", "redis"]
                },
                "dev_diary": [
                    {
                        "date": "2024-03-20T15:30:00",
                        "content": "今日はログイン機能を実装した！",
                        "progress_rate": 45,
                        "mood": "😊 順調！",
                        "todos": ["テストを書く", "デザインの修正"],
                        "reactions": [
                            {
                                "type": "👍 いいね！",
                                "custom_message": None,
                                "user_id": "user123",
                                "created_at": "2024-03-20T15:30:00"
                            }
                        ]
                    }
                ]
            }
        }
        json_encoders = {
            AppType: lambda v: v.value,
            AppStatus: lambda v: v.value
        }

class App(AppCreate):
    id: str = Field(alias="_id")
    created_at: datetime = Field(default_factory=datetime.utcnow)
    updated_at: Optional[datetime] = None
    user_id: str

    class Config:
        json_schema_extra = {
            "example": {
                "title": "My App",
                "prefix_icon": "🗡️",
                "suffix_icon": "🏴‍☠️",
                "description": "This is my awesome app",
                "status": "IN_DEVELOPMENT",
                "demo_url": "https://demo.example.com",
                "github_url": "https://github.com/example/myapp",
                "screenshots": [
                    "https://example.com/screenshots/1.jpg",
                    "https://example.com/screenshots/2.jpg"
                ]
            }
        }

# データベース情報も構造化
class Database(BaseModel):
    type: str
    hosting: HostingService
    custom_hosting: str | None = None
    reason: str

    @validator('custom_hosting')
    def validate_custom_hosting(cls, v, values):
        if values.get('hosting') == HostingService.CUSTOM and not v:
            raise ValueError('カスタムホスティングを選択した場合は、名前を入力してください')
        return v