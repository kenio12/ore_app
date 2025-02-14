"""
開発環境（ハードウェア）に関する定数
"""

# パソコンタイプ
PC_TYPES = {
    'laptop': 'ノートパソコン',
    'desktop': 'デスクトップパソコン',
    'other': 'その他'
}

# デバイスタイプ（OS）
DEVICE_TYPES = {
    'windows': 'Windows PC',
    'mac': 'Mac',
    'linux': 'Linux PC',
    'chrome_os': 'Chrome OS',
    'other': 'その他'
}

# メーカー名は自由入力（例）
MAKER_EXAMPLES = [
    'Apple（MacBook Pro/Airなど）',
    'Lenovo（ThinkPadなど）',
    'Dell（XPS/Inspironなど）',
    'HP（Pavilion/Spectre/Envyなど）',
    'ASUS（ZenBook/ROGなど）',
    'Microsoft（Surfaceシリーズ）',
    'Google（Chromebookシリーズ）',
    '自作PC',
    'その他'
]

# OSタイプ
OS_TYPES = {
    'windows': 'Windows（10/11）',
    'macos': 'macOS',
    'linux': 'Linux（Ubuntu/CentOSなど）',
    'chrome_os': 'Chrome OS',
    'other': 'その他'
}

# CPUタイプ
CPU_TYPES = {
    'intel': 'Intel CPU搭載（Core i3/i5/i7/i9など）',
    'amd': 'AMD CPU搭載（Ryzen 3/5/7/9など）',
    'apple_silicon': 'Apple Silicon搭載（M1/M2/M3など）',
    'other': 'その他'
}

# メモリサイズ
MEMORY_SIZES = {
    '4gb': '4GB',
    '8gb': '8GB',
    '16gb': '16GB',
    '32gb': '32GB以上'
}

# ストレージタイプ
STORAGE_TYPES = {
    'hdd': 'HDD',
    'ssd': 'SSD（SATA接続）',
    'nvme': 'NVMe SSD（高速）',
    'other': 'その他'
}

# モニター数（開発効率に重要）
MONITOR_COUNTS = {
    'laptop_single': 'ノートPCの画面のみ',
    'laptop_plus_one': 'ノートPC + 外部モニター1台',
    'laptop_plus_two': 'ノートPC + 外部モニター2台',
    'laptop_plus_more': 'ノートPC + 外部モニター3台以上',
    'desktop_single': 'デスクトップPC + モニター1台',
    'desktop_dual': 'デスクトップPC + モニター2台',
    'desktop_triple': 'デスクトップPC + モニター3台',
    'desktop_more': 'デスクトップPC + モニター4台以上',
    'other': 'その他'
}

# モニターサイズ（メインの外部モニター）
MONITOR_SIZES = {
    '24': '24インチ',
    '27': '27インチ',
    '32': '32インチ',
    '34': '34インチウルトラワイド',
    '38': '38インチウルトラワイド',
    '43+': '43インチ以上',
    'other': 'その他'
}

# インターネット回線
INTERNET_TYPES = {
    'fiber': '光回線（1Gbps以上）',
    'catv': 'ケーブルテレビ回線',
    'adsl': 'ADSL',
    'mobile': 'モバイル回線（4G/5G）',
    'other': 'その他'
} 