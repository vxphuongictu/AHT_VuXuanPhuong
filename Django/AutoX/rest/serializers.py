from rest_framework import serializers
from products.models import *

class GalleryImageSerializers(serializers.ModelSerializer):
    class Meta:
        model       = GalleryImageProduct
        fields      = ['image']

class JoinTableSerializers(serializers.ModelSerializer):
    class Meta:
        model   = Jointablesmodel
        fields  = [
            'product_id',
            'product_name',
            'product_description',
            'product_price',
            'product_image',
            'attribute_name',
            'attribute_value',
            'post_time',
            'cat_id',
            'cat_name'
        ]


class ProductSerializers(serializers.ModelSerializer):
    class Meta:
        model       = Product
        fields      = ['product_id', 'product_name','product_description', 'product_price', 'product_image', 'product_create_time']

class CategoriesSerializers(serializers.ModelSerializer):
    on_cat          = ProductSerializers(many=True)
    class Meta:
        model       = Categories
        fields      = ['cat_id', 'cat_name', 'on_cat']