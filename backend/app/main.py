import logging

logging.basicConfig(level=logging.DEBUG)

from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware
from fastapi.staticfiles import StaticFiles
from app.routers import auth, upload, users, apps
from app.core.config import settings
import uvicorn

app = FastAPI(
    title="DevShare API",
    debug=True  # デバッグモードを有効化
)

# CORSの設定を修正
app.add_middleware(
    CORSMiddleware,
    allow_origins=["http://localhost:5173"],  # フロントエンドのURL
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# ルーターの追加
app.include_router(auth.router, prefix="/api/auth", tags=["auth"])
app.include_router(users.router, prefix="/api/users")
app.include_router(apps.router, prefix="/api/apps", tags=["apps"])

# 静的ファイル用のディレクトリをマウント
app.mount("/uploads", StaticFiles(directory="uploads"), name="uploads")

# アップロードルーターを追加
app.include_router(upload.router)

@app.get("/")
async def root():
    return {"message": "Welcome to DevShare API"} 

if __name__ == "__main__":
    uvicorn.run(app, host="0.0.0.0", port=8000, debug=True) 