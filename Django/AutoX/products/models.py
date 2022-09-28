from django.db import models
# Create your models here.

class Product_meta(models.Model):
    product                             = models.ForeignKey('Product', on_delete=models.CASCADE, related_name="product")
    product_kilometer                   = models.CharField(max_length=20, default='29400', blank=True)
    product_fuel                        = models.CharField(max_length=20, default='Gasoline', blank=True)
    product_cheaper                     = models.IntegerField(default='125000000', blank=True)
    product_post_time                   = models.DateField(auto_now_add=True)
    product_at                          = models.CharField(max_length=20, default='AT', blank=True)
    product_auction_price               = models.IntegerField(default='326750000', blank=True)
    product_time_remaining              = models.DateField(blank=True, default='')
    product_condition                   = models.CharField(max_length=50, default='Used', blank=True)
    product_color                       = models.CharField(max_length=50, default='Gray', blank=True)
    product_reg_date                    = models.DateField(default='', blank=True)
    product_body_type                   = models.CharField(max_length=100, default='Hatchback', blank=True)
    product_manufacturing_year          = models.IntegerField(default='2018', blank=True)
    product_power                       = models.IntegerField(default='182', blank=True)
    product_seats                       = models.IntegerField(default='5', blank=True)

    class Meta:
        db_table = "product_meta"

# class Product_meta(models.Model):
#     product                             = models.ForeignKey('Product', on_delete=models.CASCADE, related_name="product_meta")
#     product_key                         = models.CharField(max_length=5000, default='', blank=True)
#     product_value                       = models.CharField(max_length=5000, default='', blank=True)
#
#     class Meta:
#         db_table = "product_meta"

class Product(models.Model):

    product_name                    = models.CharField(max_length=500, blank=True)
    product_description             = models.TextField(max_length=5000, blank=True)
    product_price                   = models.CharField(max_length=20, blank=True)
    product_image                   = models.ImageField(upload_to='uploads/%Y/%m/%d', blank=True)
    category                        = models.ForeignKey('Categories', on_delete=models.CASCADE, related_name="cat", default='')
    product_create_time             = models.DateField(auto_now_add=True, blank=True)

    class Meta:
        db_table    = "products"

    def __str__(self):
        return self.product_name

class GalleryImageProduct(models.Model):
    prd                 = models.ForeignKey('Product', on_delete=models.CASCADE, related_name='gallery')
    prd_image           = models.ImageField(upload_to='uploads/%Y/%m/%d', blank=True, name="image")

    class Meta:
        db_table        = "product_gallery"

class Categories(models.Model):
    cat_name            = models.CharField(max_length=100, blank=True, unique=True)

    class Meta:
        db_table        = "categories"

    def __str__(self):
        return self.cat_name

class Product_wishlist(models.Model):
    prd_id          = models.IntegerField(blank=True, default='')
    user_id         = models.IntegerField(blank=True, default='')
    date_added      = models.DateField(auto_now_add=True)

    class Meta:
        db_table    = "product_wishlist"

    # def __str__(self):
    #     return self.prd_id