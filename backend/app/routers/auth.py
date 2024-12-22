from fastapi import APIRouter, Depends, HTTPException, status, Response, Cookie
from fastapi.security import OAuth2PasswordBearer, OAuth2PasswordRequestForm
from motor.motor_asyncio import AsyncIOMotorDatabase
from app.database import get_db
from app.models.user import User
from app.schemas.user import UserCreate, UserResponse
from app.utils.auth import verify_password, get_password_hash, create_access_token, create_verification_token
from app.utils.email import send_verification_email
from jose import jwt
from jose import JWTError
from app.core.config import settings
from datetime import datetime
from fastapi.responses import JSONResponse
from ..utils.auth import get_current_user
from typing import Optional
from ..redis_client import redis_client
import uuid

router = APIRouter(
    prefix="/auth",
    tags=["auth"]
)
oauth2_scheme = OAuth2PasswordBearer(tokenUrl="api/auth/login")

async def create_session(user_id: str) -> str:
    session_id = str(uuid.uuid4())
    await redis_client.set(f"session:{session_id}", user_id, ex=1800)
    return session_id

@router.post("/register", response_model=UserResponse)
async def register(user: UserCreate, response: Response, db: AsyncIOMotorDatabase = Depends(get_db)):
    try:
        print("===== Registration Debug =====")
        print(f"MongoDB URI: {settings.MONGODB_URI}")
        print(f"Database instance: {db}")
        print(f"Database name: {db.name}")
        print(f"Collections: {await db.list_collection_names()}")
        
        # メールアドレスの重複チェック
        existing_user = await db.users.find_one({"email": user.email})
        print(f"Existing user check: {existing_user}")
        
        if existing_user:
            return JSONResponse(
                status_code=status.HTTP_400_BAD_REQUEST,
                content={"detail": "Email already registered"}
            )
        
        # ユーザー名の重複チェック
        if await db.users.find_one({"username": user.username}):
            return JSONResponse(
                status_code=status.HTTP_400_BAD_REQUEST,
                content={"detail": "Username already taken"}
            )
        
        # 確認トークンを生成
        verification_token = create_verification_token(user.email)
        
        # 新規ユーザーの作成
        user_dict = {
            "email": user.email,
            "username": user.username,
            "hashed_password": get_password_hash(user.password),
            "is_active": False,  # アカウントは非アクティブ
            "is_verified": False,  # メール未確認
            "verification_token": verification_token,
            "created_at": datetime.utcnow()
        }
        
        result = await db.users.insert_one(user_dict)
        created_user = await db.users.find_one({"_id": result.inserted_id})
        created_user["_id"] = str(created_user["_id"])
        
        # 確認メールの送信
        print(f"Sending verification email for user: {user.email}")
        try:
            await send_verification_email(user.email, user.username, verification_token)
            print("Verification email sent successfully")
        except Exception as e:
            print(f"Error sending verification email: {str(e)}")
            # メール送信に失敗した場合、作成したユーザーを削除
            await db.users.delete_one({"_id": result.inserted_id})
            raise HTTPException(
                status_code=status.HTTP_500_INTERNAL_SERVER_ERROR,
                detail="Failed to send verification email"
            )
        
        # セッションは作成しない（メール確認後にログインさせる）
        return {
            "message": "Registration successful. Please check your email to verify your account.",
            "user": {
                "id": str(created_user["_id"]),
                "email": created_user["email"],
                "username": created_user["username"]
            }
        }
        
    except Exception as e:
        print(f"Registration error: {str(e)}")
        return JSONResponse(
            status_code=status.HTTP_500_INTERNAL_SERVER_ERROR,
            content={"detail": str(e)}
        )

@router.post("/login")
async def login(
    form_data: OAuth2PasswordRequestForm = Depends(),
    db: AsyncIOMotorDatabase = Depends(get_db)
):
    # ユーザーの検索
    user = await db.users.find_one({"email": form_data.username})
    
    # ユーザーが存在しないかパスワードが間違っている場合
    if not user or not verify_password(form_data.password, user["hashed_password"]):
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="Incorrect email or password"
        )
    
    # メール認証が完了していない場合
    if not user.get("is_verified", False):
        raise HTTPException(
            status_code=status.HTTP_403_FORBIDDEN,
            detail="Please verify your email before logging in"
        )
    
    # アカウントが有効でない場合
    if not user.get("is_active", False):
        raise HTTPException(
            status_code=status.HTTP_403_FORBIDDEN,
            detail="Account is not active"
        )
    
    # JWTトークンの生成
    access_token = create_access_token(data={"sub": user["email"]})
    return {"access_token": access_token, "token_type": "bearer"}

