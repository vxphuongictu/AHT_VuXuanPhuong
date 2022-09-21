/* MENU */

var isOpened 	= false;

function showNavMenu()
{
	$('body').on('click', '.fa-bars', function(){
		if (isOpened === false)
		{
			$('.menu-content').addClass('show-nav-menu');
			$('.nav-menu').addClass('extend-nav-menu');
			$('.menu-btn').css('display', 'none');
			$('.list-menu').addClass('center-nav-menu');
			isOpened = true;
		}
		else
		{
			$('.menu-content').removeClass('show-nav-menu');
			$('.nav-menu').removeClass('extend-nav-menu');
			$('.menu-btn').css('display', 'flex');
			$('.list-menu').removeClass('center-nav-menu');
			isOpened = false;
		}
	});
}

function serviceSubMenu()
{
	$('body').on('click', '.service-sub-menu', function(e){
		$('.service-sub-menu-item').slideToggle(200);
	});
}

function aboutSubMenu()
{
	$('body').on('click', '.about-sub-menu', function(e){
		$('.about-sub-menu-item').slideToggle(200);
	});
}

function categoryMenu()
{
	$('body').on('click', '.category, .fa-solid', function(e){
		$('.category-list').slideToggle(200);
	});
}

function subMenuItem()
{
	$('body').on('click', '.nav-sub-menu', function () {
		$('.sub-menu-item').slideToggle(200);
	});
}

function hiddenMenu()
{
	$(document).on('click', function(e) {
		var target 		= e.target;
		if (!$(target).is('.category, .category span a, .search-group i')){
			$('.category-list').css('display', 'none');
		}
		if (!$(target).is('.nav-sub-menu .menu-img, .nav-sub-menu .categories')) {
			$('.sub-menu-item').css('display', 'none');
		}
		if (!$(target).is('.about-sub-menu a')) {
			$('.about-sub-menu-item').css('display', 'none');
		}
		if (!$(target).is('.service-sub-menu a')) {
			$('.service-sub-menu-item').css('display', 'none');
		}
		if (!$(target).is('.language-groups, .language-groups div, .language-groups .fa-angle-down')) {
			$('.others-language').css('display', 'none');
		}
		if (!$(target).is(
			'.fa-search, .panel-search-mobile .container .modal-search .btn-search a, .panel-search-mobile .container .modal-search .search-group .line, .panel-search-mobile .container .modal-search .btn-search, .category-list .item a, .header .container .act-group .icon-group .fa-search, .language-groups, .language-groups div, .panel-search-mobile .container .modal-search .search-group .category, .panel-search-mobile .container .modal-search .search-group .category span a, .panel-search-mobile .container .modal-search .search-group i, .panel-search-mobile .container .modal-search .search-group .input-search, .category-list, .category-list .category-item, .panel-search-mobile .container .modal-search .search-group .input-search input'
			)) {
			$('.panel-search-mobile').css('display', 'none');
		}
		if (!$(target).is('.fa-bars, .about-sub-menu a, .service-sub-menu a')) {
			$('.menu-content').removeClass('show-nav-menu');
			$('.nav-menu').removeClass('extend-nav-menu');
			$('.menu-btn').css('display', 'flex');
			$('.list-menu').removeClass('center-nav-menu');
			isOpened = false;
		}
	})
}
/* END MENU */

/* slidebar */
function slidebar()
{
	$('body').on('click', '.btn-previous', function(){
		var i 			= 0;
		var j 			= 0;
		var slide 		= $('body').find('.bg-slide');
		slide.each(function(e) {
			var target 	= $(this);
			if (target.css('display') == 'block') {
				target.css('display', 'none');
				j = i;
			}
			i ++;
		});
		if (j == 0)
		{
			j = 2;
		}
		else
		{
			j-=1;
		}
		slide[j].style.display 	= 'block';
	});

	$('body').on('click', '.btn-after', function(){
		var i 			= 0;
		var j 			= 0;
		var slide 		= $('body').find('.bg-slide');
		slide.each(function(e) {
			var target 	= $(this);
			if (target.css('display') == 'block') {
				target.css('display', 'none');
				j = i;
			}
			i ++;
		});
		if (j >= 2)
		{
			j = 0;
		}
		else
		{
			j+=1;
		}
		slide[j].style.display 	= 'block';
	});
}

