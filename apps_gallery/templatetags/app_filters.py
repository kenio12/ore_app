from django import template

register = template.Library()

@register.filter
def get_dict_value(dictionary, key):
    """辞書からキーに対応する値を取得するフィルター"""
    return dictionary.get(key, key) 