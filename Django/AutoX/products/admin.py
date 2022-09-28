from django.contrib import admin
from .models import Product, Product_meta, Categories, GalleryImageProduct

# Register your models here.

class ProductGalleryInline(admin.StackedInline):
    model   = GalleryImageProduct

class ProductDetailInline(admin.StackedInline):
    model   = Product_meta
    max_num = 1

class ProductsAdmin(admin.ModelAdmin):
    list_display    = ['id', 'product_name', 'product_image', 'product_price', 'product_create_time']
    search_fields   = ['product_name']
    inlines         = [ProductDetailInline, ProductGalleryInline]


admin.site.register(Product, ProductsAdmin)
admin.site.register(Categories)