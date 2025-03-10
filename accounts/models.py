from django.contrib.auth.models import AbstractUser
from django.db import models
import uuid

# Create your models here.

class CustomUser(AbstractUser):
    email = models.EmailField('メールアドレス', unique=True)
    email_verified = models.BooleanField('メール認証済み', default=False)
    
    # メール認証用の確認コードを追加
    verification_code = models.CharField('確認コード', max_length=50, blank=True, null=True)
    
    def generate_verification_code(self):
        """一意の確認コードを生成"""
        code = str(uuid.uuid4())[:12]
        self.verification_code = code
        return code
