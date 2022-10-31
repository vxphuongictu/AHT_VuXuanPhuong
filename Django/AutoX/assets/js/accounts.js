$(document).ready(function(){

	$('body').on('click', '.list-menu ul li', function(e) {
		var target 	 	= $(this);
		var className 	= target.attr('class');
		
		/* Set style to default */
		jQuery.each($('.main-menu li'), function(index, element){
			$(element).css({
				'border-left' 		: '0',
				'background-color' 	: '#fff'
			});

			$(element).find('a').css('color', '#333');
		});
		/* end set style to default */

		/* Style after click button */
		$('.'+className).css({
			'border-left' 		: '2px solid #4b9eff',
			'background-color' 	: 'aliceblue'
		});

		$('.'+className).find('a').css('color', '#ffbe00');
		/* End style after click button */
	});

	$('body').on('click', '#update-information', function () {
		var fullname 		= $('#full-name').val();
		var email 			= $('#email').val();
		var phone 			= $('#phone').val();
		var avatar 			= $('#avatar').prop('files')[0];
		var current_pwd 	= $('#current-password').val();
		var new_pwd 		= $('#new-password').val();
		var confirm_pwd 	= $('#confirm-password').val();
		var csrf_token 		= $('#csrf_token').val()

		var updateFrom 		= new FormData();
		updateFrom.append('fullname', fullname);
		updateFrom.append('email', email);
		updateFrom.append('phone', phone);
		updateFrom.append('avatar', avatar);
		updateFrom.append('current_pwd', current_pwd);
		updateFrom.append('new_pwd', new_pwd);
		updateFrom.append('confirm_pwd', confirm_pwd);
		updateFrom.append('csrfmiddlewaretoken', csrf_token);

		$.ajax({
			url 	 	: '/users/profile/',
			type 	 	: 'POST',
			processData : false,
			contentType : false,
			data 	 	: updateFrom,
			beforeSend: function(){
				loader();
				$('.update-information tr td p').html('');
				$('.update-information tr td div').css('border', '1px solid #ede6e6');
			},
			success: function (data) {
				var respone 	= jQuery.parseJSON(data);
				if (respone.status == false)
				{
					if (respone.msg == "Password does not match")
					{
						$('#new-password').parents('.new-password').find('.input-container').css('border', '1px solid red');
						$('#confirm-password').parents('.confirm-password').find('.input-container').css('border', '1px solid red');
						$('.password-msg').html(respone.msg);
					} else if (respone.msg == "Incorrect password") {
						$('#current-password').parents('.current-password').find('.input-container').css('border', '1px solid red');
						$('.current-password-msg').html(respone.msg);
					}
				} else {
					window.location.href 	= '';
				}
			}
		});
	});

	$('body').on('click', '.main-menu li', function(event) {
		var target 		 	= event.target;
		var parentsClass 	= $(target).parents('li').attr('class');
		var panelName 		= parentsClass.split("-")[1];

		jQuery.each($('.panel-info > div'), function(index, element){
			var className 	= $(element).attr('class');
			if (panelName == className)
			{
				$(element).fadeIn(500);
			} else {
				$(element).css('display', 'none');
			}
		});
	});

	$(document).ready(function () {
	  $('#order-management-admin').DataTable();
	  $('.dataTables_length').addClass('bs-select');
	});

	$(document).ready(function () {
	  $('.profile-container #wishlist').DataTable();
	  $('.dataTables_length').addClass('bs-select');
	});

	$('body').on('click', '.admin tbody tr', function() {
		var order_id 	= $(this).attr('id');
		window.location.href 	= '/products/order-detail/'+order_id+'/';
	});
});