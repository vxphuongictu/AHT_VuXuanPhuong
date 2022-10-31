import json
from django.http import HttpResponse
from django.core.mail import send_mail
from AutoX import settings
from django.shortcuts import render

# Create your views here.

def mailToOrder(request, first_name, last_name, company, country, street_address, street_optional, town, postcode, phone, email):
    status      = False
    msg         = ""
    fullname    = first_name + " " + last_name
    message     = """
    Hi, My name is """ +fullname + """
    \n
    Here is some my information to order product on your website:
    Company name: """ + company + """ \n
    Country:  """ + country + """ \n
    Street: """ + street_address + """ \n
    Home town: """ + town + """ \n
    Postcode: """ + postcode + """\n
    Phone: """ + phone + """ \n
    email: """ + email +"""
    """

    if (message == ""):
        msg     = "Please enter full information on this form"
    else:
        subject = "Email sent from AutoX"
        send    = send_mail(subject, message, settings.EMAIL_HOST, [settings.RECEIVE_MAIL], fail_silently=False)
        msg     = send
        status  = True

    result      = {
        'status': status,
        'msg'   : msg
    }
    return HttpResponse(json.dumps(result))

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
    status          = False
    msg             = ""
    field_name      = True
    field_phone     = True
    field_mail      = True
    field_message   = True
    if request.method == "POST":
        full_name       = request.POST['full_name']
        phone_number    = request.POST['phone_number']
        email           = request.POST['email']
        message         = request.POST['message']

        if (full_name == "") or (phone_number == "") or (email == "") or (message == ""):
            if full_name == "":
                field_name      = False

            if phone_number == "":
                field_phone     = False

            if email == "":
                field_mail      = False

            if message == "":
                field_message   = False

            msg         = "Please enter full information on this form"
        else:
            subject     = "Email sent from AutoX by guest account!"
            message     = message + "\n My Email Address is: "+email+" And my phone number: "+phone_number
            send        = send_mail(subject, message, settings.EMAIL_HOST, [settings.RECEIVE_MAIL], fail_silently=False)
            msg         = send
            status      = True

    result              = {
        'status'        : status,
        'msg'           : msg,
        'field_name'    : field_name,
        'field_phone'   : field_phone,
        'field_mail'    : field_mail,
        'field_message' : field_message
    }
    return HttpResponse(json.dumps(result))