import logging

logging.basicConfig(level=logging.DEBUG)

from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware
from fastapi.staticfiles import StaticFiles
from app.routers import auth, upload, users, apps
from app.core.config import settings
import uvicorn
from starlette.middleware.sessions import SessionMiddleware
import os

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

# セッションミドルウェアの設定
app.add_middleware(
    SessionMiddleware,
    secret_key="your-super-secret-key",
    session_cookie="session",
    max_age=24 * 60 * 60,
    same_site="lax",
    https_only=False
)

# ルーターの設定
app.include_router(auth.router, prefix="/api")
app.include_router(users.router, prefix="/api")
app.include_router(apps.router, prefix="/api/apps")

# 静的ファイル用のディレクトリをマウント
app.mount("/uploads", StaticFiles(directory="uploads"), name="uploads")

# アップロードルーターを追加
app.include_router(upload.router)

@app.get("/")
async def root():
    return {"message": "Welcome to DevShare API"} 

@app.get("/api/healthcheck")
async def healthcheck():
    return {"status": "ok"}

if __name__ == "__main__":
    uvicorn.run(app, host="0.0.0.0", port=8000, debug=True) 