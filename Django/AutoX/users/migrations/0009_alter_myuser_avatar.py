# Generated by Django 4.0.5 on 2022-10-07 04:35

from django.db import migrations, models


class Migration(migrations.Migration):

    dependencies = [
        ('users', '0008_alter_myuser_avatar'),
    ]

    operations = [
        migrations.AlterField(
            model_name='myuser',
            name='avatar',
            field=models.ImageField(blank=True, upload_to='uploads/%Y/%m/%d'),
        ),
    ]
