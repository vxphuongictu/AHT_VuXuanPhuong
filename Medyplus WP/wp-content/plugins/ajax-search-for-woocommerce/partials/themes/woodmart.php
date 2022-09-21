<?php

// Exit if accessed directly
if ( ! defined( 'DGWT_WCAS_FILE' ) ) {
	exit;
}

if ( ! function_exists( 'woodmart_search_form' ) ) {
	function woodmart_search_form( $args = array() ) {
		echo do_shortcode( '[wcas-search-form]' );
	}
}
