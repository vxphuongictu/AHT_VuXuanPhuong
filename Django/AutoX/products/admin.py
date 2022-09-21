from django.contrib import admin
from .models import Products, Product_details

# Register your models here.

class ProductsAdmin(admin.ModelAdmin):
    list_display    = ['product_id', 'product_image', 'product_title', 'product_content', 'product_create_time']
    search_fields   = ['product_title']


admin.site.register(Products, ProductsAdmin)
admin.site.register(Product_details)