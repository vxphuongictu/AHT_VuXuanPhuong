from datetime import datetime
from django.shortcuts import render, redirect, reverse
from django.views.decorators.csrf import csrf_protect
from nganluong import config
from nganluong.component import SeamlessCheckout
from products.models import *
from users.models import *
from mail.views import *
from users.models import MyUser
import json
import stripe
from django.http import HttpResponse, HttpResponseNotFound, JsonResponse


def index(request):
    return HttpResponse()


def stripe_success(request):
    session_id = request.GET.get('session_id')
    if session_id is None:
        return HttpResponseNotFound()

    stripe.api_key = settings.STRIPE_SECRET_KEY
    session = stripe.checkout.Session.retrieve(session_id)
    return render(request, template_name='stripe_success.html')

def stripe_fail(request):
    return render(request, template_name='stripe_failed.html')

def return_message(request):
    success                     = False
    try:
        error_code              = request.GET['error_code']
    except:
        error_code              = ''

    try:
        token                   = request.GET['token']
    except:
        token                   = ''

    try:
        order_code              = request.GET['order_code']
    except:
        order_code              = ''

    try:
        order_id                = request.GET['order_id']
    except:
        order_id                = ''

    context                     = {'success': '', 'result_get_transaction_detail': ''}
    if error_code == '00':
        result_get_transaction_detail   = SeamlessCheckout.SeamlessCheckout().getTransactionDetail({'token': token})
        if result_get_transaction_detail['error_code'] == '00' and result_get_transaction_detail['transaction_status'] == '00':
            success             = True
            context['success']                          = success
            context['result_get_transaction_detail']    = result_get_transaction_detail

    return render(request, template_name='return.html', context=context)

def verify_qrcode(request):
    error                       = ''
    success                     = False

    if 'SEAMLESS' in request.session:
        qr_data                 = request.session['SEAMLESS']['qr_data']
        context                 = {'success': success, 'error': error, 'qr_data': qr_data}
        return render(request, template_name='', context=context)
    return redirect('/')

def verify_otp(request):
    context                     = {'success': False, 'error': ''}
    if 'SEAMLESS' in request.session:
        auth_url                = request.session['SEAMLESS']['auth_url']
        token                   = request.session['SEAMLESS']['token']

        try:
            otp                 = request.POST['otp']
        except:
            otp                 = ''

        try:
            list_product        = str(request.POST['list_product']).split(",")
        except:
            list_product        = []

        try:
            order_id            = request.POST['order_id']
        except:
            order_id            = ''

        if request.method == 'POST':
            input_authen_transaction    = {
                'token'         : token,
                'otp'           : otp,
                'auth_url'      : auth_url
            }
            result_authen_transaction   = SeamlessCheckout.SeamlessCheckout().authenTransaction(input_authen_transaction)
            if result_authen_transaction['error_code'] == '00':
                context['success']      = True

                # Delete product on cart after order #
                for prd_id in list_product:
                    delete_cart         = cart.objects.filter(user_id=request.user.id, prd_id=prd_id)
                    delete_cart.delete()

                # Update order history if order success #
                get_cart                = history_order.objects.filter(user_id=request.user.id, id=order_id).get()
                get_cart.order_status   = 2
                get_cart.save()

            else:
                context['error']        = result_authen_transaction['description']

    return HttpResponse(json.dumps(context))

def setExpressCheckout(request):
    return HttpResponse()

def cancel_order(request, id):
    result                  = {
        'status'            : False,
        'msg'               : ''
    }
    if request.user.is_authenticated:
        order               = history_order.objects.filter(user_id=request.user.id, id=id)
        fetch               = order.get()

        if (fetch.order_status == 0 or fetch.order_status == 1):
            if (order.count() > 0):
                order.delete()
                result['status'] = True
                result['msg']    = 1

    return HttpResponse(json.dumps(result))

