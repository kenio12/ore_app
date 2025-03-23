#!/usr/bin/env python
import os
import django

os.environ.setdefault('DJANGO_SETTINGS_MODULE', 'config.settings')
django.setup()

from accounts.models import CustomUser

def delete_user(username=None, email=None):
    """指定されたユーザー名またはメールアドレスを持つユーザーを削除する"""
    if username:
        deleted, _ = CustomUser.objects.filter(username=username).delete()
        print(f"{deleted}人のユーザー (username={username}) を削除しました")
    
    if email:
        deleted, _ = CustomUser.objects.filter(email=email).delete()
        print(f"{deleted}人のユーザー (email={email}) を削除しました")

if __name__ == "__main__":
    import sys
    if len(sys.argv) > 1:
        username = sys.argv[1] if len(sys.argv) > 1 else None
        email = sys.argv[2] if len(sys.argv) > 2 else None
        delete_user(username, email)
    else:
        # 全ユーザーリストを表示
        users = CustomUser.objects.all()
        print(f"登録ユーザー数: {users.count()}")
        for user in users:
            print(f"ID: {user.id}, ユーザー名: {user.username}, メール: {user.email}, 有効: {user.is_active}") 