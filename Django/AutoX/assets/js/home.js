/* Get data on db */
var data 				= "";
var page_name_log 		= "";
var box_name 		 	= "biddings"; // default box
var cat_id 		 		= 0; // default cat is All
var max_item_per_page 	= 5; // default
var wishlist_arr		= [];

function home(csrf_token, page_name)
{
	page_name_log 	= page_name ? page_name : page_name_log;

	$.ajax({
			url 	: '/rest/categories/',
			type 	: 'GET',
			data 	: {},
			success : function (respone) {
				data 		= respone.results;
				if (page_name == "home")
				{
					show_count_box_item(data);
					show_categories(data, csrf_token);
				} else {
					max_item_per_page 	= 6;
				}
				get_action(data, csrf_token);
			}
		});
}
/* Get data on db end */

/* listen mouse event when click box-item, cateogries, ... */
function get_action(data, csrf_token)
{

	show_products(data, box_name, cat_id, max_item_per_page, csrf_token); // show all products if don't click any element

	$('body').on('click', '.box-item', function(e) {
		box_name 		= $(this).attr('value');
		show_products(data, box_name, cat_id, max_item_per_page, csrf_token);
	});

	$('body').on('click', '.category-option', function(e) {
		var cat_id 		= $(this).val();
		show_products(data, box_name, cat_id, max_item_per_page, csrf_token);
	});

	$('body').on('click', '#btn-see-more', function()
	{
		max_item_per_page = max_item_per_page + 6;
		show_products(data, box_name, cat_id, max_item_per_page, csrf_token);
	});
}
/* listen mouse event when click box-item, cateogries, ... end */

/* show products */
function show_products(data, box_name, cat_id, max_item_per_page, csrf_token)
{
	//$('#product-'+element.prod_id).attr('class', 'fa fa-heart');
	var products 		= [];
	var log_id 			= []; // filter ID
	var list_products 	= [];

	(data).forEach( function(element, index) {
		if (element.cat)
		{
			(element.cat).forEach(function(element, index){
				list_products.push(element);
			});
		} else {
			list_products.push(element);
		}
	});

	list_products.forEach(function(element, index)
	{
		var product_id 	= element.id;

		if (log_id.indexOf(product_id) < 0)
		{
			log_id.push(product_id);
			if (box_name == "favourites")
			{
				/* get data if click in favourite box item */
				if (wishlist_arr[0].indexOf(product_id) >= 0)
				{
					if (cat_id == 0)
					{
						products.push(element);
					} else if (cat_id == element.category_id) {
						products.push(element);
					}
				}
				/* get data if click in favourite box item */

			} else if (box_name == "results")
			{

				/* get data if click in result box item */
				var ending_date_content = element.product[0].product_time_remaining
;

				/* copy at countdown.js */
				var endTime 		= new Date(ending_date_content);
				endTime 			= (Date.parse(endTime) / 1000);

				var now 			= new Date();
				now 				= (Date.parse(now) / 1000);

				var timeLeft 		= endTime - now;
				var days 			= Math.floor(timeLeft / 86400); 
				/* copy at countdown.js end */

				if (days < 0)
				{
					if (cat_id == 0)
					{
						products.push(element);
					} else if (cat_id == element.category_id) {
						products.push(element);
					}
				}
				/* get data if click in result box item */

			} else {

				/* get data if click in biddings box item */
				if (cat_id == 0)
				{
					products.push(element);
				} else if (cat_id == element.category_id) {
					products.push(element);
				}
				/* get data if click in biddings box item end */
			}
		}
	});

	$('#list-item').load('/products/products/', 
		{
			data 		 		: JSON.stringify(products),
			cat_id 				: cat_id,
			max_item 			: max_item_per_page,
			csrfmiddlewaretoken : csrf_token
		}, function(){
			$('#btn-see-more').prop('disabled', disabled = max_item_per_page >= products.length ? true : false);
		});

}
/* show products end */

/* show categories name */
function show_categories(data, csrf_token)
{
	/* show category name */
	$('.categories').load('/products/categories/', {categories: JSON.stringify(data), csrfmiddlewaretoken: csrf_token});
	/* show category name end */
}
/* show categories name end */

/* show count box item */
function show_count_box_item(data)
{
	var total_biddings 		= 0;
	var total_result 		= 0;
	var total_favourite 	= 0;

	/* show count biddings */
	data.forEach(function(element, index){
		(element.cat).forEach(function(item){
			total_biddings++;
		});
	});
	$('#quantity-biddings').html("("+total_biddings+")");
	/* show count biddings end */

	/* show count wish list */
	$.ajax({
			url 	: '/products/wishlist/',
			type 	: 'GET',
			data 	: {},
			success: function (respone) {
				var data 	= JSON.parse(respone);
				wishlist_arr.push(data);
				$('#quantity-favourites').html("("+data.length+")");
			}
		});
	/* show count wish list end */


	/* show count result */
	var log_id 				= [];

	data.forEach(function(element, index){
		(element.cat).forEach(function(item){
			var product_id 		= item.id;
			var date_time 		= item.product[0].product_time_remaining;

			if (log_id.indexOf(product_id) < 0)
			{
				log_id.push(product_id);
				/* copy at countdown.js */
				var endTime 		= new Date(date_time);
				endTime 			= (Date.parse(endTime) / 1000);

				var now 			= new Date();
				now 				= (Date.parse(now) / 1000);

				var timeLeft 		= endTime - now;
				var days 			= Math.floor(timeLeft / 86400); 
				/* copy at countdown.js end */

				if (days < 0) {
					total_result++;
				}
			}
		});
	});

	$('#quantity-results').html("("+total_result+")");
	/* show count result */
}
/* show count box item end */