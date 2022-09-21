$(document).ready(function() {
	var currentWidth 		= $(window).width();
	resize_window(currentWidth);

	$(window).resize(function(){
		var currentWidth 	= $(window).width();
		resize_window(currentWidth);
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