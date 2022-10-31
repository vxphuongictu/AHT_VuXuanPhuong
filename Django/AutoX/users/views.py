import json

from django.contrib.auth.hashers import make_password
from django.shortcuts import render, redirect
from django.contrib.auth import authenticate, login, logout, get_user_model
from django.http.response import HttpResponse, HttpResponseRedirect
from django.core.mail import send_mail
from AutoX import settings
from django.utils.http import urlsafe_base64_encode, urlsafe_base64_decode
from django.template.loader import render_to_string
from django.utils.encoding import force_bytes, force_str
from django.contrib.sites.shortcuts import get_current_site
from .token import account_activation_token
import phonenumbers
from . import EmailOrUsernameModelBackend
from products.models import *

User    = get_user_model()
# Create your views here.


def profile(request):
    if (request.user.is_authenticated):
        if request.method == "POST":
            status      = False
            user        = request.user.username
            fullname    = request.POST['fullname']
            email       = request.POST['email']
            phone       = request.POST['phone']

            try:
                avatar  = request.FILES['avatar']
            except:
                avatar  = ""

            current_pwd = request.POST['current_pwd']
            new_pwd     = request.POST['new_pwd']
            confirm_pwd = request.POST['confirm_pwd']

            user_model  = User.objects.get(username=user)
            if (new_pwd != ""):
                if (new_pwd == confirm_pwd):
                    check_user  = authenticate(request, username=user, password=current_pwd)
                    if check_user is not None:
                        user_model.password = make_password(new_pwd)
                    else:
                        result  = {
                            'status'    : status,
                            'msg'       : "Incorrect password"
                        }
                        return HttpResponse(json.dumps(result))
                else:
                    result = {
                        'status'    : status,
                        'msg'       : "Password does not match"
                    }
                    return HttpResponse(json.dumps(result))

            if (fullname != ""):
                user_model.fullname     = fullname

            if (email != ""):
                user_model.email        = email

            if (phone != ""):
                user_model.phone        = phone

            if (avatar != ""):
                user_model.avatar       = avatar

            user_model.save()
            result   = {
                'status'    : True,
                'msg'       : "Update successfully"
            }
            return HttpResponse(json.dumps(result))

        all_wishlist    = Product_wishlist.objects.raw("SELECT * FROM `product_wishlist` INNER JOIN `products` WHERE product_wishlist.prd_id = products.product_id AND product_wishlist.user_id = "+str(request.user.id))
        all_order       = history_order.objects.all()

        order_by_id         = []
        order_manage        = []
        for orderByUser in all_order:
            prd_data        = []
            order_id        = orderByUser.id
            invoice_code    = orderByUser.invoice_code
            user_id         = orderByUser.user_id
            order_time      = orderByUser.order_time
            order_data      = json.loads(orderByUser.order_data)
            located_data    = located.objects.filter(user_id=user_id, is_default=1).get()
            total_payment   = orderByUser.total_payment
            order_status    = orderByUser.order_status

            result      = {
                'invoice_code'  : invoice_code,
                'order_id'      : order_id,
                'user_id'       : user_id,
                'order_time'    : order_time,
                'total_payment' : total_payment,
                'order_status'  : order_status,
                'located_data'  : located_data,
                'product_info'  : prd_data
            }
            for product in order_data:
                product_id      = product['product_id']
                product_price   = product['product_price']
                product_quantity= product['product_quantity']
                total_price     = int(product_price) * int(product_quantity)
                get_info        = Product.objects.filter(product_id=product_id).get()
                product_name    = get_info.product_name
                product_img     = get_info.product_image
                payload         = {
                    'product_id'        : product_id,
                    'product_name'      : product_name,
                    'product_price'     : product_price,
                    'product_quantity'  : product_quantity,
                    'product_image'     : product_img,
                    'total_price'       : total_price
                }
                prd_data.append(payload)
            order_by_id.append(result)
            if request.user.is_superuser:
                order_manage.append(result)

        context         = {'wishlist': all_wishlist, 'order_by_id': order_by_id, 'order_manage': order_manage}
        return render(request, template_name='profile.html', context=context)
    return redirect("/users/")

