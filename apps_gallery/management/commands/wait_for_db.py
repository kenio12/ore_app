from django.core.management.base import BaseCommand
from django.db import connections
from django.db.utils import OperationalError
import time

class Command(BaseCommand):
    help = 'データベースが利用可能になるまで待機'

    def handle(self, *args, **options):
        self.stdout.write('データベースの接続を待機中...')
        db_conn = None
        while not db_conn:
            try:
                # default データベースへの接続を取得
                db_conn = connections['default']
                # 接続テスト
                db_conn.cursor()
            except OperationalError:
                self.stdout.write('データベースが利用できません。5秒後に再試行...')
                time.sleep(5)
                continue
            except Exception as e:
                self.stdout.write(f'予期せぬエラー: {e}')
                time.sleep(5)
                continue

        self.stdout.write(self.style.SUCCESS('データベース接続OK！')) 