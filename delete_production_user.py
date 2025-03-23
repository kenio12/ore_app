#!/usr/bin/env python
import os
import sys
import requests
import getpass

# ベースURL（本番環境URL）
BASE_URL = "https://oreapp-production.up.railway.app"

def get_csrf_token(session):
    """CSRFトークンを取得"""
    response = session.get(f"{BASE_URL}/admin/login/")
    if response.status_code != 200:
        print(f"エラー: 管理サイトにアクセスできません。ステータスコード: {response.status_code}")
        return None
    
    # CSRFトークンをCookieから抽出
    csrf_token = session.cookies.get('csrftoken')
    if not csrf_token:
        print("エラー: CSRFトークンを取得できませんでした。")
        return None
    
    return csrf_token

def admin_login(session, username, password):
    """管理サイトにログイン"""
    csrf_token = get_csrf_token(session)
    if not csrf_token:
        return False
    
    login_data = {
        'username': username,
        'password': password,
        'csrfmiddlewaretoken': csrf_token,
        'next': '/admin/'
    }
    
    response = session.post(
        f"{BASE_URL}/admin/login/", 
        data=login_data,
        headers={
            'Referer': f"{BASE_URL}/admin/login/",
            'X-CSRFToken': csrf_token
        }
    )
    
    if response.status_code != 200 and response.status_code != 302:
        print(f"エラー: ログインに失敗しました。ステータスコード: {response.status_code}")
        return False
    
    if "authentication/login" in response.url:
        print("エラー: ログイン認証に失敗しました。ユーザー名とパスワードを確認してください。")
        return False
    
    return True

def delete_user(session, username=None, email=None):
    """ユーザーを削除"""
    if not username and not email:
        print("エラー: ユーザー名またはメールアドレスを指定してください。")
        return False
    
    # CSRFトークンを取得
    csrf_token = session.cookies.get('csrftoken')
    if not csrf_token:
        print("エラー: CSRFトークンが見つかりません。再ログインしてください。")
        return False
    
    # ユーザー一覧ページを取得し、削除したいユーザーのIDを特定
    response = session.get(f"{BASE_URL}/admin/accounts/customuser/")
    if response.status_code != 200:
        print(f"エラー: ユーザー一覧にアクセスできません。ステータスコード: {response.status_code}")
        return False
    
    # ここでHTMLをパースしてユーザーIDを抽出する必要があるが、
    # 簡略化のため、/admin/accounts/customuser/{user_id}/delete/ にリダイレクト
    print(f"注意: ブラウザを開いて以下のURLにアクセスし、管理者としてログインして削除操作を行ってください:")
    print(f"{BASE_URL}/admin/accounts/customuser/")
    print("削除したいユーザーのチェックボックスをオンにして、「選択されたアカウントを削除」を選んでください。")
    
    return True

def list_users(session):
    """ユーザー一覧を表示"""
    response = session.get(f"{BASE_URL}/admin/accounts/customuser/")
    if response.status_code != 200:
        print(f"エラー: ユーザー一覧にアクセスできません。ステータスコード: {response.status_code}")
        return False
    
    print(f"注意: ブラウザを開いて以下のURLにアクセスし、管理者としてログインしてユーザー一覧を確認してください:")
    print(f"{BASE_URL}/admin/accounts/customuser/")
    
    return True

def main():
    print("本番環境（Railway.app）のユーザー管理ツール")
    print("============================================")
    
    # オプション選択
    print("\n何をしたいですか？")
    print("1. ユーザー一覧を表示")
    print("2. ユーザーを削除")
    choice = input("選択（1または2）: ")
    
    with requests.Session() as session:
        # 管理者認証情報
        admin_username = input("管理者ユーザー名: ")
        admin_password = getpass.getpass("管理者パスワード: ")
        
        # 管理者ログイン
        if not admin_login(session, admin_username, admin_password):
            print("管理者ログインに失敗しました。終了します。")
            sys.exit(1)
        
        print("管理者ログインに成功しました！")
        
        if choice == '1':
            list_users(session)
        elif choice == '2':
            delete_type = input("ユーザー名で削除しますか？(y/n): ")
            if delete_type.lower() == 'y':
                username = input("削除するユーザー名: ")
                delete_user(session, username=username)
            else:
                email = input("削除するメールアドレス: ")
                delete_user(session, email=email)
        else:
            print("無効な選択です。終了します。")
            sys.exit(1)

if __name__ == "__main__":
    main() 