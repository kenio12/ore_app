from django import template

register = template.Library()

@register.filter
def get_item(dictionary, key):
    """
    辞書から値を取得するためのカスタムテンプレートフィルター
    使用例: {{ my_dict|get_item:key_variable }}
    """
    if not dictionary:
        return None
    
    return dictionary.get(key, None) 