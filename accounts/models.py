from django.contrib.auth.models import AbstractUser
from django.db import models

# Create your models here.

class CustomUser(AbstractUser):
    email = models.EmailField('メールアドレス', unique=True)
    email_verified = models.BooleanField('メール認証済み', default=False)
