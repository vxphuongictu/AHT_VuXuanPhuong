<?php

namespace DgoraWcas\Integrations\Themes\Restoration;

use DgoraWcas\Abstracts\ThemeIntegration;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Restoration extends ThemeIntegration {
	public function extraFunctions() {
		add_filter( 'wc_get_template', function ( $template, $templateName ) {
			if ( ! empty( $templateName ) && $templateName === 'product-searchform.php' ) {
				$template = DGWT_WCAS_DIR . 'partials/themes/restoration-searchform.php';
			}

			return $template;
		}, 10, 5 );


		add_action( 'wp_head', function () { ?>
			<style>
				.thb-header-inline-search-inner .dgwt-wcas-sf-wrapp input[type=search].dgwt-wcas-search-input {
					border: none;
					background: transparent;
					color: #fff;
					text-align: center;
					padding-right: 40px;
				}

				.thb-header-inline-search-inner .dgwt-wcas-sf-wrapp input[type=search].dgwt-wcas-search-input:focus {
					box-shadow: none;
				}

				.thb-header-inline-search-inner .dgwt-wcas-sf-wrapp .dgwt-wcas-ico-magnifier {
					display: none;
				}

				.thb-header-inline-search-inner .dgwt-wcas-sf-wrapp input[type="search"].dgwt-wcas-search-input::placeholder {
					opacity: 0.8 !important;
					color: #fff;
				}
			</style>
			<?php
		} );
	}
}
