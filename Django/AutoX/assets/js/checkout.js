$(document).ready(function(){

	/* Order now btn */
	$('body').on('click', '.order-now button[type="submit"]', function(){
		var payment_method_autox 	= $('#payment_method_autox').val();
		var order_data				= $('input[name="order-data"]').val();
		var fee_shipping 			= $('#fee_shipping').val();
		var data_card_number 		= $('#data_card_number').val();
		var data_card_fullname 		= $('#data_card_fullname').val();
		var data_card_month 		= $('#data_card_month').val();
		var data_card_year 			= $('#data_card_year').val();
		var stripe_payment 			= $('#stripe_payment').val();
		var email_stripe_payment 	= $('#email-payment').val();
		var csrf_token 				= $('input[name="csrfmiddlewaretoken"]').val();

		$.ajax({
			url: '/products/order/',
			type: 'POST',
			data: {
				csrfmiddlewaretoken: csrf_token,
				order_data: order_data,
				payment_method_autox: payment_method_autox,
				fee_shipping: fee_shipping,
				data_card_number: data_card_number,
				data_card_fullname: data_card_fullname,
				data_card_month: data_card_month,
				data_card_year: data_card_year,
				stripe_payment: stripe_payment,
				email_stripe_payment: email_stripe_payment
			},
			beforeSend: function () {
				loader();
				// disable btn submit checkout page
				$('.checkout-page .pay-method .pay-method-body .payment-method .order-body .btn-order .order-now button').prop('disabled', true);
				$('.checkout-page .pay-method .pay-method-body .payment-method .order-body .btn-order .order-now button').addClass('btn-disabled');
				$('.checkout-page .pay-method .pay-method-body .payment-method .order-body .btn-order .order-now').css('background-color', '#8080802e');
			},
			success: function (respone) {
				if (stripe_payment == 1)
				{
					var stripe 		= Stripe(respone.stripe_publishable_key);
					return stripe.redirectToCheckout({ sessionId: respone.sessionId });
				} else {
					var data 		= JSON.parse(respone);
					var product_id 	= data['product_id']; // array
					var order_id 	= data['order_id'];
					if (data['success'] == true) {
						if (payment_method_autox == "0") {
							if (data_card_number != "" && data_card_fullname != "") {
								var model_name = data['model_name'];
								$('.popup-' + model_name).fadeIn(500);
								$('.popup-' + model_name).css('display', 'flex');
								$('input[name="list_product"]').val(product_id);
								$('input[name="order_id"]').val(order_id);
							} else {
								var input_fields = $('.payment-body div');
								jQuery.each(input_fields, function (index, el) {
									$(el).find('.container-input').css({
										'border': '1px solid red'
									});
								});
							}
						} else {
							window.location.href = '/products/order-detail/' + order_id + '/';
						}
						$('.checkout-page .pay-method .pay-method-body .payment-method .order-body .btn-order .order-now button').prop('disabled', false);
						$('.checkout-page .pay-method .pay-method-body .payment-method .order-body .btn-order .order-now button').removeClass('.btn-disabled');
						$('.checkout-page .pay-method .pay-method-body .payment-method .order-body .btn-order .order-now').css('background-color', '#ffd101');
					}
				}
			}
		});
	});

	/* Confirm otp */
	$('body').on('click', '.popup-verify-panel button[type="submit"]', function(){
		var otp 			= $('#otp').val();
		var list_product 	= $('input[name="list_product"]').val();
		var order_id 		= $('input[name="order_id"]').val();
		var csrf_token 		= $('input[name="csrf_token_verify_otp"]').val();
		if (otp != "")
		{
			$.ajax({
					url 		: '/products/verify-otp/',
					type 		: 'POST',
					data 		: {
						csrfmiddlewaretoken 	: csrf_token,
						list_product 			: list_product,
						order_id 				: order_id,
						otp_code 				: otp
					},
					beforeSend: function() {
						loader();
					},
					success: function (respone) {
						var data 				= JSON.parse(respone);
						$('.popup-verify-panel .msg-result .msg').fadeOut(500);
						if (data['success'] == true)
						{
							window.location.href= '/products/order-detail/'+order_id+'/';
						} else {
							$('.popup-verify-panel .msg-result .msg').html(data['error']);
							$('.popup-verify-panel .msg-result .msg').fadeIn(500);
						}
					}
				});
		}
	});

	$('body').on('click', '.payment-method .option .box', function(e){
		var target 		= e.target;
		var value 		= $(target).find('.content').attr('value');
		if (!value)
		{
			value 		= $(target).attr('value');
		}
		$('#payment_method_autox').val(value);
	});
});

