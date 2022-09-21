<?php
class WoocommerceCustom
{

	/* Query check product is on wish list */
	function check_wish_list($product_id)
	{
		global $wpdb;

		$sql 		= "SELECT COUNT(*) FROM `wp_yith_wcwl` WHERE `prod_id` = '%d' AND `user_id` = '%d'";

		$args 		= [
			$product_id,
			get_current_user_id()
		];
		$query 		= $wpdb->get_var($wpdb->prepare($sql, $args));
		if ($query > 0)
		{
			return true;
		}
		return false;
	}

	function get_all_wish_list()
	{
		global $wpdb;
		$sql 		= "SELECT * FROM `wp_yith_wcwl` WHERE `user_id` = '%d'";

		$args 		= [
			get_current_user_id()
		];

		$query 		= $wpdb->get_results($wpdb->prepare($sql, $args));
		if ($query) {
			foreach ($query as $item)
			{
				$products[] 	= [
					'product_id' 	=> $item->prod_id,
					'quantity'	 	=> $item->quantity,
					'wishlist_id' 	=> $item->wishlist_id
				];
			}
		}

		$result 	= [
			'wish_list_count' 		=> $products ? sizeof($products) : 0,
			'wish_list_products' 	=> $products
		];
		return $result;
	}

	function get_info_products($categroyName)
	{
		$listProduct 	= [];
		$products 		= wc_get_products(
			[
				'status' 	=> 'publish',
				'category' 	=> $categroyName,
				'limit' 	=> false,
				'orderby' 	=> 'id',
				'order'	 	=> 'ASC'
			]
		);
		// setlocale(LC_MONETARY, "VN");
		foreach ($products as $product)
		{
			$product_id 			= $product->get_id();
			$product_name 			= $product->get_name();
			$status 				= $product->get_status();
			$short_description 		= $product->get_short_description();
			$product_link 			= $product->get_permalink($product_id);
			$is_wish_list			= $this->check_wish_list($product_id); // count wishlist's user id return true or false
			$kilometer_icon 		= get_field('kilometer_icon', $product_id);
			$kilometer_content 		= get_field('kilometer_content', $product_id);
			$fuel_icon 				= get_field('fuel_icon', $product_id);
			$fuel_content 			= get_field('fuel_content', $product_id);
			$unknow_icon 			= get_field('unknow_icon', $product_id);
			$unknow_content 		= get_field('unknow_content', $product_id);
			$clock_icon 			= get_field('clock_icon', $product_id);
			$clock_content 			= get_field('clock_content', $product_id);
			$cheaper_icon 			= get_field('cheaper_icon', $product_id);
			$cheaper_content 		= get_field('cheaper_content', $product_id);
			$auction_price_icon 	= get_field('auction_price_icon', $product_id);
			$auction_price_title	= get_field('auction_price_title', $product_id);
			$auction_price_content	= get_field('auction_price_content', $product_id);
			$remaining_time_icon 	= get_field('remaining_time_icon', $product_id);
			$time_remaining_content = get_field('time_remaining_content', $product_id);
			$ending_date_content 	= get_field('ending_date_content', $product_id);
			$quick_quote_title		= get_field('quick_quote_title', $product_id);
			$placeholder_quick_quote= get_field('placeholder_quick_quote', $product_id);
			$submit_content 		= get_field('submit_content', $product_id);

			$listProduct[] 			= [
				'category_name' 			=> $categroyName,
				'product_id'				=> $product_id,
				'product_name'				=> $product_name,
				'thumbnail_url' 			=> wp_get_attachment_image_src(get_post_thumbnail_id( $product_id ), 'full'),
				'status' 					=> $status,
				'short_description' 		=> $short_description,
				'product_link' 				=> $product_link,
				'is_wish_list'	 			=> $is_wish_list,
				'kilometer_icon' 			=> $kilometer_icon,
				'kilometer_content' 		=> $kilometer_content,
				'fuel_icon' 				=> $fuel_icon,
				'fuel_content' 				=> $fuel_content,
				'unknow_icon' 				=> $unknow_icon,
				'unknow_content' 			=> $unknow_content,
				'clock_icon' 				=> $clock_icon,
				'clock_content' 			=> $clock_content,
				'cheaper_icon' 				=> $cheaper_icon,
				'cheaper_content' 			=> $cheaper_content,
				'auction_price_icon' 		=> $auction_price_icon,
				'auction_price_title' 		=> $auction_price_title,
				'auction_price_content' 	=> $auction_price_content,
				'remaining_time_icon' 		=> $remaining_time_icon,
				'time_remaining_content' 	=> $time_remaining_content,
				'ending_date_content' 		=> $ending_date_content,
				'quick_quote_title' 		=> $quick_quote_title,
				'placeholder_quick_quote' 	=> $placeholder_quick_quote,
				'submit_content' 			=> $submit_content
			];
		}
		return $listProduct;
	}

	function wc_get_all_categories()
	{
		/* https://stackoverflow.com/questions/21009516/get-woocommerce-product-categories-from-wordpress */
		$arr_name     = [];
		$taxonomy     = 'product_cat';
		$orderby      = 'id';  
		$show_count   = 0;      // 1 for yes, 0 for no
		$pad_counts   = 0;      // 1 for yes, 0 for no
		$hierarchical = 1;      // 1 for yes, 0 for no  
		$title        = '';  
		$empty        = 0;

		$args = array(
			'taxonomy'     => 'product_cat',
			'orderby'      => $orderby,
			'order' 	   => 'ASC',
			'show_count'   => $show_count,
			'pad_counts'   => $pad_counts,
			'hierarchical' => $hierarchical,
			'title_li'     => $title,
			'hide_empty'   => $empty
		);
		$all_categories 	= get_categories( $args );
		foreach ($all_categories as $term)
		{
			$arr_name[]		= [
				'category_id' 		=> $term->term_id,
				'category_name' 	=> $term->name
			];
		}
		return $arr_name;
	}
}
?>