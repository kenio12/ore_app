from fastapi import APIRouter, Depends, HTTPException, status
from motor.motor_asyncio import AsyncIOMotorDatabase
from app.database import get_db
from app.models.user import User
from app.schemas.user import UserResponse
from typing import List
from app.utils.auth import get_current_user

router = APIRouter(
    tags=["users"]
)

@router.get("/", response_model=List[UserResponse])
async def get_users(db: AsyncIOMotorDatabase = Depends(get_db)):
    users = await db.users.find().to_list(1000)
    return users

@router.get("/{user_id}", response_model=UserResponse)
async def get_user(user_id: str, db: AsyncIOMotorDatabase = Depends(get_db)):
    if user := await db.users.find_one({"_id": user_id}):
        return user
    raise HTTPException(
        status_code=status.HTTP_404_NOT_FOUND,
        detail="User not found"
    )

@router.get("/api/users/me")
async def get_current_user_info(current_user: dict = Depends(get_current_user)):
    return current_user