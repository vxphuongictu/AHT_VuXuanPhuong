<?php

namespace DgoraWcas\Integrations\Themes\Impreza;

use DgoraWcas\Abstracts\ThemeIntegration;
use DgoraWcas\Helpers;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Impreza extends ThemeIntegration {

	public function init() {
		add_filter( 'dgwt/wcas/settings', function ( $settings ) {
			$articleLink = 'https://fibosearch.com/documentation/themes-integrations/impreza-theme/';
			$articleText = sprintf( __( 'Here is <a href="%s" target="_blank">article</a> about how to do it using Impreza child-theme.', 'ajax-search-for-woocommerce' ), $articleLink );

			if ( isset( $settings['dgwt_wcas_basic'][52]['desc'] ) ) {
				$settings['dgwt_wcas_basic'][52]['desc'] .= '<br />' . $articleText;
			}

			return $settings;
		}, 20 );
	}

	public function extraFunctions() {
		add_filter( 'body_class', function ( $classes ) {
			$classes[] = 'dgwt-wcas-theme-' . $this->themeSlug;

			return $classes;
		} );

		// Force enable overlay for mobile search
		add_filter( 'dgwt/wcas/settings/load_value/key=enable_mobile_overlay', function () {
			return 'on';
		} );

		// Mark that the value of the option "mobile overlay" is forced
		add_filter( 'dgwt/wcas/settings/section=form', function ( $settings ) {
			$settings[680]['disabled'] = true;
			$settings[680]['label']    = Helpers::createOverrideTooltip( 'ovtt-storefront-mobile-overlay', Helpers::getOverrideOptionText( $this->themeName ) ) . $settings[680]['label'];

			return $settings;
		} );


		// Change mobile breakpoint to 768
		add_filter( 'dgwt/wcas/settings/load_value/key=mobile_breakpoint', function () {
			return 899;
		} );

		// Mark that the value of the option "mobile breakpoint" is forced
		add_filter( 'dgwt/wcas/settings/section=form', function ( $settings ) {
			$settings[690]['disabled'] = true;
			$settings[690]['label']    = Helpers::createOverrideTooltip( 'ovtt-storefront-breakpoint', Helpers::getOverrideOptionText( $this->themeName ) ) . $settings[690]['label'];

			return $settings;
		} );
	}
}
