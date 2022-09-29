from django.contrib import admin
from .models import Product, Categories, GalleryImageProduct, Product_attributes, Product_attribute_value

# Register your models here.

class ProductGalleryInline(admin.StackedInline):
    model   = GalleryImageProduct

class Product_attribute_valueInline(admin.StackedInline):
    list_display = ['attribute_value', 'post_time']
    model       =  Product_attribute_value

class ProductsAdmin(admin.ModelAdmin):
    list_display    = ['product_name', 'product_image', 'product_price', 'product_create_time']
    search_fields   = ['product_name']
    inlines         = [Product_attribute_valueInline, ProductGalleryInline]
    save_as         = True


admin.site.register(Product, ProductsAdmin)
admin.site.register(Categories)
admin.site.register(Product_attributes)