var slideIndex	 = 0;
function showSlide()
{
	var i 			 = 0
	var slide 		 = $('body').find('.bg-slide');
	slide.each(function(e){
		$(this).css('display', 'none');
		i++;
	});
	slideIndex++;

	if (slideIndex > i) {
		slideIndex = 1;
	}
	slide[slideIndex-1].style.display 	= 'block';

	$('.btn-img .checked').css('background-color', 'transparent');

	if (slideIndex == 1)
	{
		$('#img-1 .checked').css('background-color', '#0029ff4f');
	} else if (slideIndex == 2) {
		$('#img-2 .checked').css('background-color', '#0029ff4f');
	} else if (slideIndex == 3) {
		$('#img-3 .checked').css('background-color', '#0029ff4f');
	}
	setTimeout(showSlide, 4000);
}
/* END SLIDE */

/* SLIDE ITEM SELLING */

var slideItemSelling = 0;

function slidebarItemSelling()
{

	$('body').on('click', '.ti-angle-left', function(){
		var i 			= 0;
		var j 			= 0;
		var slide 		= $('body').find('.slider-item-selling');
		slide.each(function(e) {
			var target 	= $(this);
			if (target.css('display') == 'block') {
				target.css('display', 'none');
				j = i;
			}
			i ++;
		});
		if (j == 0)
		{
			j = 2;
		}
		else
		{
			j-=1;
		}
		slide[j].style.display 	= 'block';
	});


	$('body').on('click', '.ti-angle-right', function(){
		var i 			= 0;
		var j 			= 0;
		var slide 		= $('body').find('.slider-item-selling');
		slide.each(function(e) {
			var target 	= $(this);
			if (target.css('display') == 'block') {
				target.css('display', 'none');
				j = i;
			}
			i ++;
		});
		if (j >= 2)
		{
			j = 0;
		}
		else
		{
			j+=1;
		}
		slide[j].style.display 	= 'block';
	});
}

function AutoChangeItemSelling()
{
	var i 			 = 0
	var slide 		 = $('body').find('.slider-item-selling');
	slide.each(function(e){
		$(this).css('display', 'none');
		i++;
	});
	slideItemSelling++;

	if (slideItemSelling > i) {
		slideItemSelling = 1;
	}
	slide[slideItemSelling-1].style.display 	= 'block';
	// setTimeout(AutoChangeItemSelling, 10000);
}

/* END SLIDE ITEM SELLING */

/* COUNTDOWN */

