from django.contrib.auth.base_user import BaseUserManager
from django.utils.translation import gettext_lazy as _
from django.contrib.auth.validators import UnicodeUsernameValidator
from django.db import models
from django.contrib.auth.models import AbstractUser, UserManager
from django.apps import apps
from django.contrib import auth
from django.utils.crypto import get_random_string, salted_hmac
from django.contrib.auth.hashers import make_password


# Create your models here.

# Create new fields user #

class CustomUserManager(BaseUserManager):
    use_in_migrations = True

    def _create_user(self, fullname, phone, email, password, **extra_fields):
        """
        Create and save a user with the given username, email, and password.
        """
        if not phone:
            raise ValueError("The given phone must be set")
        email           = self.normalize_email(email)
        # Lookup the real model class from the global app registry so this
        # manager method can be used in migrations. This is fine because
        # managers are by definition working on the real model.
        user            = self.model(fullname=fullname, phone=phone, email=email, **extra_fields)
        user.password   = make_password(password)
        user.save(using=self._db)
        return user

    def create_user(self, fullname, phone, email=None, password=None, **extra_fields):
        extra_fields.setdefault("is_staff", False)
        extra_fields.setdefault("is_superuser", False)
        return self._create_user(fullname, phone, email, password, **extra_fields)

    def create_superuser(self, fullname, phone, email=None, password=None, **extra_fields):
        extra_fields.setdefault("is_staff", True)
        extra_fields.setdefault("is_superuser", True)

        if extra_fields.get("is_staff") is not True:
            raise ValueError("Superuser must have is_staff=True.")
        if extra_fields.get("is_superuser") is not True:
            raise ValueError("Superuser must have is_superuser=True.")

        return self._create_user(fullname, phone, email, password, **extra_fields)


class MyUser(AbstractUser):

    username        = None
    first_name      = None
    last_name       = None

    phone           = models.CharField(
        _("phone number"),
        max_length=15,
        blank=True,
        unique=True
    )

    password        = models.CharField(_("password"), max_length=128)

    fullname        = models.CharField(
        _("full name"),
        max_length=255,
        blank=True
    )
    email           = models.EmailField(
        _("email address"),
        unique=True,
        blank=True
    )

    avatar          = models.ImageField(
        upload_to="uploads/%Y/%m/%d",
        blank=True
    )

    objects         = CustomUserManager()
    USERNAME_FIELD  = "phone"
    REQUIRED_FIELDS = ["fullname", "email"]

    def __str__(self):
        if (self.fullname):
            return self.fullname
        return self.phone