from django.urls import path
from nganluong import views

urlpatterns = [
    path('', views.index, name="index"),
    path('get_bank/', views.get_banks, name="ajax get bank"),
    path('check-field/', views.check_field, name="check field"),
]