def order_detail(request, id):

    if request.method == 'POST':
        value                   = request.POST['value']
        if value == "3" or value == "4":
            order_item                  = history_order.objects.filter(id=id).get()
            order_item.order_status     = value
            order_item.save()

    sum_price                   = 0
    order_id                    = id
    data_for_template           = []
    get_order_details           = history_order.objects.filter(id=order_id).get()
    location                    = located.objects.filter(user_id=request.user.id, is_default=1).get()

    if get_order_details.payment_method == 1:
        paymentMtStr            = "Payment on delivery"
    else:
        paymentMtStr            = "Credit"

    data_order                  = get_order_details.order_data
    for i in json.loads(data_order):
        product_id              = i['product_id']
        get_info                = Product.objects.filter(product_id=product_id).get()
        product_name            = get_info.product_name
        product_img             = get_info.product_image
        product_price           = get_info.product_price
        quantity                = i['product_quantity']
        total_price             = int(product_price) * int(quantity)
        sum_price+=total_price

        result                      = {
            "product_id"            : product_id,
            "product_name"          : product_name,
            "product_image"         : product_img,
            "product_price"         : product_price,
            "quantity"              : quantity,
            "total_price"           : total_price
        }
        data_for_template.append(result)

    context                     = {'dataOrder': data_for_template, 'paymentMt': paymentMtStr, 'located': location, 'transportFee': get_order_details.transport_fee, 'total_payment': sum_price}
    return render(request, template_name='checkout-result.html', context=context)

@csrf_protect
def order(request):

    json_result                 = {"success": False, "model_name": '', "redirect_link": '', 'msg': '', 'product_id': '', 'order_id': ''}
    if (request.method == "POST"):
        try:
            order_data          = request.POST['order_data']
        except:
            order_data          = ''

        try:
            payment_method_autox= request.POST['payment_method_autox']
        except:
            payment_method_autox= 0

        try:
            fee_shipping        = request.POST['fee_shipping']
        except:
            fee_shipping        = ''

        try:
            data_card_number    = request.POST['data_card_number']
        except:
            data_card_number    = ''

        try:
            data_card_fullname  = request.POST['data_card_fullname']
        except:
            data_card_fullname  = ''

        try:
            data_card_month     = request.POST['data_card_month']
        except:
            data_card_month     = ''

        try:
            data_card_year      = request.POST['data_card_year']
        except:
            data_card_year      = ''

        try:
            stripe_payment      = request.POST['stripe_payment']
        except:
            stripe_payment      = ''

        try:
            email_stripe_payment = request.POST['email_stripe_payment']
        except:
            email_stripe_payment = ''

        order_code                  = 'AutoX_checkout_' + str(datetime.now())
        myUser                      = MyUser.objects.filter(id=request.user.id).get()
        total_amount                = 0
        list_product_id             = []

        for order_product in json.loads(order_data):
            product_id          = order_product['product_id']
            get_info_product    = Product.objects.filter(product_id=product_id).get()
            list_product_id.append(product_id)
            product_price       = get_info_product.product_price
            product_quantity    = order_product['product_quantity']
            total_price         = int(product_price) * int(product_quantity)
            total_amount+=total_price

        json_result['product_id']       = list_product_id
        if payment_method_autox == "0" and stripe_payment == "0":
            checkout_payload            = {
                'order_code'            : order_code,
                'total_amount'          : total_amount,
                'payment_method'        : request.session['SEAMLESS']['payment_method'],
                'bank_code'             : request.session['SEAMLESS']['bank_code'],
                'fee_shipping'          : fee_shipping,
                'return_url'            : config.return_url,
                'buyer_fullname'        : MyUser.fullname,
                'buyer_email'           : myUser.email,
                'buyer_mobile'          : myUser.phone,
                'card_number'           : data_card_number,
                'card_fullname'         : data_card_fullname,
                'card_month'            : data_card_month,
                'card_year'             : data_card_year
            }
            result_set_request_field    = SeamlessCheckout.SeamlessCheckout().setExpressCheckout(checkout_payload)
            if (result_set_request_field['error_code'] == '00'):
                auth_site               = result_set_request_field['auth_site']
                auth_url                = result_set_request_field['auth_url']
                token                   = result_set_request_field['token']
                if (auth_site == 'NL'):
                    request.session['SEAMLESS']['auth_url'] = auth_url
                    request.session['SEAMLESS']['token']    = token
                    request.session.modified                = True
                    json_result['success']                  = True
                    json_result['model_name']               = "verify_otp"
                elif (auth_site == 'BANK'):
                    json_result['success']                  = True
                elif (auth_site == 'IB_BANK'):
                    json_result['success']                  = True
                    json_result['redirect_link']            = auth_url
                elif (auth_site == 'QRCODE'):
                    request.session['SEAMLESS']['qr_data'] = result_set_request_field['qr_data']
                    request.session.modified                = True
                    json_result['success']                  = True
                    json_result['model_name']               = "model_name"
            else:
                json_result['msg']  = result_set_request_field['description']
        elif payment_method_autox == "0" and stripe_payment == "1":
            stripe.api_key          = settings.STRIPE_SECRET_KEY
            checkout_session        = stripe.checkout.Session.create(
                customer_email      = email_stripe_payment,
                mode                = 'payment',
                payment_method_types=['card'],
                line_items          = [
                    {
                        'price_data': {
                            'currency' : 'usd',
                            'unit_amount': int(500000),
                            'product_data': {
                                'name'  : order_code
                            }
                        },
                        'quantity': 1,
                    },
                ],
                success_url=request.build_absolute_uri(
                    reverse('success')
                ) + "?session_id={CHECKOUT_SESSION_ID}",
                cancel_url=request.build_absolute_uri(reverse('fail')),
            )
            return JsonResponse({'sessionId': checkout_session.id, 'stripe_publishable_key': settings.STRIPE_PUBLIC_KEY})
        else:
            json_result['success']  = True
            for prd_id in list_product_id:
                delete_cart         = cart.objects.filter(user_id=request.user.id, prd_id=prd_id)
                delete_cart.delete()

        get_located_info            = located.objects.filter(user_id=request.user.id, is_default=1).get()

        insert_db                   = history_order(
            order_data=order_data,
            transport_fee=fee_shipping,
            total_payment=total_amount,
            located_id=get_located_info.id,
            user_id=request.user.id,
            payment_method=payment_method_autox,
            invoice_code=order_code
        )
        insert_db.save()
        get_history_order           = history_order.objects.filter(user_id=request.user.id, invoice_code=order_code).get()
        json_result['order_id']     = get_history_order.id
        if payment_method_autox == "1":
            get_history_order.order_status = 1
            get_history_order.save()

    return HttpResponse(json.dumps(json_result))

