import json

import requests
from .serializers import *
from products.models import *
from rest_framework import viewsets
from django.http import HttpResponse

# Create your views here.

class CategoriesViewSet(viewsets.ModelViewSet):
    queryset            = Categories.objects.all()
    serializer_class    = CategoriesSerializers

class JoinTablesViewSet(viewsets.ModelViewSet):
    queryset            = Jointablesmodel.objects.raw('SELECT * FROM `categories` INNER JOIN `products` ON categories.cat_id = products.category_id INNER JOIN `Product_attribute_value` ON products.product_id = Product_attribute_value.product_id INNER JOIN `product_attributes` ON Product_attribute_value.attribute_id = product_attributes.attribute_id')
    serializer_class    = JoinTableSerializers