"""
Django settings for config project.

Generated by 'django-admin startproject' using Django 5.1.5.

For more information on this file, see
https://docs.djangoproject.com/en/5.1/topics/settings/

For the full list of settings and their values, see
https://docs.djangoproject.com/en/5.1/ref/settings/
"""

import os
from pathlib import Path
from dotenv import load_dotenv
import dj_database_url

# Load environment variables from .env file
load_dotenv()

# Build paths inside the project like this: BASE_DIR / 'subdir'.
BASE_DIR = Path(__file__).resolve().parent.parent


# Quick-start development settings - unsuitable for production
# See https://docs.djangoproject.com/en/5.1/howto/deployment/checklist/

# SECURITY WARNING: keep the secret key used in production secret!
SECRET_KEY = 'django-insecure-(kjbp$@^)&ft&m3h$ur9z24vfx*d326=m)dc5-c4iiz4^yh(^-'

# SECURITY WARNING: don't run with debug turned on in production!
debug_value = os.getenv('DEBUG', 'True')
DEBUG = debug_value.lower() not in ('false', '0', 'no', 'off')

# ALLOWED_HOSTSの設定
allowed_hosts_default = 'localhost,127.0.0.1,oreapp-production.up.railway.app,.railway.app'
allowed_hosts = os.getenv('ALLOWED_HOSTS', allowed_hosts_default).split(',')
django_allowed_hosts = os.getenv('DJANGO_ALLOWED_HOSTS', '').split(',')

# 両方の環境変数からホストを取得して結合
ALLOWED_HOSTS = list(set(allowed_hosts + django_allowed_hosts))
if '' in ALLOWED_HOSTS:
    ALLOWED_HOSTS.remove('')


# Application definition

INSTALLED_APPS = [
    'django.contrib.admin',
    'django.contrib.auth',
    'django.contrib.contenttypes',
    'django.contrib.sessions',
    'django.contrib.messages',
    'django.contrib.staticfiles',
    'accounts.apps.AccountsConfig',
    'home.apps.HomeConfig',
    'widget_tweaks',
    'apps_gallery.apps.AppsGalleryConfig',
    'dashboard.apps.DashboardConfig',
    'profiles.apps.ProfilesConfig',
    'chats.apps.ChatsConfig',
    'blogs.apps.BlogsConfig',  # ブログアプリを追加
    'cloudinary',  # Cloudinaryを追加
    'taggit',  # タグ機能を追加
]

# Cloudinaryの設定
CLOUDINARY_STORAGE = {
    'CLOUD_NAME': os.getenv('CLOUDINARY_CLOUD_NAME'),
    'API_KEY': os.getenv('CLOUDINARY_API_KEY'),
    'API_SECRET': os.getenv('CLOUDINARY_API_SECRET'),
}

MIDDLEWARE = [
    'django.middleware.security.SecurityMiddleware',
    'django.contrib.sessions.middleware.SessionMiddleware',
    'django.middleware.common.CommonMiddleware',
    'django.middleware.csrf.CsrfViewMiddleware',
    'django.contrib.auth.middleware.AuthenticationMiddleware',
    'django.contrib.messages.middleware.MessageMiddleware',
    'django.middleware.clickjacking.XFrameOptionsMiddleware',
    'dashboard.middleware.SessionInfoMiddleware',  # セッション情報を記録するカスタムミドルウェア
]

ROOT_URLCONF = 'config.urls'

TEMPLATES = [
    {
        'BACKEND': 'django.template.backends.django.DjangoTemplates',
        'DIRS': [BASE_DIR / 'templates'],
        'APP_DIRS': True,
        'OPTIONS': {
            'context_processors': [
                'django.template.context_processors.debug',
                'django.template.context_processors.request',
                'django.contrib.auth.context_processors.auth',
                'django.contrib.messages.context_processors.messages',
            ],
        },
    },
]

WSGI_APPLICATION = 'config.wsgi.application'


# Database
# https://docs.djangoproject.com/en/5.1/ref/settings/#databases

DATABASE_URL = os.getenv('DATABASE_URL')
if DATABASE_URL:
    DATABASES = {
        'default': dj_database_url.config(default=DATABASE_URL)
    }
else:
    DATABASES = {
        'default': {
            'ENGINE': 'django.db.backends.sqlite3',
            'NAME': BASE_DIR / 'db.sqlite3',
        }
    }


# Password validation
# https://docs.djangoproject.com/en/5.1/ref/settings/#auth-password-validators

