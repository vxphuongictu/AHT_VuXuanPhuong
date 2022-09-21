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
		var full_name 		 	= $('#fullname_register').val();
		var user_register 		= $('#user_register').val();
		var email_register 		= $('#email_register').val();
		var new_pass 			= $('#new_pass').val();
		var user_confirm_pass 	= $('#user_confirm_pass').val();
		var agree 				= $('#agree').val();
		var csrf_token_register = $('#csrf_token_register').val();
		$.ajax({
				url 		: "./register/",
				type 		: 'post',
				data: {
					full_name 			: full_name,
					user_register 		: user_register,
					email_register  	: email_register,
					new_pass 			: new_pass,
					user_confirm_pass 	: user_confirm_pass,
					agree 				: agree,
					csrfmiddlewaretoken : csrf_token_register
				},
				success: function (respone) {
					var data 			= jQuery.parseJSON(respone);
					$('.register-content p').html(data.msg);

					if (data.status === "success")
					{
						$('.register-form-group').css('display', 'none');
						$('.login-form-group').toggle('500');
						$('.login-form-group').css('display', 'flex');
						$('#user_login').attr('value', user_register);
						$('#user_pass').attr('value', new_pass);
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
				url 		: "./login/",
				type 		: 'post',
				data: {
					user_login 				: user_login,
					user_pass 				: user_pass,
					csrfmiddlewaretoken 	: csrf_token,
					rememberme  			: rememberme
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
})