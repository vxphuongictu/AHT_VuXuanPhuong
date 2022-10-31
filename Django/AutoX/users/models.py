from django.contrib.auth.base_user import BaseUserManager
from django.utils.translation import gettext_lazy as _
from django.db import models
from django.contrib.auth.models import AbstractUser
from django.contrib.auth.hashers import make_password


# Create your models here.

# Create new fields user #

class card(models.Model):
    user                            = models.ForeignKey('MyUser', on_delete=models.CASCADE)
    card_number                     = models.CharField(max_length=50, default='', blank=True)
    expiration                      = models.CharField(max_length=5, default='', blank=True)
    cvv                             = models.IntegerField()
    fullname                        = models.CharField(max_length=50, default='', blank=True)
    email                           = models.CharField(max_length=50, default='', blank=True)

    def __str__(self):
        return self.fullname

class located(models.Model):
    user                            = models.ForeignKey('MyUser', on_delete=models.CASCADE)
    fullname                        = models.CharField(max_length=100, default='', blank=True)
    phone                           = models.CharField(max_length=15, default='', blank=True)
    country                         = models.CharField(max_length=100, default='', blank=True)
    detail_country                  = models.CharField(max_length=500, default='', blank=True)
    is_default                      = models.BooleanField(default=1)
    located_type                    = models.IntegerField(default=0)

class CustomUserManager(BaseUserManager):
    use_in_migrations = True

    def _create_user(self, username, fullname, phone, email, password, **extra_fields):
        """
        Create and save a user with the given username, email, and password.
        """
        if not phone:
            raise ValueError("The given phone must be set")
        email           = self.normalize_email(email)
        # Lookup the real model class from the global app registry so this
        # manager method can be used in migrations. This is fine because
        # managers are by definition working on the real model.
        user            = self.model(username=username, fullname=fullname, phone=phone, email=email, **extra_fields)
        user.password   = make_password(password)
        user.save(using=self._db)
        return user

    def create_user(self, username, fullname, phone, email=None, password=None, **extra_fields):
        extra_fields.setdefault("is_staff", False)
        extra_fields.setdefault("is_superuser", False)
        return self._create_user(username, fullname, phone, email, password, **extra_fields)

    def create_superuser(self, username, fullname, phone, email=None, password=None, **extra_fields):
        extra_fields.setdefault("is_staff", True)
        extra_fields.setdefault("is_superuser", True)

        if extra_fields.get("is_staff") is not True:
            raise ValueError("Superuser must have is_staff=True.")
        if extra_fields.get("is_superuser") is not True:
            raise ValueError("Superuser must have is_superuser=True.")

        return self._create_user(username, fullname, phone, email, password, **extra_fields)


class MyUser(AbstractUser):

    first_name      = None
    last_name       = None

    username        = models.CharField(
        _("username"),
        max_length=150,
        unique=True,
        blank=True
    )

    phone           = models.CharField(
        _("phone number"),
        max_length=15,
        blank=True,
        unique=True,
    )

    email           = models.EmailField(
        _("email address"),
        unique=True,
        blank=True
    )

    fullname        = models.CharField(
        _("full name"),
        max_length=255,
        blank=True
    )

    password        = models.CharField(_("password"), max_length=128)

    avatar          = models.ImageField(
        upload_to   = "uploads/%Y/%m/%d",
        default     = "uploads/default-avatar.png"
    )

    objects         = CustomUserManager()
    USERNAME_FIELD  = "username"
    REQUIRED_FIELDS = ["phone", "fullname", "email"]

    def __str__(self):
        if (self.fullname):
            return self.fullname
        return self.username