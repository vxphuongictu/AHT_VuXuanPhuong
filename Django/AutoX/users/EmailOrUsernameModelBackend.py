from django.contrib.auth import get_user_model
from django.contrib.auth.backends import ModelBackend
import phonenumbers
User    = get_user_model()

class EmailOrUsernameModelBackend(ModelBackend):
    """
    This is a ModelBacked that allows authentication
    with either a username or an email address.

    """

    def authenticate(self, username=None, password=None):
        if '@' in username:
            kwargs  = {'email': username}
        else:
            try:
                check_phone     = phonenumbers.parse("+84" + username)
                if (phonenumbers.is_possible_number(check_phone) is True):
                    kwargs    = {'phone': username}
                else:
                    kwargs    = {'username': username}
            except:
                kwargs        = {'username': username}

        try:
            user    = get_user_model().objects.get(**kwargs)
            if user.check_password(password):
                return user
        except User.DoesNotExist:
            return None

    def get_user(self, username):
        try:
            return get_user_model().objects.get(pk=username)
        except get_user_model().DoesNotExist:
            return None