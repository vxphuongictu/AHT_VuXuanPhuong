# Generated by Django 4.0.5 on 2022-10-18 04:23

from django.db import migrations, models


class Migration(migrations.Migration):

    dependencies = [
        ('products', '0006_history_order_payment_method'),
    ]

    operations = [
        migrations.AddField(
            model_name='history_order',
            name='invoice_code',
            field=models.CharField(blank=True, default='', max_length=500),
        ),
    ]
