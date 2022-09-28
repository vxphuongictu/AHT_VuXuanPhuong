import json
from django.http import HttpResponse
from django.core.mail import send_mail
from AutoX import settings
from django.shortcuts import render

# Create your views here.

def mailForUser(request):
    status  = False
    msg     = ""
    if request.method == "POST":
        message         = request.POST['message']

        if (message == ""):
            msg         = "Please enter full information on this form"
        else:
            subject     = "Email sent from AutoX by "+request.user.username
            send        = send_mail(subject, message, settings.EMAIL_HOST, [settings.RECEIVE_MAIL], fail_silently=False)
            msg         = send
            status      = True

    result  = {
        'status'    : status,
        'msg'       : msg
    }
    return HttpResponse(json.dumps(result))

def mailForGuest(request):
    status  = False
    msg     = ""
    if request.method == "POST":
        full_name       = request.POST['full_name']
        phone_number    = request.POST['phone_number']
        email           = request.POST['email']
        message         = request.POST['message']

        if (full_name == "") or (phone_number == "") or (email == "") or (message == ""):
            msg         = "Please enter full information on this form"
        else:
            subject     = "Email sent from AutoX by guest account!"
            message     = message + "\n My Email Address is: "+email+" And my phone number: "+phone_number
            send        = send_mail(subject, message, settings.EMAIL_HOST, [settings.RECEIVE_MAIL], fail_silently=False)
            msg         = send
            status      = True

    result          = {
        'status'    : status,
        'msg'       : msg
    }
    return HttpResponse(json.dumps(result))