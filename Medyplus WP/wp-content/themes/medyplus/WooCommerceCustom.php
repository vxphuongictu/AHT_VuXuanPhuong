<?php
class WooCommerceCustom
{
	function __construct()
	{
		return true;
	}

	function get_info_product($categroyName, $productName)
	{
		$products 	= wc_get_products(
			[
				'status' 	=> 'publish',
				'category' 	=> $categroyName
			]
		);
		setlocale(LC_MONETARY, "en_US");
		foreach ($products as $product)
		{
			if ($product->get_status() == "publish")
			{
				if ($product->get_name() == $productName)
				{
					$result[] 	 	= [
						'postID' 	=> $product->get_id(),
						'postName' 	=> $product->get_name(),
						'postPrice'	=> $product->get_regular_price(),
						'postSale'	=> $product->get_sale_price(),
						'postLink' 	=> $product->get_permalink($product->get_id())
					];
				}
			}
		}
		return $result;
	}

	function wc_get_all_categories()
	{
		/* https://stackoverflow.com/questions/21009516/get-woocommerce-product-categories-from-wordpress */
		$arr_name     = [];
		$taxonomy     = 'product_cat';
		$orderby      = 'name';  
		$show_count   = 0;      // 1 for yes, 0 for no
		$pad_counts   = 0;      // 1 for yes, 0 for no
		$hierarchical = 1;      // 1 for yes, 0 for no  
		$title        = '';  
		$empty        = 0;

		$args = array(
			'taxonomy'     => 'product_cat',
			'orderby'      => $orderby,
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
				'id' 		=> $term->term_id,
				'name' 		=> $term->name
			];
		}
		return $arr_name;
	}
}
?>