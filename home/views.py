from django.shortcuts import render
from django.contrib import messages

def home(request):
    # 後でApp modelを使用するように更新
    apps = []
    return render(request, 'home/home.html', {
        'apps': apps
    })
