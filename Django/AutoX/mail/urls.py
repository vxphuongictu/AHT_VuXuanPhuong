from django.urls import path, include
from . import views

urlpatterns = [
    path('mailForGuest/', views.mailForGuest, name="mail for guest"),
    path('mailForUser/', views.mailForUser, name="mail for user")
]