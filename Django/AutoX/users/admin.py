from django.contrib import admin
from .models import MyUser

# Register your models here.

class AdminMyUser(admin.ModelAdmin):
    list_display = ["id", "fullname", "username", "phone", "email", "avatar", "is_active"]

admin.site.register(MyUser, AdminMyUser)