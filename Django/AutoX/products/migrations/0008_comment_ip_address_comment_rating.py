# Generated by Django 4.0.5 on 2022-10-21 01:28

from django.db import migrations, models


class Migration(migrations.Migration):

    dependencies = [
        ('products', '0007_history_order_invoice_code'),
    ]

    operations = [
        migrations.AddField(
            model_name='comment',
            name='ip_address',
            field=models.CharField(blank=True, max_length=50),
        ),
        migrations.AddField(
            model_name='comment',
            name='rating',
            field=models.FloatField(default=0),
        ),
    ]
