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