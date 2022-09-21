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
		var register_nonce 		= $('#register_nonce').val();
		var wp_admin 			= $('#wp_admin').val();
		var full_name 		 	= $('#fullname_register').val();
		var user_register 		= $('#user_register').val();
		var email_register 		= $('#email_register').val();
		var new_pass 			= $('#new_pass').val();
		var user_confirm_pass 	= $('#user_confirm_pass').val();
		var agree 				= $('#agree').val();
		$.ajax({
				url 		: wp_admin,
				type 		: 'post',
				data: {
					action 	 			: 'register',
					register_nonce 		: register_nonce,
					full_name 			: full_name,
					user_register 		: user_register,
					email_register  	: email_register,
					new_pass 			: new_pass,
					user_confirm_pass 	: user_confirm_pass,
					agree 				: agree
				},
				success: function (respone) {
					var data 			= respone.data;
					var msg 			= data.msg;
					var status 			= data.status;

					$('.register-content p').html(msg);

					if (status === true)
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
		var wp_admin 			= $('#wp_admin').val();
		var login_nonce 		= $('#login_nonce').val();
		var user_pass 			= $('#user_pass').val();
		var user_login 		 	= $('#user_login').val();
		var rememberme 			= $('#rememberme').val();

		$.ajax({
				url 		: wp_admin,
				type 		: 'post',
				data: {
					action 	 			: 'login',
					login_nonce 		: login_nonce,
					user_login 			: user_login,
					user_pass 			: user_pass,
					rememberme  		: rememberme
				},
				success: function (respone) {
					var data 			= respone.data;
					var msg 			= data.msg;
					var status 			= data.status;

					$('.login-content p').html(msg);
					
					if (status === true)
					{
						location.reload();
					}
				}
			});
	})
	/* Login end */
})