AUTH_PASSWORD_VALIDATORS = [
    {
        'NAME': 'django.contrib.auth.password_validation.UserAttributeSimilarityValidator',
    },
    {
        'NAME': 'django.contrib.auth.password_validation.MinimumLengthValidator',
    },
    {
        'NAME': 'django.contrib.auth.password_validation.CommonPasswordValidator',
    },
    {
        'NAME': 'django.contrib.auth.password_validation.NumericPasswordValidator',
    },
]


# Internationalization
# https://docs.djangoproject.com/en/5.1/topics/i18n/

LANGUAGE_CODE = 'ja'

TIME_ZONE = 'Asia/Tokyo'

USE_I18N = True

USE_TZ = True


# Static files (CSS, JavaScript, Images)
# https://docs.djangoproject.com/en/5.1/howto/static-files/

STATIC_URL = 'static/'
STATICFILES_DIRS = [
    BASE_DIR / 'static',  # プロジェクトのルートディレクトリにstaticフォルダを作成
]
STATIC_ROOT = BASE_DIR / "staticfiles"

# Media files
MEDIA_URL = '/media/'
MEDIA_ROOT = BASE_DIR / 'media'

# Default primary key field type
# https://docs.djangoproject.com/en/5.1/ref/settings/#default-auto-field

DEFAULT_AUTO_FIELD = 'django.db.models.BigAutoField'

# ログイン/ログアウト後のリダイレクト先を追加
LOGIN_REDIRECT_URL = '/'
LOGOUT_REDIRECT_URL = '/'
LOGIN_URL = '/accounts/login/'

# メール設定（開発環境用）
EMAIL_BACKEND = 'django.core.mail.backends.smtp.EmailBackend'
EMAIL_HOST = os.getenv('EMAIL_HOST')
EMAIL_HOST_USER = os.getenv('EMAIL_HOST_USER')
EMAIL_HOST_PASSWORD = os.getenv('EMAIL_HOST_PASSWORD')
EMAIL_PORT = os.getenv('EMAIL_PORT')
EMAIL_USE_TLS = True

# メール認証の設定
ACCOUNT_EMAIL_VERIFICATION = 'mandatory'  # メール認証を必須に
ACCOUNT_EMAIL_REQUIRED = True  # メールアドレスを必須に

# カスタムユーザーモデルの設定
AUTH_USER_MODEL = 'accounts.CustomUser'

# ロギング設定
LOGGING = {
    'version': 1,
    'disable_existing_loggers': False,
    'filters': {
        'exclude_normal_requests': {
            '()': 'django.utils.log.CallbackFilter',
            'callback': lambda record: not (
                'Request started' in record.getMessage() or 
                'Request finished' in record.getMessage() or
                'GET /chats/api/unread-messages/' in record.getMessage() or
                'GET /health/' in record.getMessage() or
                record.getMessage().startswith('"GET')
            )
        }
    },
    'formatters': {
        'simple': {
            'format': '%(message)s'
        }
    },
    'handlers': {
        'console': {
            'class': 'logging.StreamHandler',
            'filters': ['exclude_normal_requests'],
            'formatter': 'simple'
        },
    },
    'root': {
        'handlers': ['console'],
        'level': 'INFO',
    },
    'loggers': {
        'django': {
            'handlers': ['console'],
            'level': 'WARNING',
            'propagate': False,
        },
        'django.server': {
            'handlers': ['console'],
            'level': 'WARNING',
            'propagate': False,
        },
        # チャットアプリケーション用のカスタムログ
        'chats': {
            'handlers': ['console'],
            'level': 'INFO',
            'propagate': False,
        }
    },
}

# CSRF設定
CSRF_COOKIE_SECURE = False  # 開発環境ではFalse、本番環境ではTrue
CSRF_COOKIE_HTTPONLY = False  # 開発環境ではFalse、本番環境ではTrue
CSRF_TRUSTED_ORIGINS = [
    'http://localhost:8000',
    'https://oreapp-production.up.railway.app',
    'http://oreapp-production.up.railway.app',
    'https://*.railway.app',
    'http://*.railway.app'
]

# セッション設定
SESSION_COOKIE_SECURE = False  # 開発環境ではFalse、本番環境ではTrue

# CORS設定
CORS_ALLOW_CREDENTIALS = True
CORS_ORIGIN_ALLOW_ALL = True  # 開発環境のみ。本番環境では具体的なオリジンを指定する

# セキュリティ設定
SECURE_BROWSER_XSS_FILTER = True
SECURE_CONTENT_TYPE_NOSNIFF = True

