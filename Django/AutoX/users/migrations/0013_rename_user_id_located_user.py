# Generated by Django 4.0.5 on 2022-10-10 11:10

from django.db import migrations


class Migration(migrations.Migration):

    dependencies = [
        ('users', '0012_located_fullname_located_phone'),
    ]

    operations = [
        migrations.RenameField(
            model_name='located',
            old_name='user_id',
            new_name='user',
        ),
    ]