function catulate_price_product(input_id)
{
	if (input_id)
	{
		var quantity 			= document.getElementById(input_id).value;
		var price 				= document.getElementById('price').value;
		var total_price 		= quantity * price;
		if (total_price > 0)
		{
			$('.total-single-product-price').html(formatter.format(total_price));
		}
	}
}

var formatter = new Intl.NumberFormat('en-US', {
  style: 'currency',
  currency: 'USD',

  // These options are needed to round to whole numbers if that's what you want.
  //minimumFractionDigits: 0, // (this suffices for whole numbers, but will print 2500.10 as $2,500.1)
  //maximumFractionDigits: 0, // (causes 2500.99 to be printed as $2,501)
});


$('document').ready(function() {
	$('body').on('click', '.up-sells-add-to-cart', function(event) {
		var target  	 	= $(this);
		var id_product 	= target.attr('value');

		$.ajax({
				url: document.location.href,
				type: 'post',
				data: {
					'add-to-cart'    : id_product,
					'quantity': 1
				},
				success: function (data) {
					location.href = 'http://localhost/cart/';
				}
			});
	});
})