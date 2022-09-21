function wishList(page_id, product_id)
{
	if ($('#product-'+product_id).parent('.btn-favorite').find('.exists').length > 0){
		removeToWishList(page_id, product_id);
	} else {
		addToWishList(page_id, product_id);
	}
}

function addToWishList (page_id, product_id) {
	$.ajax({
			url 	: yith_wcwl_l10n.ajax_url,
			type 	: 'post',
			data: {
				'action' 	: 'add_to_wishlist',
				'nonce'  	: yith_wcwl_l10n.nonce.add_to_wishlist_nonce,
				'context' 	: 'frontend',
				'add_to_wishlist': product_id,
				'product_type': 'simple'
			},
			beforeSend: function(){
				loader();
			},
			success: function(data)
			{
				get_data(page_id, 'home');
			}
		});
}

function removeToWishList(page_id, product_id)
{
	$.ajax({
			url 	: yith_wcwl_l10n.ajax_url,
			type 	: 'post',
			data: {
				'action' 	: 'remove_from_wishlist',
				'nonce'  	: yith_wcwl_l10n.nonce.remove_from_wishlist_nonce,
				'context' 	: 'frontend',
				'remove_from_wishlist': product_id,
				'product_type': 'simple'
			},
			beforeSend: function(){
				loader();
			},
			success: function(data)
			{
				get_data(page_id, 'home');
			}
		});
}