function nl_get_data_form()
{
	var card_fullname 	= $('#card_fullname').val();
	var card_number		= $('#card_number').val();
	var card_month 		= $('#card_month').val();
	var card_year 		= $('#card_year').val();
	var expiration_month= $('#expiration_month').val();
	var expiration_year = $('#expiration_year').val();

	$('#data_card_number').val(card_number);
	$('#data_card_fullname').val(card_fullname);

	if (card_month != "" && card_year != "")
	{
		$('#data_card_month').val(data_card_month);
		$('#data_card_year').val(data_card_year);
	} else {
		$('#data_card_month').val(expiration_month);
		$('#data_card_year').val(expiration_year);
	}
}

function check_field()
{
	var csrf_token 			= $('input[name="csrfmiddlewaretoken"]').val();
	var nl_payment_method 	= $('#payment_method').val();
	var nl_bank_code 		= $('#bank_code').val();
	var buyer_fullname 		= $('input[name="buyer_fullname"]').val();
	var buyer_email 		= $('input[name="buyer_email"]').val();
	var buyer_mobile 		= $('input[name="buyer_mobile"]').val();
	var buyer_address 		= $('input[name="buyer_address"]').val();
	if (nl_payment_method == 'stripe')
	{
		// enable btn submit checkout page
		$('.checkout-page .pay-method .pay-method-body .payment-method .order-body .btn-order .order-now button').prop('disabled', false);
		$('.checkout-page .pay-method .pay-method-body .payment-method .order-body .btn-order .order-now button').removeClass('btn-disabled');
		$('.checkout-page .pay-method .pay-method-body .payment-method .order-body .btn-order .order-now').css('background-color', '#ffd101');
		return 0;
	} else {
		$('.checkout-page .pay-method .pay-method-body .payment-method .order-body .btn-order .order-now button').prop('disabled', true);
		$('.checkout-page .pay-method .pay-method-body .payment-method .order-body .btn-order .order-now button').addClass('btn-disabled');
		$('.checkout-page .pay-method .pay-method-body .payment-method .order-body .btn-order .order-now').css('background-color', 'rgb(128, 128, 128)');
	}
	if (csrf_token != "")
	{
		if (nl_payment_method != "" && nl_bank_code != "" && buyer_fullname != "" && buyer_email != "" && buyer_mobile != "" && buyer_address != "")
		{
			$.ajax({
				url 	: '/nganluong/check-field/',
				type 	: 'post',
				data 	: {
					'csrfmiddlewaretoken' 	: csrf_token,
					'nl_payment_method'		: nl_payment_method,
					'nl_bank_code'			: nl_bank_code,
					'buyer_fullname'		: buyer_fullname,
					'buyer_email'			: buyer_email,
					'buyer_mobile'			: buyer_mobile,
					'buyer_address'			: buyer_address
				},
				beforeSend: function(){
					loader();
				},
				success : function (respone) {
					var all_element = $('.payment-body > div');
					all_element.each(function(index, el) {
						if (index > 0)
						{
							$(el).fadeOut(500);
						}
					});
					var data 	= JSON.parse(respone);
					if (data['success'] == true)
					{
						var fields 		= JSON.parse(data['fields']);
						if (fields['BANK_NAME'] == true)
						{
							$('.card-fullname').fadeIn(500);
						}

						if (fields['BANK_ACCOUNT'] == true)
						{
							$('.card-number').fadeIn(500);
						}

						if (fields['ISSUE_MONTH'] == true)
						{
							$('.card-month').fadeIn(500);
						}

						if (fields['ISSUE_YEAR'] == true)
						{
							$('.card-year').fadeIn(500);
						}

						if (fields['EXPIRED_MONTH'] == true)
						{
							$('.expiration-month').fadeIn(500);
						}

						if (fields['EXPIRED_YEAR'] == true)
						{
							$('.expiration-year').fadeIn(500);
						}
						// enable btn submit checkout page
						$('.checkout-page .pay-method .pay-method-body .payment-method .order-body .btn-order .order-now button').prop('disabled', false);
						$('.checkout-page .pay-method .pay-method-body .payment-method .order-body .btn-order .order-now button').removeClass('btn-disabled');
						$('.checkout-page .pay-method .pay-method-body .payment-method .order-body .btn-order .order-now').css('background-color', '#ffd101');
					}
				}
			});
		}
	}
}