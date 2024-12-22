from pydantic import BaseModel, Field, validator
from datetime import datetime
from typing import Optional, List

class AppCreate(BaseModel):
    title: str
    description: str
    github_url: str | None = None
    demo_url: str | None = None
    app_type: str
    prefix_icon: str | None = None
    suffix_icon: str | None = None
    screenshots: list[str] = []

    @validator('screenshots')
    def validate_screenshots(cls, v):
        if len(v) > 3:
            raise ValueError('スクリーンショットは最大3枚までです')
        if len(v) == 0:
            raise ValueError('スクリーンショットは最低1枚必要です')
        return v

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
                "demo_url": "https://demo.example.com",
                "github_url": "https://github.com/example/myapp",
                "screenshots": [
                    "https://example.com/screenshots/1.jpg",
                    "https://example.com/screenshots/2.jpg"
                ]
            }
        }