from django.urls import path
from . import views

urlpatterns = [
    path('', views.login_page, name="login page"),
    path('login/', views._login, name="ajax post login"),
    path('register/', views._register, name="ajax post register"),
    path('logout/', views._logout, name="logout"),
    path('activate/<uidb64>/<token>/', views.activate, name='activate link confirm'),
    path('resend/', views.resend, name='resend activate link'),
    path('profile/', views.profile, name='profile page')
]