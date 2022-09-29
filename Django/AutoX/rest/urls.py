from django.urls import path, include
from . import views
from rest_framework.routers import DefaultRouter


router          = DefaultRouter()
router.register('categories', views.CategoriesViewSet)
router.register('jointables', views.JoinTablesViewSet)

urlpatterns    = [
    path('', include(router.urls), name="rest api")
]