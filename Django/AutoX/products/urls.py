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
    path('update-cart/', views.upadteCart, name="Update cart Page"),
    path('cart/<int:prd_id>/', views.addCart, name="add cart"),
    path('cart/', views.viewcart, name="Cart Page"),
    path('checkout/', views.checkout, name="Checkout page"),
    path('saveAddress/', views.saveAddress, name="save located for users"),
    path('updateAddress/', views.updateAddress, name="update located"),
    path('deleteAddress/<int:id>/', views.deleteAddress, name="delete address"),
    path('order/', views.order, name="order process"),
    path('order-detail/<int:id>/', views.order_detail, name="order details"),
    path('cancel-order/<int:id>/', views.cancel_order, name="cancel order"),
    path('verify-otp/', views.verify_otp, name="verify_otp"),
    path('verify-qrcode/', views.verify_qrcode, name="verify_qrcode"),
    path('set-express-checkout/', views.setExpressCheckout, name="check field"),
    path('message/', views.return_message, name="return url"),
    path('stripe-success/', views.stripe_success, name="success"),
    path('stripe-fail/', views.stripe_fail, name="fail")
]