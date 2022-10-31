from django.shortcuts import render, redirect
from django.http import HttpResponse

# Create your views here.

def about(request):
    context         = {'login': ''}

    if (request.user.is_authenticated):
        login       = True
    else:
        login       = False

    context['login']= login
    return render(request, template_name='about.html', context=context)