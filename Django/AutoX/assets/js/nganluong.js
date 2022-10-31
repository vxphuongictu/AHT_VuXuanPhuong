function get_bank()
{
    var payment_method 	= $('#payment_method').val();

	if (payment_method == "stripe")
	{
		$('.nganluong').css('display', 'none');
		$('.stripe').fadeIn(500);
		// Change value data on form
		$('#stripe_payment').val(1);
	} else {
		// Change value data on form
		$('#stripe_payment').val(0);
		$('.stripe').css('display', 'none');
		$('.nganluong').fadeIn(500);

		var csrf_token = $('input[name="csrfmiddlewaretoken"]').val();
		$.ajax({
			url: '/nganluong/get_bank/',
			type: 'POST',
			data: {
				payment_method: payment_method,
				csrfmiddlewaretoken: csrf_token
			},
			success: function (response) {
				var data = jQuery.parseJSON(response);
				$('#bank_code').empty();
				$.each(data, function (bank_code, bank_name) {
					$('#bank_code').append($('<option>', {
						value: bank_code,
						text: bank_code + ' - ' + bank_name
					}));
				});
			}
		});
	}
}