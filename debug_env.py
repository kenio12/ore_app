import os
from dotenv import load_dotenv

# .envファイルを読み込む
load_dotenv()

# Cloudinaryの設定を表示
print("CLOUDINARY_CLOUD_NAME:", os.getenv('CLOUDINARY_CLOUD_NAME'))
print("CLOUDINARY_API_KEY:", os.getenv('CLOUDINARY_API_KEY'))
print("CLOUDINARY_API_SECRET:", os.getenv('CLOUDINARY_API_SECRET'))

# 他の環境変数も表示
print("DEBUG:", os.getenv('DEBUG'))
print("DJANGO_SETTINGS_MODULE:", os.getenv('DJANGO_SETTINGS_MODULE')) 