def insert_history(user_id, order_data, fee_shipping, total_amount, payment_method_autox, order_code):
    get_located_user    = located.objects.filter(user_id=user_id).get()
    insert_history      = history_order(
        order_data=order_data,
        transport_fee=fee_shipping,
        total_payment=total_amount,
        located_id=get_located_user.id,
        user_id=user_id,
        payment_method=payment_method_autox,
        invoice_code=order_code
    )
    insert_history.save()

def viewcart(request):
    if (request.user.is_authenticated):
        user_id         = request.user.id
        your_cart       = cart.objects.raw("SELECT * FROM `cart` INNER JOIN `products` WHERE cart.prd_id = products.product_id AND cart.user_id = "+str(user_id))
        context         = {'your_cart': your_cart}
        return render(request, template_name='viewcart.html', context=context)
    return redirect('/')


def deleteAddress(request, id):
    if (request.user.is_authenticated):
        if id != "":
            obj                 = located.objects.filter(id=id).get()
            obj.delete()

            obj                 = located.objects.filter(user_id=request.user.id).order_by('-id').all()
            for i in obj:
                i.is_default    = 1
                i.save()
                break
            return redirect('/products/checkout/')
    return redirect('/')

def updateAddress(request):
    if (request.user.is_authenticated):
        if request.method == "POST":
            address         = request.POST['address']
            getLocated      = located.objects.filter(user_id=request.user.id).all()
            for i in getLocated:
                if i.id == int(address):
                    i.is_default = 1
                else:
                    i.is_default = 0
                i.save()
            return redirect('/products/checkout/')
    return redirect('/')

