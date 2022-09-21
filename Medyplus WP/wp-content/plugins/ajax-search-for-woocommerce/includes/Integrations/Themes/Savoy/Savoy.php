<?php

namespace DgoraWcas\Integrations\Themes\Savoy;

use DgoraWcas\Abstracts\ThemeIntegration;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Savoy extends ThemeIntegration {
	public function extraFunctions() {
		add_filter( 'wc_get_template', array( $this, 'getTemplate' ), 10, 5 );
		add_filter( 'nm_header_default_links', array( $this, 'headerLinks' ) );

		add_action( 'wp_footer', array( $this, 'overwriteMobileSearch' ), 100 );
	}

	/**
	 * Overwrite search template
	 *
	 * @return string
	 */
	public function getTemplate( $template, $template_name, $args, $template_path, $default_path ) {
		if ( $template_name === 'product-searchform_nm.php' ) {
			$template = DGWT_WCAS_DIR . 'partials/themes/savoy/product-searchform_nm.php';
		}

		return $template;
	}

	/**
	 * Replace search icon in header
	 *
	 * @return array
	 */
	public function headerLinks( $links ) {
		if ( isset( $links['search'] ) ) {
			$links['search'] = '<li class="nm-menu-search menu-item">' . do_shortcode( '[wcas-search-form layout="icon"]' ) . '</li>';
		}

		return $links;
	}

	/**
	 * Overwrite search bar in mobile menu
	 *
	 * @return void
	 */
	public function overwriteMobileSearch() {
		global $nm_globals;
		if ( isset( $nm_globals['shop_search_header'] ) && $nm_globals['shop_search_header'] ) {
			echo '<div id="wcas-savoy-mobile-search" style="display: none;">' . do_shortcode( '[wcas-search-form]' ) . '</div>';
			?>
			<script>
				(function ($) {
					$(window).on('load', function () {
						$('.nm-mobile-menu-item-search').replaceWith($('#wcas-savoy-mobile-search > div'));
					});
				}(jQuery));
			</script>
			<?php
		}
	}
}
