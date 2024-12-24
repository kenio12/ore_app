from fastapi import APIRouter, Depends, HTTPException, status
from fastapi.security import OAuth2PasswordBearer, OAuth2PasswordRequestForm
from motor.motor_asyncio import AsyncIOMotorDatabase
from jose import JWTError, jwt
from app.database import get_db
from app.core.config import settings
from app.models.user import User
from app.schemas.user import UserCreate, UserResponse
from app.utils.auth import verify_password, get_password_hash, create_access_token
from datetime import datetime, timedelta
from fastapi.responses import JSONResponse

router = APIRouter(
    prefix="/auth",
    tags=["auth"]
)

oauth2_scheme = OAuth2PasswordBearer(tokenUrl="api/auth/login")


# get_current_user 関数を追加
async def get_current_user(
    token: str = Depends(oauth2_scheme),
    db: AsyncIOMotorDatabase = Depends(get_db)
):
    credentials_exception = HTTPException(
        status_code=status.HTTP_401_UNAUTHORIZED,
        detail="Could not validate credentials",
        headers={"WWW-Authenticate": "Bearer"},
    )
    try:
        payload = jwt.decode(
            token, 
            settings.SECRET_KEY, 
            algorithms=[settings.ALGORITHM]
        )
        email: str = payload.get("sub")
        if email is None:
            raise credentials_exception
            
        # ここでデバッグ用のログを追加
        print(f"Looking for user with email: {email}")
        user = await db["users"].find_one({"email": email})
        print(f"Found user: {user}")
        
        if user is None:
            raise credentials_exception
            
        return user
        
    except JWTError:
        raise credentials_exception

async def authenticate_user(email: str, password: str, db):
    user = await db["users"].find_one({"email": email})
    if not user:
        return False
    if not verify_password(password, user["hashed_password"]):
        return False
    return user

@router.post("/register", response_model=UserResponse)
async def register(user: UserCreate, db: AsyncIOMotorDatabase = Depends(get_db)):
    try:
        # メールアドレスの重複チェック
        if await db.users.find_one({"email": user.email}):
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
        
        # 新規ユーザーの作成
        user_dict = {
            "email": user.email,
            "username": user.username,
            "hashed_password": get_password_hash(user.password),
            "is_active": True,  # 直接アクティブに
            "created_at": datetime.utcnow()
        }
        
        result = await db.users.insert_one(user_dict)
        created_user = await db.users.find_one({"_id": result.inserted_id})
        created_user["_id"] = str(created_user["_id"])
        
        return {
            "message": "Registration successful",
            "user": {
                "id": str(created_user["_id"]),
                "email": created_user["email"],
                "username": created_user["username"]
            }
        }
        
    except Exception as e:
        return JSONResponse(
            status_code=status.HTTP_500_INTERNAL_SERVER_ERROR,
            content={"detail": str(e)}
        )

@router.post("/login")
async def login(
    form_data: OAuth2PasswordRequestForm = Depends(),
    db = Depends(get_db)
):
    # ユーザー認証
    user = await authenticate_user(form_data.username, form_data.password, db)
    if not user:
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="Incorrect username or password"
        )

    # JWTトークン生成
    access_token = create_access_token(
        data={"sub": user["email"]},
        expires_delta=timedelta(minutes=30)
    )

    return {
        "access_token": access_token,
        "token_type": "bearer",
        "user": {
            "id": str(user["_id"]),
            "email": user["email"],
            "username": user["username"]
        }
    }

@router.post("/logout")
async def logout():
    # JWTの場合、サーバー側でのログアウト処理は不要
    # フロントエンドでトークンを削除するだけ
    return {"message": "Logged out successfully"}

@router.delete("/delete-account")
async def delete_account(
    current_user: dict = Depends(get_current_user),
    db: AsyncIOMotorDatabase = Depends(get_db)
):
    try:
        user_id = current_user.get("id") or current_user.get("_id")
        if not user_id:
            raise HTTPException(status_code=400, detail="User ID not found")

        from bson import ObjectId
        result = await db.users.delete_one({"_id": ObjectId(user_id)})
        
        if result.deleted_count == 0:
            raise HTTPException(status_code=404, detail="User not found")
        
        return {"message": "Account deleted successfully"}
        
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))