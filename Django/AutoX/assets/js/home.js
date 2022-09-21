/* Get data on db */
var data 				= "";
var page_id_log 		= "";
var page_name_log 		= "";
var box_name 		 	= "biddings"; // default box
var cat_name 		 	= "All"; // default cat
var max_item_per_page 	= 5; // default

function get_data(page_id, page_name)
{
	page_id_log		= page_id ? page_id : page_id_log;
	page_name_log 	= page_name ? page_name : page_name_log;

	admin_ajax 	= $('#wp_admin').attr('value');
	page_uri 	= $('#page_uri').attr('value');
	$.ajax({
			url 	: admin_ajax,
			type 	: 'post',
			data 	: {
				action 	: "getData"
			},
			success: function (respone) {
				data 		= respone.data;
				if (page_name == "home")
				{
					show_count_box_item(data);
					show_categories(data, page_uri);
					show_count_categories(data);
				} else {
					max_item_per_page 	= 6;
				}
				get_action(data, page_id, page_name);
			}
		});
}
/* Get data on db end */

/* listen mouse event when click box-item, cateogries, ... */
function get_action(data, page_id, page_name)
{

	show_products(data, box_name, cat_name, page_id, page_name, max_item_per_page); // show all products if don't click any element

	$('body').on('click', '.box-item', function(e) {
		box_name 		= $(this).attr('value');
		show_count_categories(data, box_name);
		show_products(data, box_name, cat_name, page_id, page_name, max_item_per_page);
	});

	$('body').on('click', '.category-option', function(e) {
		cat_name 		= $(this).find('.title').html();
		show_products(data, box_name, cat_name, page_id, page_name, max_item_per_page);
	});

	$('body').on('click', '#btn-see-more', function()
	{
		max_item_per_page = max_item_per_page + 6;
		show_products(data, box_name, cat_name, page_id, page_name, max_item_per_page);
	});
}
/* listen mouse event when click box-item, cateogries, ... end */

/* show products */
function show_products(data, box_name, cat_name, page_id, page_name, max_item_per_page)
{
	var products 		= [];
	var log_id 			= []; // filter ID

	(data.products_data).forEach( function(element, index) {

		var product_id 	= element.product_id;

		if (log_id.indexOf(product_id) < 0)
		{
			log_id.push(product_id);
			if (box_name == "favourites")
			{
				/* get data if click in favourite box item */
				if (element.is_wish_list === true)
				{
					if (cat_name == 'All')
					{
						products.push(element);
					} else if (cat_name == element.category_name) {
						products.push(element);
					}
				}
				/* get data if click in favourite box item */

			} else if (box_name == "results")
			{

				/* get data if click in result box item */
				var ending_date_content = element.ending_date_content;

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
					if (cat_name == 'All')
					{
						products.push(element);
					} else if (cat_name == element.category_name) {
						products.push(element);
					}
				}
				/* get data if click in result box item */

			} else {

				/* get data if click in biddings box item */
				if (cat_name == 'All')
				{
					products.push(element);
				} else if (cat_name == element.category_name) {
					products.push(element);
				}
				/* get data if click in biddings box item end */
			}
		}

	});

	$('#list-item').load(page_uri+'/custom_template/custom-product-home.php', 
		{
			data 		: JSON.stringify(products),
			page_id 	: page_id,
			page_name 	: page_name,
			cat_name 	: cat_name,
			max_item 	: max_item_per_page
		}, function(){
			$('#btn-see-more').prop('disabled', disabled = max_item_per_page >= products.length ? true : false);
		});

}
/* show products end */

/* show count categories */
function show_count_categories(data, box_name)
{
	/* show count of categories */
	var log_id 			= [];
	var cat_info 		= [];
	var totalCount 		= 0;

	(data.categories_data).forEach( function(element, index) {
		var count 		= 0;
		var cat_name 	= element.category_name;
		(data.products_data).forEach( function(element, index) {

			var product_id 	= element.product_id;

			if (box_name == 'favourites') {
				if (element.is_wish_list === true)
				{
					if (cat_name == element.category_name)
					{
						count++;
						totalCount++;
					}
				}
			} else if (box_name == 'results') {

				var date_time 		= element.ending_date_content;

				/* copy at countdown.js */
				var endTime 		= new Date(date_time);
				endTime 			= (Date.parse(endTime) / 1000);

				var now 			= new Date();
				now 				= (Date.parse(now) / 1000);

				var timeLeft 		= endTime - now;
				var days 			= Math.floor(timeLeft / 86400); 
				/* copy at countdown.js end */

				if (days < 0)
				{
					if (cat_name == element.category_name)
					{
						count++;
						totalCount++;
					}
				}
			} else {
				if (cat_name == element.category_name)
				{
					count++;
					totalCount++;
				}
			}
		});
		cat_info.push({
			'cat_name' 	 	 	: cat_name.replace(' ', ''),
			'products_count' 	: count
		})
	});

	$('#quantity-All').html("&nbsp;("+totalCount+")");
	cat_info.forEach( function(element, index) {
		$('#quantity-'+element.cat_name).html("&nbsp;("+element.products_count+")");
	});
	/* show count of categories end */
}
/* show count categories end */

/* show categories name */
function show_categories(data, page_uri)
{
	/* show category name */
	$('.categories').load(page_uri+'/custom_template/custom-categories-home.php', {data: JSON.stringify(data.categories_data)}, function(){
		show_count_categories(data);
	});
	/* show category name end */
}
/* show categories name end */

/* show count box item */
function show_count_box_item(data)
{
	/* show count biddings */
	$('#quantity-biddings').html("("+data.products_data.length+")");
	/* show count biddings end */

	/* show count wish list */
	$('#quantity-favourites').html("("+data.wish_list_data.wish_list_count+")");
	/* show count wish list end */

	/* show count result */
	var log_id 				= [];
	var countFinished 		= 0;

	(data.products_data).forEach( function(element, index) {

		var product_id 		= element.product_id;
		var date_time 		= element.ending_date_content;

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
				countFinished++;
			}
		}
	});
	$('#quantity-results').html("("+countFinished+")");
	/* show count result */
}
/* show count box item end */