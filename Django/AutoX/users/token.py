from django.contrib.auth.tokens import PasswordResetTokenGenerator
import six
from django.utils.crypto import constant_time_compare, salted_hmac
from django.utils.http import base36_to_int, int_to_base36
from django.conf import settings

class AccountActivationTokenGenerator(PasswordResetTokenGenerator):
    pass
    # def _make_hash_value(self, user, timestamp):
    #     print("123")
    #     return (
    #         six.text_type(user.pk) + six.text_type(timestamp) + six.text_type(user.is_active)
    #     )

account_activation_token = AccountActivationTokenGenerator()