@router.post("/verify-email/{token}")
async def verify_email(token: str, response: Response, db: AsyncIOMotorDatabase = Depends(get_db)):
    try:
        # トークンの検証
        payload = jwt.decode(token, settings.SECRET_KEY, algorithms=[settings.ALGORITHM])
        email = payload.get("sub")
        
        # ユーザーの更新とログイン情報の取得
        user = await db.users.find_one_and_update(
            {"email": email},
            {
                "$set": {
                    "is_active": True,
                    "is_verified": True,
                    "last_login": datetime.utcnow()  # ログイン時刻を更新
                }
            },
            return_document=True
        )
        
        if not user:
            raise HTTPException(
                status_code=status.HTTP_400_BAD_REQUEST,
                detail="User not found"
            )
        
        # セッションを作成
        session_id = await create_session(str(user["_id"]))
        
        # Cookieを設定
        response.set_cookie(
            key="session_id",
            value=session_id,
            httponly=True,
            max_age=1800,
            samesite="lax"
        )
        
        # JWTトークンを生成
        access_token = create_access_token(data={"sub": email})
        
        # ユーザー情報を整形
        user_response = {
            "id": str(user["_id"]),
            "email": user["email"],
            "username": user["username"],
            "is_active": user["is_active"],
            "is_verified": user["is_verified"]
        }
        
        return {
            "message": "Email verified successfully",
            "access_token": access_token,
            "token_type": "bearer",
            "user": user_response
        }
        
    except JWTError:
        raise HTTPException(
            status_code=status.HTTP_400_BAD_REQUEST,
            detail="Invalid or expired verification token"
        )

@router.post("/forgot-password")
async def forgot_password(email: str, db: AsyncIOMotorDatabase = Depends(get_db)):
    # ユーザーの検索
    user = await db.users.find_one({"email": email})
    if user:
        # パスワードリセットメールを送信
        await send_password_reset_email(user["email"])
    return {"message": "If an account exists, a password reset link has been sent"}

@router.post("/logout")
async def logout(
    response: Response,
    session_id: Optional[str] = Cookie(None)
):
    try:
        # セッションIDがあればRedisから削除
        if session_id:
            await redis_client.delete(f"session:{session_id}")
        
        # クッキーを削除
        response.delete_cookie(
            key="session_id",
            path="/",
            secure=True,
            httponly=True,
            samesite="lax"
        )
        
        return {"message": "ログアウトに成功しました"}
        
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

@router.delete("/delete-account")
async def delete_account(
    response: Response,
    current_user: dict = Depends(get_current_user),
    db: AsyncIOMotorDatabase = Depends(get_db)
):
    try:
        print("=== Delete Account Debug ===")
        print(f"Current user: {current_user}")
        
        # ユーザーIDを取得（ObjectIdの文字列形式）
        user_id = current_user.get("id") or current_user.get("_id")
        print(f"User ID: {user_id}")
        
        if not user_id:
            raise HTTPException(status_code=400, detail="ユーザーIDが見つかりません")

        # ユーザーのデータを削除
        from bson import ObjectId
        result = await db.users.delete_one({"_id": ObjectId(user_id)})
        print(f"Delete result: {result.deleted_count}")
        
        if result.deleted_count == 0:
            raise HTTPException(status_code=404, detail="ユーザーが見つかりません")

        # セッションを削除
        session_id = current_user.get("session_id")
        if session_id:
            await redis_client.delete(f"session:{session_id}")
        
        # クッキーを削除
        response.delete_cookie(
            key="session_id",
            path="/",
            secure=True,
            httponly=True,
            samesite="lax"
        )
        
        return {"message": "アカウントが正常に削除されました"}
        
    except Exception as e:
        print(f"Error deleting account: {str(e)}")
        raise HTTPException(status_code=500, detail=str(e))

@router.get("/verify-session")
async def verify_session(current_user: dict = Depends(get_current_user)):
    try:
        return {
            "message": "Session is valid",
            "user": current_user
        }
    except Exception as e:
        raise HTTPException(status_code=401, detail="Invalid session")