function user_send_mail(e, csrf_token)
{
	var target 		= $(e);
	var product_id 	= target.attr('value');
	var message 	= $('#quick-quote-message-'+product_id).val();

	$.ajax({
			url 		: '/mail/mailForUser/',
			type 		: 'POST',
			data 		: {
				message 			: message,
				csrfmiddlewaretoken : csrf_token
			},
			beforeSend: function(){
				$('#btn-submit-quick-quote-'+product_id).prop('disabled', true);
				loader();
			},
			success: function (respone) {
				var data 	= JSON.parse(respone);
				if (data.status == true)
				{
					$('#result-quick-quote-'+product_id).html('Email has been sent!')
				} else {
					$('#result-quick-quote-'+product_id).html(data.msg);
				}
				$(target).prop('disabled', false);
				if (data.status == true)
				{
					$('#result-quick-quote-'+product_id).css('color', 'green');
				} else {
					$('#result-quick-quote-'+product_id).css('color', 'red');
				}
			}
		});
}

function user_send_mail_col_right(e, csrf_token)
{
	var target 		= $(e);
	var product_id 	= target.attr('value');
	var message 	= $('#quick-quote-message-col-right-'+product_id).val();
	var wp_admin 	= $('#wp_admin').val();

	$.ajax({
			url 		: '/mail/mailForUser/',
			type 		: 'POST',
			data 		: {
				message 			: message,
				csrfmiddlewaretoken : csrf_token
			},
			beforeSend: function(){
				$('.like-vehicle button').prop('disabled', true);
				loader();
			},
			success: function (respone) {
				var data 	= JSON.parse(respone);
				if (data.status == true)
				{
					$('#like-vehicle-'+product_id).html('Email has been sent!')
				} else {
					$('#like-vehicle-'+product_id).html(data.msg)
				}
				$(target).prop('disabled', false);
				if (data.status == true)
				{
					$('#like-vehicle-'+product_id).css('color', 'green');
				} else {
					$('#like-vehicle-'+product_id).css('color', 'red');
				}
			}
		});
}

function guest_send_mail(csrf_token){
	var full_name 	= $('#full-name').val();
	var phone_number= $('#phone-number').val();
	var email 		= $('#email').val();
	var details 	= $('#details').val();

	$.ajax({
			url 		: '/mail/mailForGuest/',
			type 		: 'POST',
			data 		: {
				full_name 			: full_name,
				phone_number 		: phone_number,
				email 				: email,
				message 			: details,
				csrfmiddlewaretoken : csrf_token
			},
			beforeSend: function(){
				loader();
			},
			success: function (respone) {
				var data 	= JSON.parse(respone);
				if (data.status == true)
				{
					$('.vehicle-form .content').html('Email has been sent!')
				} else {
					$('.vehicle-form .content').html(data.msg)
				}
			}
		});
}