<?php

namespace DgoraWcas\Integrations\Themes\Woodmart;

use DgoraWcas\Abstracts\ThemeIntegration;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Woodmart extends ThemeIntegration {
	public function extraFunctions() {
		add_filter( 'woodmart_shop_page_link', array( $this, 'shop_page_link' ), 10, 3 );
	}

	/**
	 * Add to the address of the shop a parameter informing that the search is done by our plugin
	 *
	 * @param string $link
	 * @param boolean $keep_query
	 * @param string $taxonomy
	 *
	 * @return string
	 */
	public function shop_page_link( $link, $keep_query, $taxonomy ) {
		if ( $keep_query && isset( $_GET['dgwt_wcas'] ) ) {
			$link = add_query_arg( 'dgwt_wcas', '1', $link );
		}

		return $link;
	}
}
