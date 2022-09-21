from django.urls import path, include
from . import views
from rest_framework.routers import DefaultRouter


router  = DefaultRouter()
router.register('products', views.ProductViewSet)

urlpatterns     = [
    path('product_api/', include(router.urls)),
]