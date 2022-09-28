from .serializers import ProductSerializers, CategoriesSerializers
from products.models import *
from rest_framework import viewsets, permissions
from datetime import datetime

# Create your views here.
class ProductsViewSet(viewsets.ModelViewSet):
    queryset            = Product.objects.all()
    serializer_class    = ProductSerializers

class CategoriesViewSet(viewsets.ModelViewSet):
    queryset            = Categories.objects.all()
    serializer_class    = CategoriesSerializers

class ProductsResultViewSet(viewsets.ModelViewSet):
    timeNow             = datetime.now()
    queryset            = Product.objects.filter()