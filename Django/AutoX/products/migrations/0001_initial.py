# Generated by Django 4.0.5 on 2022-09-28 07:44

from django.db import migrations, models
import django.db.models.deletion


class Migration(migrations.Migration):

    initial = True

    dependencies = [
    ]

    operations = [
        migrations.CreateModel(
            name='Categories',
            fields=[
                ('cat_id', models.AutoField(primary_key=True, serialize=False)),
                ('cat_name', models.CharField(blank=True, max_length=100, unique=True)),
            ],
            options={
                'db_table': 'categories',
            },
        ),
        migrations.CreateModel(
            name='Product',
            fields=[
                ('product_id', models.AutoField(primary_key=True, serialize=False)),
                ('product_name', models.CharField(blank=True, max_length=500)),
                ('product_description', models.TextField(blank=True, max_length=5000)),
                ('product_price', models.CharField(blank=True, max_length=20)),
                ('product_image', models.ImageField(blank=True, upload_to='uploads/%Y/%m/%d')),
                ('product_create_time', models.DateField(auto_now_add=True)),
                ('category', models.ForeignKey(on_delete=django.db.models.deletion.CASCADE, related_name='on_cat', to='products.categories')),
            ],
            options={
                'db_table': 'products',
            },
        ),
        migrations.CreateModel(
            name='Product_attributes',
            fields=[
                ('attribute_id', models.AutoField(primary_key=True, serialize=False)),
                ('attribute_name', models.CharField(blank=True, default='', max_length=200, unique=True)),
            ],
            options={
                'db_table': 'product_attributes',
            },
        ),
        migrations.CreateModel(
            name='Product_wishlist',
            fields=[
                ('id', models.BigAutoField(auto_created=True, primary_key=True, serialize=False, verbose_name='ID')),
                ('prd_id', models.IntegerField(blank=True, default='')),
                ('user_id', models.IntegerField(blank=True, default='')),
                ('date_added', models.DateField(auto_now_add=True)),
            ],
            options={
                'db_table': 'product_wishlist',
            },
        ),
        migrations.CreateModel(
            name='Product_attribute_value',
            fields=[
                ('id', models.BigAutoField(auto_created=True, primary_key=True, serialize=False, verbose_name='ID')),
                ('attribute_value', models.CharField(blank=True, default='', max_length=200)),
                ('post_time', models.DateField(auto_now_add=True)),
                ('attribute', models.ForeignKey(on_delete=django.db.models.deletion.CASCADE, related_name='attribute_value', to='products.product_attributes')),
                ('product', models.ForeignKey(default='', on_delete=django.db.models.deletion.CASCADE, related_name='attributes', to='products.product')),
            ],
            options={
                'db_table': 'product_attribute_value',
            },
        ),
        migrations.CreateModel(
            name='Jointablesmodel',
            fields=[
                ('id', models.BigAutoField(auto_created=True, primary_key=True, serialize=False, verbose_name='ID')),
                ('product_name', models.CharField(blank=True, default='', max_length=200)),
                ('product_description', models.CharField(blank=True, default='', max_length=5000)),
                ('product_price', models.CharField(blank=True, max_length=20)),
                ('product_image', models.CharField(blank=True, default='', max_length=5000)),
                ('attribute_name', models.CharField(blank=True, default='', max_length=200)),
                ('attribute_value', models.CharField(blank=True, default='', max_length=200)),
                ('post_time', models.DateField(auto_now_add=True)),
                ('cat_name', models.CharField(blank=True, max_length=100, unique=True)),
                ('product', models.ForeignKey(on_delete=django.db.models.deletion.CASCADE, to='products.product')),
            ],
            options={
                'db_table': 'join_table_models',
            },
        ),
        migrations.CreateModel(
            name='GalleryImageProduct',
            fields=[
                ('id', models.BigAutoField(auto_created=True, primary_key=True, serialize=False, verbose_name='ID')),
                ('image', models.ImageField(blank=True, upload_to='uploads/%Y/%m/%d')),
                ('prd', models.ForeignKey(on_delete=django.db.models.deletion.CASCADE, related_name='gallery', to='products.product')),
            ],
            options={
                'db_table': 'product_gallery',
            },
        ),
    ]
