# Generated by Django 4.0.5 on 2022-09-19 14:42

from django.db import migrations, models


class Migration(migrations.Migration):

    dependencies = [
        ('products', '0001_initial'),
    ]

    operations = [
        migrations.AlterField(
            model_name='product_details',
            name='detail_id',
            field=models.AutoField(primary_key=True, serialize=False),
        ),
        migrations.AlterField(
            model_name='products',
            name='product_id',
            field=models.AutoField(primary_key=True, serialize=False),
        ),
    ]