def saveAddress(request):
    if (request.user.is_authenticated):
        if request.method == "POST":
            fullname        = request.POST['fullname']
            phone           = request.POST['phone']
            country         = request.POST['country']
            detail_country  = request.POST['detail-country']
            addressType     = request.POST['addressType']

            if addressType == "":
                addressType = 0

            try:
                request.POST['set-to-default']
                useAsDefault            = 1
                update              = located.objects.filter(user_id=request.user.id).all()
                for i in update:
                    i.is_default   = 0
                    i.save()
            except:
                useAsDefault            = 1

            user_located                = located(
                user_id=request.user.id,
                fullname=fullname,
                phone=phone,
                located_type=addressType,
                detail_country=detail_country,
                country=country,
                is_default=useAsDefault
            )

            user_located.save()
            return redirect('/products/checkout/')
    return redirect('/')

def checkout(request):
    if (request.user.is_authenticated):
        context                 = {'located': '', 'data': '', 'basic_data': '', 'total_price': '', 'ship_price': '', 'total_payment' : '', 'user_located': ''}
        location                = located.objects.filter(user_id=request.user.id).all()
        context['located']      = location
        if request.method == "POST":
            data                = request.POST['data']
            data_for_template   = []
            ship_price          = 500000
            total_payment       = 0

            for item in json.loads(data):
                product_id      = item['product_id']
                get_info        = Product.objects.filter(product_id=product_id).get()
                product_name    = get_info.product_name
                product_price   = get_info.product_price
                product_img     = get_info.product_image
                quantity        = item['product_quantity']
                total_price     = int(product_price) * int(quantity)
                total_payment+=total_price
                result          = {
                    "product_id"    : product_id,
                    "product_name"  : product_name,
                    "product_image" : product_img,
                    "product_price" : product_price,
                    "quantity"      : quantity,
                    "total_price"   : total_price
                }
                data_for_template.append(result)

            context['basic_data']   = data
            context['data']         = data_for_template
            context['total_price']  = total_payment
            context['ship_price']   = ship_price
            context['total_payment']= total_payment + ship_price
            user_located            = located.objects.raw("SELECT * FROM `users_located` INNER JOIN `users_myuser` ON users_located.user_id = users_myuser.id WHERE users_located.is_default = 1 AND user_id = "+str(request.user.id))
            context['user_located'] = user_located
        return render(request, template_name='checkout.html', context=context)
    return redirect('/')

def upadteCart(request):
    if request.method == "POST":
        user_id         = request.user.id
        product_id      = request.POST['product_id']
        if request.POST['act'] == "delete":
            c               = cart.objects.filter(user_id=user_id, prd_id=product_id)
            c.delete()
        else:
            quantity        = request.POST['quantity']
            user_id         = request.user.id

            if product_id:
                c           = cart.objects.filter(user_id=user_id, prd_id=product_id).get()
                c.quantity  = quantity
                c.save()
    return HttpResponse()

def addCart(request, prd_id):
    if (request.user.is_authenticated):
        user_id         = request.user.id
        cart_db         = cart.objects.filter(prd_id=prd_id, user_id=user_id)
        if cart_db.count() > 0:
            record      = cart_db.get()
            quantity    = record.quantity + 1
            record.quantity = quantity
            record.save()
        else:
            quantity    = 1
            insert_db = cart(prd_id=prd_id, quantity=quantity, user_id=user_id)
            insert_db.save()
        return HttpResponse(quantity)

def countWishList(request):
    wishlist_arrr       = []
    wishlist            = Product_wishlist.objects.filter(user_id=request.user.id)
    for i in wishlist:
        wishlist_arrr.append(i.prd_id)
    return HttpResponse(json.dumps(wishlist_arrr))

def wishlist(request, prd_id):
    if (request.user.is_authenticated):
        user_id     = request.user.id
    else:
        user_id     = request.COOKIES.get('csrftoken')

    count_item      = Product_wishlist.objects.filter(prd_id=prd_id, user_id=user_id).count()
    if (count_item <= 0):
        insert      = Product_wishlist(prd_id=prd_id, user_id=user_id)
        insert.save()
    else:
        delete      = Product_wishlist.objects.filter(prd_id=prd_id, user_id=user_id)
        delete.delete()
    return HttpResponse()

