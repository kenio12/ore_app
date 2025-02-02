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
        'icon': 'information-circle'
    },
    'appeal': {
        'label': 'アプリの魅力',
        'icon': 'sparkles'
    },
    'screenshots': {
        'label': 'スクリーンショット',
        'icon': 'photograph'
    }
}

# アプリケーション共通設定
APP_DEFAULTS = {
    'title': '',
    'status': 'draft'
}
