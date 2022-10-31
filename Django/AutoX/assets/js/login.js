/* add placeholder login form */
$(document).ready(function(){
	$('body').on('click', '#register-submit-login-page', function(e){
		$('.login-form-group').css('display', 'none')
		$('.register-form-group').toggle('500');
		$('.register-form-group').css('display', 'flex');
	});

	$('body').on('click', '#login-submit-register-page', function(e){
		$('.register-form-group').css('display', 'none');
		$('.login-form-group').toggle('500');
		$('.login-form-group').css('display', 'flex');
	});

	/* register */
	$('body').on('click', '#wp-submit-register', function(){
		var username 			= $('#register_username').val();
		var phone_number 		= $('#register_phone').val();
		var email 				= $('#email_register').val();
		var full_name 		 	= $('#fullname_register').val();
		var new_pass 			= $('#new_pass').val();
		var user_confirm_pass 	= $('#user_confirm_pass').val();
		var agree 				= $('#agree').val();
		var csrf_token_register = $('#csrf_token_register').val();
		$.ajax({
				url 		: "/users/register/",
				type 		: 'POST',
				data: {
					username 			: username,
					phone_number		: phone_number,
					email  				: email,
					full_name 			: full_name,
					new_pass 			: new_pass,
					user_confirm_pass 	: user_confirm_pass,
					agree 				: agree,
					csrfmiddlewaretoken : csrf_token_register
				},
				beforeSend: function ()
				{
					loader();
				},
				success: function (respone) {
					var data 			= jQuery.parseJSON(respone);

					if (data.status === "success")
					{
						$('.register-form-group').css('display', 'none');
						$('.login-form-group').toggle('500');
						$('.login-form-group').css('display', 'flex');
						$('#user_login').attr('value', username);
						$('#user_pass').attr('value', new_pass);
						$('.login-content p').html(data.msg);
					} else {
						$('.register-content p').html(data.msg);
					}
				}
			});
	});
	/* Register end */

	/* Login */
	$('body').on('click', '#wp-submit-login', function(){
		var user_login 			= $('#user_login').val();
		var user_pass 		 	= $('#user_pass').val();
		var rememberme 			= $('#rememberme').val();
		var csrf_token 			= $('#csrf_token').val();

		$.ajax({
				url 		: "/users/login/",
				type 		: 'post',
				data: {
					user_login 				: user_login,
					user_pass 				: user_pass,
					csrfmiddlewaretoken 	: csrf_token,
					rememberme  			: rememberme
				},
				beforeSend: function ()
				{
					loader();
				},
				success: function (respone) {
					var data  	= jQuery.parseJSON(respone);
					if(data.status == "success")
					{
						location.reload();
					}
					$('.login-content p').html(data.msg);
				},
				error: function () {
					var msg 	= "Please refresh the page and try again"
					$('.login-content p').html(msg);
				}
			});
	})
	/* Login end */

	/* resend activation link */
	$('body').on('click', '.resend-mail-activate', function(event) {
		var username   	= $('#user_login').val();
		var csrf_token 	= $('#csrf_token').val();
		$.ajax({
				url  		: '/users/resend/',
				type 		: 'post',
				data 		: {
					username 			: username,
					csrfmiddlewaretoken : csrf_token
				},
				beforeSend 	: function(){
					loader();
				},
				success 	: function (data) {
					var respone 	= jQuery.parseJSON(data);
					if (respone.status == true)
					{
						$('.login-content p').html(respone.msg);
					} else {
						$('.login-content p').html("Error! Please contact admin to problem sloving");
					}
				}
			});
	});
	/* end resend activation link */
})