from fastapi import APIRouter, Depends, HTTPException
from typing import List
from ..database import get_db
from ..models.app import AppCreate, AppGenre
from datetime import datetime
from bson import ObjectId
from ..utils.auth import get_current_user  # 追加：認証用
import json
from bson.errors import InvalidId

router = APIRouter()

@router.post("/")
async def create_app(
    app_data: AppCreate, 
    current_user: dict = Depends(get_current_user),  # 認証必須
    db = Depends(get_db)
):
    try:
        if not current_user:  # ユーザーが認証されているか確認
            raise HTTPException(
                status_code=401,
                detail="Not authenticated"
            )
        
        # Enumの値を全て文字列に変換
        app_dict = app_data.dict()
        app_dict["genres"] = [genre.value for genre in app_data.genres]
        app_dict["app_type"] = app_data.app_type.value  # AppTypeを文字列に
        app_dict["status"] = app_data.status.value      # AppStatusを文字列に
        
        print("Creating new app:", app_dict)
        
        app_dict["user_id"] = current_user["_id"]
        app_dict["user"] = {
            "_id": current_user["_id"],
            "username": current_user["username"],
            "display_name": current_user.get("display_name")
        }
        app_dict["created_at"] = datetime.utcnow()
        
        # MongoDBに保存
        result = await db["apps"].insert_one(app_dict)
        
        # 保存したドキュメントを取得
        created_app = await db["apps"].find_one({"_id": result.inserted_id})
        
        if created_app:
            created_app["_id"] = str(created_app["_id"])
            print("App created successfully:", created_app)
            return created_app
            
    except Exception as e:
        print("Error creating app:", str(e))
        raise HTTPException(status_code=500, detail=str(e))

@router.get("/")
async def get_apps(limit: int = 100, db = Depends(get_db)):
    try:
        # created_atで降順（新しい順）にソート
        apps = await db["apps"].find().sort("created_at", -1).to_list(length=limit)
        
        # 2. 有効なユーザーIDだけを集める
        valid_user_ids = set()
        for app in apps:
            try:
                # 文字列がObjectIDとして有効かチェック
                if ObjectId.is_valid(app.get("user_id", "")):
                    valid_user_ids.add(ObjectId(app["user_id"]))
            except (InvalidId, KeyError):
                continue
        
        # 3. ユーザー情報を一括取得
        users = await db["users"].find({"_id": {"$in": list(valid_user_ids)}}).to_list(length=None)
        users_dict = {str(user["_id"]): user for user in users}
        
        # 4. レスポンス用にデータを整形
        response_data = []
        for app in apps:
            app_data = {
                "_id": str(app["_id"]),
                "title": app.get("title", ""),
                "description": app.get("description", ""),
                "demo_url": app.get("demo_url"),
                "github_url": app.get("github_url"),
                "screenshots": app.get("screenshots", []),
                "created_at": app.get("created_at"),
                "prefix_icon": app.get("prefix_icon", "🗡️"),
                "suffix_icon": app.get("suffix_icon", "🏴‍☠️"),
                "app_type": app.get("app_type", "UNSPECIFIED"),  # genreを完全駆逐してapp_type変更！
            }
            
            # ユーザー情報を追加
            user = users_dict.get(app.get("user_id", ""))
            if user:
                app_data["user"] = {
                    "_id": str(user["_id"]),
                    "username": user.get("username", ""),
                    "display_name": user.get("display_name", user.get("username", ""))
                }
            
            response_data.append(app_data)
        
        return response_data
        
    except Exception as e:
        print("Error fetching apps:", str(e))
        raise HTTPException(status_code=500, detail=str(e))

@router.get("/{app_id}")
async def get_app(app_id: str, db = Depends(get_db)):
    try:
        app = await db["apps"].find_one({"_id": ObjectId(app_id)})
        if app is None:
            raise HTTPException(status_code=404, detail="App not found")
        
        app["_id"] = str(app["_id"])
        return app
        
    except Exception as e:
        print("Error fetching app:", str(e))
        raise HTTPException(status_code=500, detail=str(e))