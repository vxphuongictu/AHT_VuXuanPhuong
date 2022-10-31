function cancel_order(e)
{
	var target 		= e;
	var order_id 	= $(target).attr('value');

	$.ajax({
		url 	: '/products/cancel-order/'+order_id+'/',
		type 	: 'GET',
		data 	: {},
		success: function (response) {
			var data = jQuery.parseJSON(response);
			if  (data.status == true)
			{
				$(target).parents('.order-container').fadeOut(500);
			}
		}
	});
}

$(document).ready(function(){
	/* total checked */
	$('body').on('click', '.select-all, .select-all-content', function(event) {
		var target 				= event.target;
		var allItem 			= $('.product-item');

		if ($(target).attr('class') == 'select-all-content')
		{
			if ($(target).parents('.check-all').find('.select-all').is(":checked") == false)
			{
				jQuery.each(allItem, function(index, val) {
					$(val).find('.checkbox input').prop('checked', true);
					$('.select-all').prop('checked', true);
				});
			} else {
				jQuery.each(allItem, function(index, val) {
					$(val).find('.checkbox input').prop('checked', false);
					$('.select-all').prop('checked', false);
				});
			}
		} else if ($(this).is(":checked"))
		{
			jQuery.each(allItem, function(index, val) {
				$(val).find('.checkbox input').prop('checked', true);
				$('.select-all').prop('checked', true);
			});
		} else {
			jQuery.each(allItem, function(index, val) {
				$(val).find('.checkbox input').prop('checked', false);
				$('.select-all').prop('checked', false);
			});
		}

		/* create data post */
		var datalist 			= [];
		var numberOfChecked 	= $('.checked-prd:checkbox:checked');

		jQuery.each(numberOfChecked, function(index, el) {
			var parents 		= $(el).parents('.product-item');
			var product_id 		= $(parents).attr('value');
			var product_price 	= $(parents).find('.price').text().replaceAll(',', '').replace('VND', '');
			var product_quantity= $(parents).find('.input-quantity input').val();

			var payload 		= {
				'product_id'		: product_id,
				'product_price' 	: product_price,
				'product_quantity' 	: product_quantity
			}
			datalist.push(payload);
		});

		if (datalist.length > 0)
		{
			$('#data-checkout').attr('value', JSON.stringify(datalist));
		}
	});

	/* Total price */
	$('body').on('click', '.checked-prd, .select-all, .down-btn, .up-btn, .select-all-content', function(){
		var numberOfChecked 	= $('input.checked-prd:checkbox:checked').length;
		$('.num-product').html(numberOfChecked);

		var totalPrice 			= 0;
		var checked_item 		= $('.checked-prd:checkbox:checked');
		jQuery.each(checked_item, function(index, el) {
			var price  			= $(el).parents('.product-item').find('.total-price').text();
			price 				= price.replaceAll(',', '').replace('VND', '');
			totalPrice+=Number(price);
		});
		$('.all-price').html(totalPrice.toLocaleString());
	});

	/* show popup add address */
	$('body').on('click', '.popup-setup-address', function(){
		$('.popup-add-address').fadeIn(500);
		$('.popup-add-address').css('display', 'flex');
	});

	$('body').on('click', '.address-footer *', function(){
		$('.popup-add-address').css('display', 'none');
	});

	/* Get Address Type */
	$('body').on('click', '.select-type *', function(event){
		var target 	 	= event.target;
		var value 		= $(target).attr('value');
		$('.addressType').val(value);
	});

	/* use as default address */
	$('body').on('click', '.use-to-default', function (event) {
		var target 		= event.target;
		if ($(target).find('.set-to-default').is(":checked"))
		{
			$(target).find('.set-to-default').prop('checked', false);
		} else {
			$(target).find('.set-to-default').prop('checked', true);
		}
	});

	/* Change address */
	$('body').on('click', '.change-address', function(event) {
		$('.popup-change-address').fadeIn(500);
		$('.popup-change-address').css('display', 'flex');
	});

	/* add new location btn */
	$('body').on('click', '.border-input span', function(event) {
		$('.popup-change-address').css('display', 'none');
		$('.popup-add-address').fadeIn(500);
		$('.popup-add-address').css('display', 'flex');
	});

	/* Change color when select address type */
	$('body').on('click', '.select-type *', function(event) {
		var all_btn 	= $('.select-type .box');
		jQuery.each(all_btn, function(index, element){
			$(element).css({
				'color'	: '#917e7e52',
				'border': '1px solid #917e7e52'
			})
		});
		var target 		= event.target;
		var box 		= $(target).parents('.box');
		box.css({
			'color' 	: '#ee4d2d',
			'border' 	: '1px solid #ee4d2d'
		});
	});

	/* btn popup close */
	$('body').on('click', '.popup-change-address .confirm-group span', function(event) {
		$('.popup-change-address').css('display', 'none');
	});

	/* payment method choice */
	$('body').on('click', '.payment-method .option .box, .payment-method .option .box span', function (event) {
		var target 	= event.target;

		var method 	= $(target).parents('.box').find('.content').attr('value');
		if (!method)
		{
			method 	= $(target).find('.content').attr('value');
		}

		var all_btn = $('.box').find('.check');

		jQuery.each(all_btn, function(index, element){
			$(element).css('display', 'none');
		});

		$(target).parents('.box').find('.check').fadeIn(500);
		$(target).parents('.box').find('.check').css('display', 'flex');

		$(target).find('.check').fadeIn(500);
		$(target).find('.check').css('display', 'flex');

		if (method == 0)
		{
			$('.payment-method .payment').fadeIn(500);
			$('#payment-method').val(0);
			// disable btn submit checkout page
			$('.checkout-page .pay-method .pay-method-body .payment-method .order-body .btn-order .order-now button').prop('disabled', true);
			$('.checkout-page .pay-method .pay-method-body .payment-method .order-body .btn-order .order-now button').addClass('btn-disabled');
			$('.checkout-page .pay-method .pay-method-body .payment-method .order-body .btn-order .order-now').css('background-color', '#8080802e');
		} else {
			$('.payment-method .payment').fadeOut(500);
			$('#payment-method').val(1);
			// enable btn submit checkout page
			$('.checkout-page .pay-method .pay-method-body .payment-method .order-body .btn-order .order-now button').prop('disabled', false);
			$('.checkout-page .pay-method .pay-method-body .payment-method .order-body .btn-order .order-now button').removeClass('btn-disabled');
			$('.checkout-page .pay-method .pay-method-body .payment-method .order-body .btn-order .order-now').css('background-color', '#ffd101');
		}
	});

	/* help cvv */
	$('body').on('click', '.checkout-page .pay-method .pay-method-body .payment-body .input-group-inline > div:last-child .container-input > i', function(event) {
		$('.popup-cvv-help').fadeIn(500);
		$('.popup-cvv-help').css('display', 'flex');
	});

	/* close popup help cvv */
	$('body').on('click', '.checkout-page .popup-cvv-help .panel-cvv-help .body .footer-btn .btn-box span', function(event) {
		$('.popup-cvv-help').fadeOut(500);
	});

	/* Click Change Order status of admin */
	$('body').on('click', '#btn-order-done', function (){
		$('.popup-change-order').fadeIn(500);
		$('.popup-change-order').css('display', 'flex');
	});

	/* order one product on cart */
	$('body').on('click', '.checked-prd', function(){
		var datalist 			= [];
		var numberOfChecked 	= $('.checked-prd:checkbox:checked');

		jQuery.each(numberOfChecked, function(index, el) {
			var parents 		= $(el).parents('.product-item');
			var product_id 		= $(parents).attr('value');
			var product_price 	= $(parents).find('.price').text().replaceAll(',', '').replace('VND', '');
			var product_quantity= $(parents).find('.input-quantity input').val();

			var payload 		= {
				'product_id'		: product_id,
				'product_price' 	: product_price,
				'product_quantity' 	: product_quantity
			}
			datalist.push(payload);
		});

		if (datalist.length > 0)
		{
			$('#data-checkout').attr('value', JSON.stringify(datalist));
		}
	});

	$('body').on('click', '.popup-change-order .body .btn-container button', function(e) {
		var all_btn 	= $('.popup-change-order .body .btn-container button');
		jQuery.each(all_btn, function(index, element) {
			$(element).parents('.btn-container').css({
				'transform' : '',
				'box-shadow': ''
			})
		});

		var target 	 	= e.target;
		$(target).addClass('current-select');
		$(target).parents('.btn-container').css({
			'transform' : 'scale(1.1)',
			'box-shadow': 'rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset'
		});
	})

	$('body').on('click', '.popup-change-order .popup-container .btn-container-foot button', function(){
		var value  		= $('.current-select').attr('value');
		var csrf_token 	= $('input[name="csrfmiddlewaretoken"]').val();

		$.ajax({
				url 	: '',
				type 	: 'post',
				data 	: {
					value				: value,
					csrfmiddlewaretoken : csrf_token
				},
				success: function (respone) {
					window.location.href = '';
				}
			});
	})

	$('body').on('click', '.popup-change-order .popup-container .close-foot button', function(){
		$('.popup-change-order').fadeOut(500);
	})
})