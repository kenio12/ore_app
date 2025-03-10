#!/usr/bin/env python
from django.contrib.auth import get_user_model

User = get_user_model()

try:
    user = User.objects.get(username='けにお王2')
    user.delete()
    print('ユーザー「けにお王2」を削除しました。')
except User.DoesNotExist:
    print('ユーザー「けにお王2」は存在しません。')
except Exception as e:
    print(f'エラー: {str(e)}') 