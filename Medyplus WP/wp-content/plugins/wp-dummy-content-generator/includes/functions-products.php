<?php
function wp_dummy_content_generatorProducts(){
    include( WP_PLUGIN_DIR.'/'.plugin_dir_path(wp_dummy_content_generator_PLUGIN_BASE_URL) . 'admin/template/wp_dummy_content_generator-products.php');
}

function wp_dummy_content_generatorGenerateProducts($posttype='product',$wp_dummy_content_generatorIsThumbnail='off',$productHaveSamePrice='off',$productHaveSalesPrice='off'){
    include( WP_PLUGIN_DIR.'/'.plugin_dir_path(wp_dummy_content_generator_PLUGIN_BASE_URL) . 'Faker-main/vendor/autoload.php');
    // use the factory to create a Faker\Generator instance
    $wp_dummy_content_generatorFaker = Faker\Factory::create();
    $wp_dummy_content_generatorPostTitle = $wp_dummy_content_generatorFaker->text($maxNbChars = 10);
    $wp_dummy_content_generatorPostDescription = $wp_dummy_content_generatorFaker->text($maxNbChars = 700);
    $rand_num = rand(1,10);
    $wp_dummy_content_generatorPostThumb = WP_PLUGIN_DIR.'/'.plugin_dir_path(wp_dummy_content_generator_PLUGIN_BASE_URL) . 'images/products/'.$rand_num.".jpg";
    // create post
    $wp_dummy_content_generatorPostArray = array(
      'post_title'    => wp_strip_all_tags( $wp_dummy_content_generatorPostTitle ),
      'post_content'  => $wp_dummy_content_generatorPostDescription,
      'post_status'   => 'publish',
      'post_author'   => 1,
      'post_type' => $posttype
    );
    // Insert the post into the database
    $wp_dummy_content_generatorPostID = wp_insert_post( $wp_dummy_content_generatorPostArray );
    if($wp_dummy_content_generatorPostID){
        update_post_meta($wp_dummy_content_generatorPostID,'wp_dummy_content_generator_post','true');

        // update visibility, price etc
        // visibility
		update_post_meta( $wp_dummy_content_generatorPostID, '_visibility', 'visible' );

		// price
        if($productHaveSamePrice=='off'){
            $price = wc_format_decimal( floatval( rand( 1, 10000 ) ) / 100.0 );  
        }else{
            $price = 200;
        }
		update_post_meta( $wp_dummy_content_generatorPostID, '_price', $price );
		update_post_meta( $wp_dummy_content_generatorPostID, '_regular_price', $price );
		if($productHaveSalesPrice=='on'){
            update_post_meta( $wp_dummy_content_generatorPostID, '_sale_price', $price-1 );
        }
		// add categories
		$wp_dummy_content_generatorTerms = array();
		$wp_dummy_content_generator_cats = wp_dummy_content_generatorGetWcCategories();
		$wp_dummy_content_generatorCategoryNumber = count( $wp_dummy_content_generator_cats );
		$wp_dummy_content_generatorMax = rand( 1, 3 );
		for ( $i = 0; $i < $wp_dummy_content_generatorMax ; $i++ ) {
			$wp_dummy_content_generatorTerms[] = $wp_dummy_content_generator_cats[rand( 0, $wp_dummy_content_generatorCategoryNumber - 1 )]->term_id;
		}
		wp_set_object_terms( $wp_dummy_content_generatorPostID, $wp_dummy_content_generatorTerms, 'product_cat', true );


        if($wp_dummy_content_generatorIsThumbnail=='on')
        wp_dummy_content_generator_Generate_Featured_Image( $wp_dummy_content_generatorPostThumb,$wp_dummy_content_generatorPostID);
        return 'success';
    }else{
        return 'error';
    }

}
function wp_dummy_content_generatorGetWcCategories(){
	// since wordpress 4.5.0
	$args = array(
	    'taxonomy'   => "product_cat",
	    'hide_empty' =>  false,
	);
	$wp_dummy_content_generatorProductCategories = get_terms($args);
	return $wp_dummy_content_generatorProductCategories;
}
function wp_dummy_content_generatorAjaxGenProducts () {
    $wp_dummy_content_generatorIsThumbnail = 'off';
    $post_type = 'product';
    $remaining_products = sanitize_text_field($_POST['remaining_products']);
    $product_count = sanitize_text_field($_POST['wp_dummy_content_generator-product_count']);
    $productHaveSalePrice = sanitize_text_field($_POST['wp_dummy_content_generator-haveSalesPrice'])?sanitize_text_field($_POST['wp_dummy_content_generator-haveSalesPrice']):'off';
    $productHaveSamePrice = sanitize_text_field($_POST['wp_dummy_content_generator-haveSamePrice'])?sanitize_text_field($_POST['wp_dummy_content_generator-haveSamePrice']):'off';
  

    if($remaining_products>=5){
        $loopLimit = 5;
    }else{
        $loopLimit = $remaining_products;
    }


    $wp_dummy_content_generatorIsThumbnail = sanitize_text_field($_POST['wp_dummy_content_generator-thumbnail']);
    $counter = 0;
    for ($i=0; $i < $loopLimit ; $i++) { 
        $generationStatus = wp_dummy_content_generatorGenerateProducts($post_type,$wp_dummy_content_generatorIsThumbnail,$productHaveSamePrice,$productHaveSalePrice);
        if($generationStatus == 'success'){
            $counter++;
        }
    }
    if($remaining_products>=5){
        $remaining_products = $remaining_products - 5;
    }else{
        $remaining_products = 0;
    }
    echo json_encode(array('status' => 'success', 'message' => 'Products generated successfully.','remaining_products' => $remaining_products) );
    die();
}
add_action("wp_ajax_wp_dummy_content_generatorAjaxGenProducts", "wp_dummy_content_generatorAjaxGenProducts");
add_action("wp_ajax_nopriv_wp_dummy_content_generatorAjaxGenProducts", "wp_dummy_content_generatorAjaxGenProducts");

function wp_dummy_content_generatorGetFakeProductsList(){
    $args = array(
        'posts_per_page' => -1,
        'post_type' => 'product',
        'order' => 'DESC',
        'meta_query' => array(
            array(
                'key' => 'wp_dummy_content_generator_post',
                'value' => 'true',
                'compare' => '='
            ),
        )
    );
    $wp_dummy_content_generatorQueryData = new WP_Query( $args );
    return $wp_dummy_content_generatorQueryData;
}

function wp_dummy_content_generatorDeleteFakeProducts(){
    $wp_dummy_content_generatorQueryData = wp_dummy_content_generatorGetFakeProductsList();
    if ($wp_dummy_content_generatorQueryData->have_posts()) {
        while ( $wp_dummy_content_generatorQueryData->have_posts() ) :
            $wp_dummy_content_generatorQueryData->the_post();
            wp_delete_post(get_the_ID());
        endwhile;
    }
    wp_reset_postdata();
}

function wp_dummy_content_generatorDeleteProducts () {
    wp_dummy_content_generatorDeleteFakeProducts();
    echo json_encode(array('status' => 'success', 'message' => 'Data deleted successfully.') );
    die();
}
add_action("wp_ajax_wp_dummy_content_generatorDeleteProducts", "wp_dummy_content_generatorDeleteProducts");
add_action("wp_ajax_nopriv_wp_dummy_content_generatorDeleteProducts", "wp_dummy_content_generatorDeleteProducts");