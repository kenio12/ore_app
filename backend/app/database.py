from motor.motor_asyncio import AsyncIOMotorClient
from pymongo.server_api import ServerApi
from app.core.config import settings

# MongoDBクライアントの設定
async def get_mongodb_client():
    client = AsyncIOMotorClient(
        settings.MONGODB_URI,
        server_api=ServerApi('1')
    )
    try:
        await client.admin.command('ping')
        print("MongoDB接続OK!")
        return client
    except Exception as e:
        print(f"MongoDB接続エラー: {e}")
        raise e

# データベースとコレクションの取得
async def get_db():
    client = await get_mongodb_client()
    return client.ore_app_db

# 必要に応じてコレクション（テーブルみたいなもの）を取得する関数も用意
async def get_collection(collection_name: str):
    db = await get_db()
    return db[collection_name]
