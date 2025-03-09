from django import template
import json

register = template.Library()

@register.filter(name='get_dict_value')
def get_dict_value(dictionary, key):
    """辞書からキーに対応する値を取得するフィルター"""
    # 辞書とキーが存在することを確認
    if dictionary is None or key is None:
        return ''
    
    # 辞書型であることを確認
    if not isinstance(dictionary, dict):
        return key
    
    # 辞書から値を取得（存在しない場合はキーをそのまま返す）
    return dictionary.get(key, key) 

@register.filter(name='get_item')
def get_item(dictionary_or_list, key_or_index):
    """
    リストや辞書から指定されたキー/インデックスの要素を取得するフィルター
    - リストの場合はインデックスで要素を取得
    - 辞書の場合はキーで要素を取得
    """
    try:
        if isinstance(dictionary_or_list, dict):
            return dictionary_or_list.get(key_or_index, '')
        elif isinstance(dictionary_or_list, (list, tuple)):
            return dictionary_or_list[key_or_index]
        return ''
    except (IndexError, TypeError, KeyError):
        return ''

@register.filter(name='subtract')
def subtract(value, arg):
    """数値から別の数値を引くフィルター"""
    try:
        return value - arg
    except (ValueError, TypeError):
        return value 

@register.filter(name='json_decode')
def json_decode(value):
    """JSON文字列をデコードするフィルター"""
    try:
        return json.loads(value) if value else []
    except (json.JSONDecodeError, TypeError):
        return [] 

@register.filter(name='genre_display')
def genre_display(genre_key):
    """英語のジャンルキーを日本語表示に変換するフィルター"""
    from apps_gallery.constants.app_info import ジャンル表示
    return ジャンル表示.get(genre_key, genre_key) 