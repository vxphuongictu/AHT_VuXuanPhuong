from django.db import models

# Create your models here.

class Products(models.Model):
    product_id          = models.AutoField(primary_key=True)
    product_image       = models.ImageField(upload_to='uploads/%Y/%m/%d', blank=True)
    product_title       = models.CharField(max_length=500, blank=True)
    product_content     = models.TextField(max_length=5000, blank=True)
    product_create_time = models.DateTimeField(auto_now_add=True, blank=True)

    def __str__(self):
        return self.product_title


class Product_details(models.Model):
    detail_id           = models.AutoField(primary_key=True)
    detail_product_id   = models.ForeignKey(Products, on_delete=models.CASCADE)
    detail_title        = models.CharField(max_length=500, blank=True)
    detail_content      = models.TextField(max_length=5000, blank=True)