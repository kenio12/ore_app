from django import template

register = template.Library()

@register.filter
def get_item(dictionary, key):
    """
    辞書からキーを使って値を取得するカスタムフィルター
    使用例: {{ my_dict|get_item:key_var }}
    """
    if not dictionary:
        return ''
    return dictionary.get(key, '')

@register.filter
def split(value, arg):
    """
    文字列を分割し、指定されたインデックスの要素を返す
    使用例: {{ my_string|split:',0' }} - カンマで分割して最初の要素を取得
    """
    if not value:
        return ''
    
    separator, index = arg[0], int(arg[1:])
    parts = value.split(separator)
    
    if 0 <= index < len(parts):
        return parts[index]
    return '' 