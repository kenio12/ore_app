from fastapi import APIRouter, UploadFile, File, HTTPException
from typing import List
import cloudinary
import cloudinary.uploader
from ..core.config import settings
from datetime import datetime
import logging

router = APIRouter()

# デバッグ用のログを追加
print("=== Cloudinary Config Debug ===")
print(f"Cloud Name: {settings.CLOUDINARY_CLOUD_NAME}")
print(f"API Key: {settings.CLOUDINARY_API_KEY}")
print(f"API Secret: {settings.CLOUDINARY_API_SECRET}")
print("===========================")

# Cloudinaryの設定
try:
    cloudinary.config(
        cloud_name=settings.CLOUDINARY_CLOUD_NAME,
        api_key=settings.CLOUDINARY_API_KEY,
        api_secret=settings.CLOUDINARY_API_SECRET,
        secure=True
    )
    print("Cloudinary configuration successful!")
except Exception as e:
    print(f"Cloudinary configuration error: {str(e)}")
    raise

@router.post("/api/upload/screenshots", response_model=List[str])
async def upload_screenshots(files: List[UploadFile] = File(...)):
    """
    複数の画像をアップロードするエンドポイント
    """
    try:
        uploaded_urls = []
        
        for file in files:
            if not file.content_type.startswith('image/'):
                raise HTTPException(status_code=400, detail="画像ファイルのみアップロード可能です")
            
            try:
                contents = await file.read()
                timestamp = datetime.now().strftime('%Y%m%d_%H%M%S')
                
                # デバッグ用のログ
                print(f"Uploading file: {file.filename}")
                print(f"Content type: {file.content_type}")
                print(f"File size: {len(contents)} bytes")
                
                result = cloudinary.uploader.upload(
                    contents,
                    folder="makeandshowapp",
                    public_id=f"{timestamp}_{file.filename}",
                    timeout=60
                )
                
                uploaded_urls.append(result["secure_url"])
                
            except Exception as e:
                print(f"Error uploading file {file.filename}: {str(e)}")
                raise HTTPException(status_code=500, detail=f"ファイル {file.filename} のアップロード中にエラーが発生: {str(e)}")
        
        return uploaded_urls
        
    except Exception as e:
        print(f"Unexpected error: {str(e)}")
        raise HTTPException(status_code=500, detail=f"予期せぬエラーが発生: {str(e)}")