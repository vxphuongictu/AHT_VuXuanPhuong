from rest_framework import serializers
from .models import Products

class ProductSerializers(serializers.ModelSerializer):
    class Meta:
        model   = Products
        fields  = ['product_id', 'product_image', 'product_title', 'product_content', 'product_create_time']