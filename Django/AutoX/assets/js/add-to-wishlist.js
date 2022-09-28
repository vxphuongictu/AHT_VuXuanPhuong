function wishList (product_id, csrf_token, page_name) {
	$.ajax({
			url 	: '/products/wishlist/'+product_id+'/',
			type 	: 'GET',
			data: {},
			beforeSend: function(){
				loader();
			},
			success: function(data)
			{
				if (page_name == "home")
				{
					home(csrf_token, 'home');
				} else {
					window.location.href = "";
				}
			}
		});
}