function countDown()
{
	var timeNow 		= new Date().getTime();
	var timeEnd 		= new Date("Jan 1, 2023 0:0:0").getTime();
	var remainingTime	= timeEnd - timeNow;
	var days = Math.floor(remainingTime / (1000 * 60 * 60 * 24));
	var hours = Math.floor((remainingTime % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
	var minutes = Math.floor((remainingTime % (1000 * 60 * 60)) / (1000 * 60));
	var seconds = Math.floor((remainingTime % (1000 * 60)) / 1000);
	$('#remaining-day').html(days);
	$('#remaining-hour').html(hours);
	$('#remaining-min').html(minutes);
	$('#remaining-sec').html(seconds);
	setTimeout(countDown, 1000);
}
/* END COUNTDOWN */

/* draggable */

function holdToChangeSlidebar()
{
	var mouseDown		= 0;
	var mouseUp 		= 0;
	var slide 			= $('body').find('.bg-slide');

	$('.bg-slide').mousedown(function(e){
		mouseDown 		= e.pageX;
	}).mouseup(function(e) {
		mouseUp 		= e.pageX;
		var i 			= 0;
		var j 			= 0;
		slide.each(function(){
			var target 	= $(this);
			if (target.css('display') == 'block') {
				target.css('display', 'none');
				j = i;
			}
			i++;
		});

		if (mouseDown > mouseUp)
		{
			if (j >= 2)
			{
				j = 0;
			} else {
				j+=1;
			}
		}
		else if (mouseDown < mouseUp)
		{
			if (j == 0)
			{
				j = 2;
			} else {
				j-=1;
			}
		}
		slide[j].style.display 	= 'block';
	});
}

function changeBackgroundMobile()
{
	$('body').on('click', '.btn-next-slide-mobile .btn-img', function(event) {

		var imgCount= 0;
		var target  = 0;
		var idName 	= $(this).attr('id');
		var slide 	= $('body').find('.bg-slide');

		$('.btn-img .checked').css('background-color', 'transparent');
		$(this).find('.checked').css('background-color', '#0029ff4f');

		if (idName == "img-1")
		{
			target 	= 0;
		} else if (idName == "img-2") {
			target 	= 1;
		} else if (idName == "img-3") {
			target 	= 2;
		}

		slide.each(function(){
			var target 	= $(this);
			if (target.css('display') == 'block') {
				target.css('display', 'none');
			}
		});

		slide[target].style.display 	= 'block';
	});
}

function changeLatestNew()
{
	var i 			= 0;
	var j 			= 0;
	var countItem 	= $('body').find('.changeItem');
	countItem.each(function() {
		var target 	= $(this);
		if (target.css('order') == '1')
		{
			target.css('order', 3);
			target.css('display', 'none');
		} else if (target.css('order') == '2')
		{
			target.css('order', 1);
			target.css('display', 'flex');
		} else if (target.css('order') == '3')
		{
			target.css('order', 2);
			target.css('display', 'flex');
		}
	});

	var windows 	= $(window).width();
	if (windows > 1150 || windows < 460)
	{
		countItem.each(function() {
			var target 	= $(this);
			if (j == 0)
			{
				target.css('order', 1);
				target.css('display', 'flex');
			}

			if (j == 1)
			{
				target.css('order', 2);
				target.css('display', 'flex');
			}

			if (j == 2)
			{
				target.css('order', 3);
				target.css('display', 'flex');
			}
			j++;
		});
	} else {
		$('.item-show').addClass('changeItem');
	}
	setTimeout(changeLatestNew, 4000);
}

function reloadAfterChangeSize()
{
	$(window).resize(function(event) {
		var width 		= $(window).width();

		if (width > 1150 || width < 460)
		{
			var j 			= 0;
			$('.item-show').removeClass('changeItem');
			var countItem 	= $('body').find('.item-show');
			countItem.each(function() {
				var target 	= $(this);
				if (j == 0)
				{
					target.css('order', 1);
					target.css('display', 'flex');
				}

				if (j == 1)
				{
					target.css('order', 2);
					target.css('display', 'flex');
				}

				if (j == 2)
				{
					target.css('order', 3);
					target.css('display', 'flex');
				}
				j++;
			});
		} else {
			$('.item-show').addClass('changeItem');
		}
	});
}

function showHeader()
{
	var windows		= $(window).width();
	if (windows <= 960 && windows > 374)
	{
		$('.container-find-out .icon, .container-email-us .icon, .container-contact-us .icon').on('click', function(){
			var target 	= $(this);
			target.parent().find('.content').css('display', 'block');
		});

		$('body').on('click', function(e){
			var target 	= e.target;
			if(!$(target).is('.container-find-out .icon, .top-menu-icon-1'))
			{
				$('.container-find-out .content').css('display', 'none');
			}
			if(!$(target).is('.container-email-us .icon, .top-menu-icon-2'))
			{
				$('.container-email-us .content').css('display', 'none');
			}
			if(!$(target).is('.container-contact-us .icon, .top-menu-icon-3'))
			{
				$('.container-contact-us .content').css('display', 'none');
			}
		});
	} else if (windows <= 374) {
		$('.container-find-out .icon, .container-email-us .icon, .container-contact-us .icon, .top-menu, .top-menu-group').on('click', function(){
			$('.top-menu').css('height', '150px');
			$('.more-info-mobile').css('flex-direction', 'column');
			$('.container-find-out .content, .container-email-us .content, .container-contact-us .content').css('display', 'block');
		});

		$('body').on('click', function(e){
			var target 	= e.target;
			if(!$(target).is('.container-find-out .icon, .top-menu-icon-1, .container-email-us .icon, .top-menu-icon-2, .container-contact-us .icon, .top-menu-icon-3, .top-menu, .top-menu-group'))
			{
				$('.top-menu').css('height', '50px');
				$('.more-info-mobile').css('flex-direction', 'row');
				$('.container-find-out .content, .container-email-us .content, .container-contact-us .content').css('display', 'none');
			}
		});
	} else {
		$('.container-find-out .content, .container-email-us .content, .container-contact-us .content').css('display', '');
	}
}

function showSearchMenu()
{
	$('body').on('click', '.icon-group .fa-search', function(){
		$('.panel-search-mobile').slideToggle(500);
	});
}

function changeLanguage()
{
	$('body').on('click', '.language-groups', function(){
		$('.others-language').slideToggle(500);
	})
}

$(document).ready(function() {
	slidebar();
	showSlide();
	categoryMenu();
	subMenuItem();
	aboutSubMenu();
	serviceSubMenu();
	hiddenMenu();
	countDown();
	AutoChangeItemSelling();
	slidebarItemSelling();
	showNavMenu();
	holdToChangeSlidebar();
	changeBackgroundMobile();
	changeLatestNew();
	showHeader();
	reloadAfterChangeSize();
	showSearchMenu();
	changeLanguage();
});