def resend(request):
    if request.method == "POST":
        status      = False
        username    = request.POST['username']

        try:
            if '@' in username:
                user    = User.objects.get(email=username)
            else:
                try:
                    check_phone     = phonenumbers.parse("+84" + username)
                    if (phonenumbers.is_possible_number(check_phone) is True):
                        user        = User.objects.get(phone=username)
                    else:
                        user        = User.objects.get(username=username)
                except:
                    user    = User.objects.get(username=username)

            email       = user.email
            subject     = "Confirm your email"
            message     = render_to_string('active_email.html', {
                'user'          : user.username,
                'domain'        : get_current_site(request).domain,
                'uid'           : urlsafe_base64_encode(force_bytes(user.pk)),
                'token'         : account_activation_token.make_token(user),
                'protocol'      : 'https' if request.is_secure() else 'http'
            })

            send_mail(
                subject,
                message,
                settings.EMAIL_HOST,
                [email],
                fail_silently=False
            )
        except:
            user        = None

        if user is not None:
            status      = True
            msg         = "Activation link has been sent to your email"
        else:
            msg         = ""
        result          = {
            'status'    : status,
            'msg'       : msg
        }
        return HttpResponse(json.dumps(result))

def activate(request, uidb64, token):
    if (request.user.is_authenticated):
        return redirect('/')
    else:
        try:
            uid     = force_str(urlsafe_base64_decode(uidb64))
            user    = User.objects.get(pk=uid)
        except(TypeError, ValueError, OverflowError, User.DoesNotExist):
            user    = None

        if user is not None and account_activation_token.check_token(user, token):
            user.is_active = True
            user.save()
            msg     = 'Thank you for your email confirmation. Now you can login your account.'
        else:
            msg     = 'Activation link is invalid!'

        context     = {'msg': msg}
        return render(request, template_name='index.html', context=context)

def login_page(request):
    if (request.user.is_authenticated):
        return HttpResponseRedirect('/')
    else:
        return render(request, template_name='index.html')


def _login(request):
    if request.method == "POST":
        username                = request.POST['user_login']
        password                = request.POST['user_pass']
        rememberme              = request.POST['rememberme']
        result                  = {'status': 'failed', 'msg': ''}

        if (username != "") and (password != ""):
            try:
                if '@' in username:
                    user        = User.objects.get(email=username)
                else:
                    try:
                        check_phone = phonenumbers.parse("+84"+username)
                        if (phonenumbers.is_possible_number(check_phone) is True):
                            user    = User.objects.get(phone=username)
                        else:
                            user    = User.objects.get(username=username)
                    except:
                        user    = User.objects.get(username=username)
            except:
                user            = None

            if user is not None:
                if user.is_active == 1:
                    user_login          = EmailOrUsernameModelBackend.EmailOrUsernameModelBackend().authenticate(username=username, password=password)
                    if (user_login is not None):
                        login(request, user_login)
                        result['status'] = "success"
                        result['msg']   = "Login successfully"
                        if rememberme == "forever":
                            request.session.set_expiry(5000)
                        else:
                            request.session.set_expiry(0)
                    else:
                        result['msg']   = "The username is not registered on this site. If you are unsure of your username, try your email address instead."
                else:
                    result['msg']       = "Your accounts hasn't been activated. Click <a class='resend-mail-activate'>here</a> if you don't have link activate!"
            else:
                result['msg']   = "Please enter the correct phone number and password for a staff account. Note that both fields may be case-sensitive."
        else:
            result['msg']       = "please enter full information"

        return HttpResponse(json.dumps(result))

def _register(request):
    if request.method == "POST":
        result              = {'status': 'failed', 'msg': ''}

        username            = request.POST['username']
        phone_number        = request.POST['phone_number']
        email               = request.POST['email']
        full_name           = request.POST['full_name']
        new_pass            = request.POST['new_pass']
        user_confirm_pass   = request.POST['user_confirm_pass']
        agree               = request.POST['agree']

        if (agree == "checked"):
            if (full_name != "" and username != '' and phone_number != '' and email != '' and new_pass != '' and user_confirm_pass != ''):
                if (user_confirm_pass == new_pass):
                    user            = User.objects.create_user(username, full_name, phone_number, email, new_pass)
                    user.is_active  = 0
                    user.save()

                    ## Send mail confirm after register ##
                    subject         = "Confirm your email"
                    message         = render_to_string('active_email.html', {
                        'user'      : user.username,
                        'domain'    : get_current_site(request).domain,
                        'uid'       : urlsafe_base64_encode(force_bytes(user.pk)),
                        'token'     : account_activation_token.make_token(user),
                        'protocol'  : 'https' if request.is_secure() else 'http'
                    })

                    send_mail(
                        subject,
                        message,
                        settings.EMAIL_HOST,
                        [email],
                        fail_silently=False
                    )
                    ## End send mail confirm after register ##

                    result['status']= "success"
                    result['msg']   = "Register successfully, Activation link has been sent to your email. Please check and confirm email to active accounts"
                else:
                    result['msg']   = "The enter confirm password does not same"
            else:
                result['msg']       = "please enter full information"
        else:
            result['msg']           = "You must agree policy after register"

        return HttpResponse(json.dumps(result))

def _logout(request):
    logout(request)
    return HttpResponseRedirect('/')