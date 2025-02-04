from django import template

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
def get_item(lst, index):
    """リストから指定されたインデックスの要素を取得するフィルター"""
    try:
        return lst[index]
    except (IndexError, TypeError):
        return None 

@register.filter(name='subtract')
def subtract(value, arg):
    """数値から別の数値を引くフィルター"""
    try:
        return value - arg
    except (ValueError, TypeError):
        return value 