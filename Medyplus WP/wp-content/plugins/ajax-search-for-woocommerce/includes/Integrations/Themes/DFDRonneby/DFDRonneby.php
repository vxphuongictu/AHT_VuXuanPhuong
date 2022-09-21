<?php

namespace DgoraWcas\Integrations\Themes\DFDRonneby;

use DgoraWcas\Abstracts\ThemeIntegration;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class DFDRonneby extends ThemeIntegration {
	public function __construct( $themeSlug, $themeName ) {
		parent::__construct(
			$themeSlug,
			$themeName,
			array(
				'replaceSearchSuffix' => '<br><span style="color: red;">' . __( 'Note: We currently only support the header type: "Header 10"', 'ajax-search-for-woocommerce' ) . '</span>'
			)
		);
	}
}
