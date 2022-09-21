function search()
{
	var result 			= []; // container products result
	var products 		= []; // it's data to show home page

	var input_value  	= $('.search-content').find('input').val().toLowerCase();
	if (input_value)
	{
		var data_pdc 	= data.products_data;
		data_pdc.forEach( function(element, index) {

			var char_same      = 0;
			var char_length    = 0; // count string of the name product
			var prdc_name      = element.product_name.toLowerCase();
			var arr_char_prdc  = []; // container char of name product

			/* get char in product name */
			for (let i = 0; i < prdc_name.length; i ++)
			{
				arr_char_prdc.push(prdc_name[i]);
				char_length++;
			}
			/* get char in product name end */

			/* get char in value input */
			for (let i = 0; i < input_value.length; i ++)
			{
				var char 	 	= input_value[i];
				if (char == arr_char_prdc[i])
				{
					char_same++; // compare char of both array
				}
			}
			/* get char in value input end */

			var percent 		= (char_length - char_same)/char_length*100;
			if (100 - percent >= 15) // if result > 15%
			{
				result.push(element);
			}

		});

		products['products_data'] = result;
		show_products(products, box_name, cat_name, page_id_log, page_name_log, max_item_per_page);
	} else {
		get_data(page_id_log, page_name_log);
	}
}