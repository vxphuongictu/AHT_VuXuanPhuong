from django.http import HttpResponse
from django.shortcuts import render
from products.models import *
import json
from datetime import datetime

def index(request):
    return HttpResponse()

def manager(request):
    context                 = {"categories": '', "products": ''}

    categories              = Categories.objects.all()
    context['categories']   = list(categories)

    products                = Product.objects.all()
    context['products']     = list(products)
    return render(request, template_name="manager.html", context=context)

def countWishList(request):
    wishlist_arrr       = []
    wishlist            = Product_wishlist.objects.filter(user_id=request.user.id)
    for i in wishlist:
        wishlist_arrr.append(i.prd_id)
    return HttpResponse(json.dumps(wishlist_arrr))

def wishlist(request, prd_id):
    if (request.user.is_authenticated):
        user_id         = request.user.id
        count_item      = Product_wishlist.objects.filter(prd_id=prd_id, user_id=user_id).count()
        if (count_item <= 0):
            insert      = Product_wishlist(prd_id=prd_id, user_id=user_id)
            insert.save()
        else:
            delete      = Product_wishlist.objects.filter(prd_id=prd_id, user_id=user_id)
            delete.delete()
    return HttpResponse()

def detail(request, p_id):
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
        
    context         = {"login": login, "product_info": main_product, "detail_product": meta_arr, 'gallery_product': list(gallery_product), 'wishlist': wishlist_arr}
    return render(request, template_name="detail-product.html", context=context)

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
        wishlist    = Product_wishlist.objects.filter(user_id=request.user.id).all()
        for i in wishlist:
            wishlist_arr.append(i.prd_id)
        context['wishlist']     = wishlist_arr
        context['cat_id']       = cat_id
        context['max_item']     = max_item
        context['data']         = dataPost
        if request.user.is_authenticated:
            context['login']    = True
    return render(request, template_name='products.html', context=context)