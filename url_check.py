from django.urls import reverse; try: reverse("apps_gallery:detail", args=[1]); print("exists"); except Exception as e: print(e)
