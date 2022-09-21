from django.shortcuts import render
from .models import Products
from .serializers import ProductSerializers
from rest_framework import viewsets

# Create your views here.

class ProductViewSet(viewsets.ModelViewSet):
    queryset            = Products.objects.all()
    serializer_class    = ProductSerializers
