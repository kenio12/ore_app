"""
クラウドストレージ関連の定数
"""

# Cloudinary変換設定
CLOUDINARY_TRANSFORMATION = {
    'quality': 'auto:eco',      # 最高の圧縮率
    'fetch_format': 'auto',     # 最適なフォーマットに自動変換
    'width': 800,               # 最大幅を制限
    'crop': 'limit',            # アスペクト比を保持しながらリサイズ
    'compression': 'low'        # 低圧縮（画質優先）
}

# Cloudinaryフォルダ構造
CLOUDINARY_FOLDERS = {
    'screenshots': 'ore_app/screenshots',
    'avatars': 'ore_app/avatars',
    'temp': 'ore_app/temp'
}

# ログレベル
LOG_LEVELS = {
    'debug': 'debug',
    'info': 'info',
    'warning': 'warning',
    'error': 'error'
} 