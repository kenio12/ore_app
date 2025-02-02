"""
アプリケーションの基本情報に関する定数
"""

APP_TYPES = {
    'web_app': 'Webアプリケーション',
    'ios_app': 'iOSアプリ',
    'android_app': 'Androidアプリ',
    'windows_app': 'Windowsアプリ',
    'mac_app': 'macOSアプリ',
    'linux_app': 'Linuxアプリ',
    'game': 'ゲーム',
    'other': 'その他'
}

APP_STATUS = {
    'completed': '完成',
    'in_development': '開発中',
    'draft': '非公開',
    'public': '公開'
}

GENRES = {
    'sns': 'SNS',
    'netshop': 'ネットショップ/EC',
    'matching': 'マッチングサービス',
    'learning_service': '学習サービス',
    'work': '仕事効率化',
    'entertainment': '娯楽',
    'daily_life': '日常生活',
    'communication': 'コミュニケーション',
    'healthcare': 'ヘルスケア',
    'finance': '金融',
    'news_media': 'ニュース・メディア',
    'food': '飲食・フード',
    'travel': '旅行・観光',
    'real_estate': '不動産',
    'education': '教育',
    'recruitment': '採用・求人',
    'literature': '文学',
    'art': '美術',
    'music': '音楽',
    'pet': 'ペット',
    'game': 'ゲーム',
    'sports': 'スポーツ',
    'academic': '学問',
    'development_tool': '開発ツール',
    'api_service': 'API/Webサービス',
    'cms': 'CMS',
    'blog': 'ブログ/メディア',
    'portfolio': 'ポートフォリオ',
    'other': 'その他'  # その他を選んだ場合、カスタムジャンルを入力できるように
}

# タブ定義を変更
TABS = {
    'basic': {
        'label': '基本情報',
        'icon': 'bi-info-circle-fill'
    },
    'appeal': {
        'label': 'アプリの魅力',
        'icon': 'bi-stars',
        'color': 'var(--cyber-green)'  # 緑色を定義
    },
    'screenshots': {
        'label': 'スクリーンショット',
        'icon': 'bi-image-fill',
        'color': 'var(--cyber-purple)'  # 紫色を定義
    },
    'story': {
        'label': '開発ストーリー',
        'icon': 'bi-book-fill'
    }
}

# アプリケーション共通設定
APP_DEFAULTS = {
    'title': '',
    'status': 'draft'
}

# モデルのフィールド定義
MODEL_FIELDS = {
    'title': {
        'verbose_name': 'アプリ名',
        'max_length': 100,
    },
    'other_genre': {
        'verbose_name': 'その他のジャンル',
        'max_length': 50,
        'blank': True,
    },
    'app_url': {  # シンプルに「アプリのURL」に変更
        'verbose_name': 'アプリのURL',
        'max_length': 200,
        'blank': True,
    },
    'github_url': {
        'verbose_name': 'GitHubリポジトリURL',
        'max_length': 200,
        'blank': True,
    },
    # ... 他のフィールドは省略 ...
}
