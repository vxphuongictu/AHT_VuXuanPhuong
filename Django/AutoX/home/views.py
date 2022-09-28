from django.shortcuts import render
import json

# Create your views here.
def index(request):

    context     = {'login' : False}

    if(request.user.is_authenticated):
        context['login']        = True
        return render(request, template_name='homeForLoggedUser.html', context=context)
    else:
        return render(request, template_name='home.html', context=context)