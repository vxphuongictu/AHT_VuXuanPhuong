$(window).on("load", function() {
	var currentWidth 		= $(window).width();
	resize_window(currentWidth);
	rps_home_none_user(currentWidth);
	rps_home_after_login(currentWidth);
	rps_cart(currentWidth)

	$(window).resize(function(){
		var currentWidth 	= $(window).width();
		resize_window(currentWidth);
		rps_home_none_user(currentWidth);
		rps_home_after_login(currentWidth);
		rps_cart(currentWidth)
	});
});

function resize_window(currentWidth)
{
	if (currentWidth <= 425)
	{
		$('#register-submit-login-page').val('Register');
		$('#login-submit-register-page').val('Login');
	}
}

function rps_home_none_user(currentWidth)
{

	/* min width 2465px */
	if (currentWidth >= 3465)
	{
		var item = $('#list-item .product-item');
		jQuery.each(item, function(index, element){
			if (index > 0 && (index+1) % 3 == 0)
			{
				$(element).css('margin-right', 'unset');
			}
		})
	} else if (currentWidth >= 2350)
	{
		var item = $('#list-item .product-item');
		jQuery.each(item, function(index, element){
			if (index > 0 && (index+1) % 2 == 0)
			{
				$(element).css('margin-right', 'unset');
			}
		})
	}
	/* end min width 2350px */

	/* max width 2350px */
	if (currentWidth <= 2350)
	{
		var item = $('#list-item .product-item');
		jQuery.each(item, function(index, element){
			if (index > 0 && (index+1) % 2 == 0)
			{
				$(element).css('margin-right', 'unset');
			}
		})
	}
	/* end max width 2350px */

	/* max width 1100px */
	if (currentWidth <= 1100)
	{
		$('.go-to-top').draggable();
	}
	/* end max width 1100px */
}

function rps_home_after_login(currentWidth)
{
	if (currentWidth <= 768)
	{
		var categories 		= $('.categories button');

		jQuery.each(categories, function(index, val) {
			if ((index + 1) % 2 == 0)
			{
				$(val).css('margin-right', 'unset');
			}

		});
	}
}

function rps_cart(currentWidth)
{
	if (currentWidth <= 1100)
	{
		$('.list-view-cart').remove();
	} else {
		$('.list-view-cart-mobile').remove();
	}
}