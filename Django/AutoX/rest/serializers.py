from rest_framework import serializers
from products.models import *

class GalleryImageSerializers(serializers.ModelSerializer):
    class Meta:
        model       = GalleryImageProduct
        fields      = ['image']

class ProductDetailSerializers(serializers.ModelSerializer):
    class Meta:
        model       = Product_meta
        fields      = [
            'product_kilometer',
            'product_fuel',
            'product_cheaper',
            'product_post_time',
            'product_at',
            'product_auction_price',
            'product_time_remaining',
            'product_condition',
            'product_color',
            'product_reg_date',
            'product_body_type',
            'product_manufacturing_year',
            'product_power',
            'product_seats'
        ]

class ProductSerializers(serializers.ModelSerializer):
    product         = ProductDetailSerializers(many=True)
    gallery         = GalleryImageSerializers(many=True)
    class Meta:
        model       = Product
        fields      = [
            'id',
            'product_name',
            'product_description',
            'product_price',
            'product_image',
            'product_create_time',
            'category_id',
            'product',
            'gallery'
        ]


class CategoriesSerializers(serializers.ModelSerializer):
    cat         = ProductSerializers(many=True)
    class Meta:
        model   = Categories
        fields  = ['id', 'cat_name', 'cat']