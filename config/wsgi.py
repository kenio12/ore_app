"""
WSGI config for config project.

It exposes the WSGI callable as a module-level variable named ``application``.

For more information on this file, see
https://docs.djangoproject.com/en/5.1/howto/deployment/wsgi/
"""

import os
import sys
import logging

# ロギングの設定
logging.basicConfig(level=logging.DEBUG)
logging.debug("WSGI起動中: sys.path = %s", sys.path)

from django.core.wsgi import get_wsgi_application

os.environ.setdefault('DJANGO_SETTINGS_MODULE', 'config.settings')

# WSGI applicationを取得
try:
    application = get_wsgi_application()
    logging.debug("WSGI application取得成功")
except Exception as e:
    logging.error("WSGI application取得失敗: %s", e)
    raise

# Railwayのために$PORT環境変数を確認
port = os.environ.get('PORT', '8000')
if port:
    print(f"PORT環境変数が設定されています: {port}")