def detail(request, p_id):

    if request.method == "POST":
        fullname    = request.POST['your-name']
        email       = request.POST['your-email']
        msg         = request.POST['your-comment']
        rating      = request.POST['rating-data']
        ipaddress   = request.META.get('REMOTE_ADDR')
        product_id  = request.POST['product-id']
        comment(fullname=fullname, email=email, msg=msg, rating=rating, ip_address=ipaddress, prd_id=product_id).save()
        return redirect('/products/detail/'+product_id+'/')

    if (request.user.is_authenticated):
        wishlist_arr    = []
        meta_arr        = []
        result          = {}
        post_id         = int(p_id)
        main_product    = Product.objects.get(pk=post_id)
        meta_product    = Product.objects.raw('SELECT * FROM `products` INNER JOIN `Product_attribute_value` ON products.product_id = Product_attribute_value.product_id INNER JOIN `product_attributes` ON Product_attribute_value.attribute_id = product_attributes.attribute_id WHERE products.product_id = '+str(post_id))
        for item in meta_product:
            result[item.attribute_name] = item.attribute_value
        meta_arr.append(result)
        gallery_product = main_product.gallery.all()
        wishlist        = Product_wishlist.objects.filter(user_id=request.user.id).all()
        for i in wishlist:
            wishlist_arr.append(i.prd_id)

        if (request.user.is_authenticated):
            login       = True
        else:
            login       = False

        arr_msg         = []
        all_comment     = comment.objects.filter(prd_id=post_id).order_by('-comment_id').all()
        for i in all_comment:
            comment_id  = i.comment_id
            fullname    = i.fullname
            email       = i.email
            msg         = i.msg
            rating      = i.rating
            post_time   = i.create_time

            try:
                info_user   = MyUser.objects.filter(email=email).get()
                avatar      = info_user.avatar
            except:
                avatar      = ""

            result      = {
                'comment_id'    : comment_id,
                'fullname'      : fullname,
                'email'         : email,
                'msg'           : msg,
                'total_btn_rate': range(int(5)),
                'rating'        : int(rating),
                'post_time'     : post_time,
                'avatar'        : avatar
            }
            arr_msg.append(result)

        context         = {
            "login": login,
            "product_info": main_product,
            "detail_product": meta_arr,
            "gallery_product": list(gallery_product),
            "wishlist": wishlist_arr,
            "user_info": request.user,
            "comment_list": arr_msg
        }
        return render(request, template_name="detail-product.html", context=context)
    return redirect('/users/')

def categories(request):
    result          = []
    totalCount      = 0
    context         = {'categories': '', 'totalCount': 0}

    if request.method == "POST":
        data   = json.loads(request.POST['categories'])
        for item in data:
            totalCount+=len(item['on_cat'])
        context['totalCount'] = totalCount

        for item in data:
            payload     = {
                'cat_id'    : item['cat_id'],
                'cat_name'  : item['cat_name'],
                'cat_count' : len(item['on_cat'])
            }
            result.append(payload)
        context['categories'] = result

    return render(request, template_name='categories.html', context=context)

def products(request):
    wishlist_arr    = []
    context         = {'login': False, 'cat_name' : '', 'max_item' : '', 'data': {}, 'wishlist': ''}
    if request.method == "POST":
        cat_id      = json.loads(request.POST['cat_id'])
        max_item    = json.loads(request.POST['max_item'])
        dataPost    = json.loads(request.POST['data'])

        if (request.user.is_authenticated):
            user    = request.user.id
        else:
            user    = request.COOKIES.get('csrftoken')

        wishlist    = Product_wishlist.objects.filter(user_id=user).all()
        for i in wishlist:
            wishlist_arr.append(i.prd_id)
        context['wishlist']     = wishlist_arr
        context['cat_id']       = cat_id
        context['max_item']     = max_item
        context['data']         = dataPost
        if request.user.is_authenticated:
            context['login']    = True
    return render(request, template_name='products.html', context=context)