from django.urls import path
from . import views
from home import views as view_home

urlpatterns    = [
    path('', views.index, name="index"),
    path('categories/', views.categories, name="categories"),
    path('products/', views.products, name="products"),
    path('detail/', view_home.index, name="home page"),
    path('detail/<int:p_id>/', views.detail, name="detail product"),
    path('wishlist/', views.countWishList, name="count wishlist"),
    path('wishlist/<int:prd_id>/', views.wishlist, name="wishlist"),
    path('manager/', views.manager, name="manager product")
]