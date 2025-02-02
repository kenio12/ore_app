"""
ハードウェア環境に関する定数
"""

# デバイスタイプ
DEVICE_TYPES = {
    'windows': 'Windows PC',
    'mac': 'Mac',
    'linux': 'Linux PC',
    'chrome_os': 'Chrome OS',
    'tablet': 'タブレット',
    'smartphone': 'スマートフォン',
    'other': 'その他'
}

# OSタイプ
OS_TYPES = {
    'ios': 'iOS',
    'android': 'Android',
    'windows': 'Windows',
    'macos': 'macOS',
    'linux': 'Linux',
    'other': 'その他'
}

# CPUタイプ
CPU_TYPES = {
    'x86': 'x86/x64',
    'arm': 'ARM',
    'apple_silicon': 'Apple Silicon',
    'other': 'その他'
}

# メモリサイズ
MEMORY_SIZES = {
    '2gb': '2GB以下',
    '4gb': '4GB',
    '8gb': '8GB',
    '16gb': '16GB',
    '32gb': '32GB以上'
}

# ストレージタイプ
STORAGE_TYPES = {
    'hdd': 'HDD',
    'ssd': 'SSD',
    'nvme': 'NVMe SSD',
    'emmc': 'eMMC',
    'other': 'その他'
}

# モニター数
MONITOR_COUNTS = {
    '1': '1台',
    '2': '2台',
    '3': '3台',
    '4+': '4台以上'
}

# モニターサイズ
MONITOR_SIZES = {
    '24': '24インチ以下',
    '27': '27インチ',
    '32': '32インチ',
    '34': '34インチウルトラワイド',
    '38': '38インチウルトラワイド',
    '43+': '43インチ以上',
    'other': 'その他'
}

# モニター解像度
MONITOR_RESOLUTIONS = {
    'fhd': 'FHD (1920x1080)',
    '2k': '2K (2560x1440)',
    '4k': '4K (3840x2160)',
    '5k': '5K以上',
    'other': 'その他'
} 