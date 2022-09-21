import json

from django.shortcuts import render
from django.contrib.auth import authenticate, login, logout
from django.contrib.auth.models import User
from django.http.response import HttpResponse, HttpResponseRedirect
# Create your views here.


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
            user_login          = authenticate(username=username, password=password)
            if (user_login is not None):
                login(request, user_login)
                result['status'] = "success"
                result['msg']   = "Login successfully"
                if rememberme == "forever":
                    request.session.set_expiry(500)
                else:
                    request.session.set_expiry(0)
            else:
                result['msg']   = "The username is not registered on this site. If you are unsure of your username, try your email address instead."
        else:
            result['msg']       = "please enter full information"

        return HttpResponse(json.dumps(result))

def _register(request):
    if request.method == "POST":
        result              = {'status': 'failed', 'msg': ''}

        full_name           = request.POST['full_name']
        user_register       = request.POST['user_register']
        email_register      = request.POST['email_register']
        new_pass            = request.POST['new_pass']
        user_confirm_pass   = request.POST['user_confirm_pass']
        agree               = request.POST['agree']

        if (agree == "checked"):
            if (full_name != "" and user_register != '' and email_register != '' and new_pass != '' and user_confirm_pass != ''):
                if (user_confirm_pass == new_pass):
                    user        = User.objects.create_user(user_register, email_register, new_pass)
                    user.save()
                    result['status']= "success"
                    result['msg']   = "Register successfully"
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
