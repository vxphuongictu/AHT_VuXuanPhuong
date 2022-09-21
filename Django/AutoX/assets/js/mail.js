function user_send_mail(e)
{
	var target 		= $(e);
	var product_id 	= target.attr('value');
	var message 	= $('#quick-quote-message-'+product_id).val();
	var wp_admin 	= $('#wp_admin').val();

	$.ajax({
			url 		: wp_admin,
			type 		: 'post',
			data 		: {
				action 		: 'userMail',
				message 	: message
			},
			beforeSend: function(){
				$('#btn-submit-quick-quote-'+product_id).prop('disabled', true);
				loader();
			},
			success: function (respone) {
				var data 	= respone.data;
				$('#result-quick-quote-'+product_id).html(data.msg);
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

function user_send_mail_col_right(e)
{
	var target 		= $(e);
	var product_id 	= target.attr('value');
	var message 	= $('#quick-quote-message-col-right-'+product_id).val();
	var wp_admin 	= $('#wp_admin').val();

	$.ajax({
			url 		: wp_admin,
			type 		: 'post',
			data 		: {
				action 		: 'userMail',
				message 	: message
			},
			beforeSend: function(){
				$('.like-vehicle button').prop('disabled', true);
				loader();
			},
			success: function (respone) {
				var data 	= respone.data;
				$('#like-vehicle-'+product_id).html(data.msg)
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

function guest_send_mail(){
	var full_name 	= $('#full-name').val();
	var phone_number= $('#phone-number').val();
	var email 		= $('#email').val();
	var details 	= $('#details').val();

	var wp_admin 	= $('#wp_admin').val();

	$.ajax({
			url 		: wp_admin,
			type 		: 'post',
			data 		: {
				action 		: 'guestMail',
				full_name 	: full_name,
				phone_number: phone_number,
				email 		: email,
				message 	: details
			},
			beforeSend: function(){
				loader();
			},
			success: function (respone) {
				var data 	= respone.data;
				$('.vehicle-form .content').html(data.msg)
			}
		});
}