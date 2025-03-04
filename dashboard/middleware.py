"""
セッション情報を記録するためのカスタムミドルウェア
"""
class SessionInfoMiddleware:
    """
    ユーザーのログインセッションに、ブラウザ情報（ユーザーエージェント）とIPアドレスを
    記録するためのミドルウェア。セッション管理機能で利用される。
    """
    def __init__(self, get_response):
        self.get_response = get_response

    def __call__(self, request):
        # リクエスト処理の前にここのコードが実行される
        
        response = self.get_response(request)
        
        # リクエスト処理の後にここのコードが実行される
        
        # ログイン済みユーザーのセッション情報を保存
        if request.user.is_authenticated and hasattr(request, 'session') and request.session.session_key:
            # ユーザーエージェント（ブラウザ情報）を保存
            user_agent = request.META.get('HTTP_USER_AGENT', 'Unknown Browser')
            request.session['user_agent'] = user_agent
            
            # IPアドレスを保存（プロキシ対応）
            x_forwarded_for = request.META.get('HTTP_X_FORWARDED_FOR')
            if x_forwarded_for:
                # プロキシを経由している場合は最初のIPを取得
                ip_address = x_forwarded_for.split(',')[0].strip()
            else:
                # 直接アクセスの場合はREMOTE_ADDRから取得
                ip_address = request.META.get('REMOTE_ADDR', 'Unknown IP')
            
            request.session['ip_address'] = ip_address
            
            # 最終アクティビティ時間を更新（オプション）
            from django.utils import timezone
            request.session['last_activity'] = timezone.now().isoformat()
            
            # セッション変更を保存
            request.session.modified = True
            
        return response 