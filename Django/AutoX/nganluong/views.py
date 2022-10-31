import json
from nganluong import config
from nganluong.component import SeamlessCheckout
from django.http import HttpResponse

# Create your views here.

def index(request):
    return HttpResponse()

def check_field(request):
    success         = False
    error           = ""
    fields          = {
        'BANK_NAME'     : False,
        'BANK_ACCOUNT'  : False,
        'ISSUE_MONTH'   : False,
        'ISSUE_YEAR'    : False,
        'EXPIRED_MONTH' : False,
        'EXPIRED_YEAR'  : False
    }

    if request.method == "POST":
        request.session['SEAMLESS']     = {}
        try:
            nl_payment_method           = request.POST['nl_payment_method']
            request.session['SEAMLESS']['payment_method'] = nl_payment_method
        except:
            nl_payment_method           = ''

        try:
            nl_bank_code                = request.POST['nl_bank_code']
            request.session['SEAMLESS']['bank_code'] = nl_bank_code
        except:
            nl_bank_code                = ''

        try:
            buyer_fullname              = request.POST['buyer_fullname']
            request.session['SEAMLESS']['buyer_fullname'] = buyer_fullname
        except:
            buyer_fullname              = ''

        try:
            buyer_email                 = request.POST['buyer_email']
            request.session['SEAMLESS']['buyer_email'] = buyer_email
        except:
            buyer_email                 = ''

        try:
            buyer_mobile                = request.POST['buyer_mobile']
        except:
            buyer_mobile                = ''

        try:
            buyer_address               = request.POST['buyer_address']
            request.session['SEAMLESS']['buyer_address'] = buyer_address
        except:
            buyer_address               = ''

        if (request.session['SEAMLESS']):
            payment_method          = request.session['SEAMLESS']['payment_method']
            bank_code               = request.session['SEAMLESS']['bank_code']
            request_fields_result   = SeamlessCheckout.SeamlessCheckout().getRequestField({
                'payment_method'    : payment_method,
                'bank_code'         : bank_code
            })

            if request_fields_result and request_fields_result['error_code'] == '00':
                for bank_item in request_fields_result['response']['bank']:
                    if bank_item['bank_code'] == bank_code:
                        request_fields      = bank_item['payment_method'][payment_method]
                        if request_fields != 'request_fields':
                            try:
                                for field in request_fields['field']:
                                    fields[field]   = True
                            except:
                                pass
                            success             = True
                            break
            else:
                print("["+request_fields_result['error_code']+"] Connection error!")

    return HttpResponse(json.dumps({'success': success, 'error': error, 'fields': json.dumps(fields)}))

def get_banks(request):
    banks           = ''
    param_post      = False
    if request.method == "POST":
        try:
            request.POST['payment_method']
            param_post  = True
        except:
            pass

    if param_post is True:
        banks       = config.SeamlessCheckout.SeamlessCheckout().getBanksByPaymentMethod(request.POST['payment_method'])

    return HttpResponse(json.dumps(banks))