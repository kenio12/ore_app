import os
from dotenv import load_dotenv
import cloudinary
import cloudinary.uploader

# .envファイルを読み込む
load_dotenv()

# Cloudinaryの設定を表示
print("CLOUDINARY_CLOUD_NAME:", os.getenv('CLOUDINARY_CLOUD_NAME'))
print("CLOUDINARY_API_KEY:", os.getenv('CLOUDINARY_API_KEY'))
print("CLOUDINARY_API_SECRET:", os.getenv('CLOUDINARY_API_SECRET'))

# Cloudinaryの設定
cloudinary.config(
    cloud_name=os.getenv('CLOUDINARY_CLOUD_NAME'),
    api_key=os.getenv('CLOUDINARY_API_KEY'),
    api_secret=os.getenv('CLOUDINARY_API_SECRET'),
    secure=True
)

# Cloudinaryの設定を確認
print("Cloudinary設定:", cloudinary.config().cloud_name)

# テスト用のアップロード
try:
    # テキストファイルを作成
    with open('test.txt', 'w') as f:
        f.write('テスト')
    
    # アップロード
    with open('test.txt', 'rb') as f:
        result = cloudinary.uploader.upload(
            f,
            folder="test",
            resource_type="raw"
        )
    
    print("アップロード成功:", result['secure_url'])
except Exception as e:
    print("アップロードエラー:", str(e)) 