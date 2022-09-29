# Generated by Django 4.0.5 on 2022-09-28 15:39

from django.db import migrations, models


class Migration(migrations.Migration):

    dependencies = [
        ('products', '0004_alter_product_attributes_attribute_options'),
    ]

    operations = [
        migrations.RemoveField(
            model_name='product_attributes',
            name='attribute_options',
        ),
        migrations.AddField(
            model_name='product_attribute_value',
            name='attribute_options',
            field=models.CharField(choices=[('text', 'Text'), ('number', 'Number'), ('password', 'Password'), ('datetime-local', 'Date time')], default='', max_length=100),
        ),
    ]