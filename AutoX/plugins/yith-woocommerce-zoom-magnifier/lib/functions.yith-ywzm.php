<?php
/**
 * Functions
 *
 * @author YITH
 */

if ( ! defined( 'YITH_WCMG' ) ) {
	exit;
} // Exit if accessed directly

if ( ! function_exists( 'yith_wcmg_is_enabled' ) ) {
	/**
	 * Check if the plugin is enabled for the current context
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	function yith_wcmg_is_enabled() {

		if ( wp_is_mobile() ) {
			if ( 'yes' === get_option( 'ywzm_hide_zoom_mobile' ) ){
				return false;
			}
			else{
				return true;
			}
		}
		else{
			if ( defined( 'YITH_YWZM_INIT' ) ) {
				return true;
			}
			else{
				return false;
			}
		}
	}
}

