<?php

namespace DgoraWcas;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Setup {

	public function init() {
		add_action( 'init', array( $this, 'setImageSize' ) );
	}

	/**
	 * Register custom image size
	 * @return void
	 */
	public function setImageSize() {
		add_image_size( 'dgwt-wcas-product-suggestion', 64, 0, false );
	}

}
