<?php

namespace DgoraWcas\Integrations\Themes\Enfold;

use DgoraWcas\Abstracts\ThemeIntegration;
use DgoraWcas\Helpers;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Enfold extends ThemeIntegration {
	public function extraFunctions() {
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
			return 768;
		} );

		// Mark that the value of the option "mobile breakpoint" is forced
		add_filter( 'dgwt/wcas/settings/section=form', function ( $settings ) {
			$settings[690]['disabled'] = true;
			$settings[690]['label']    = Helpers::createOverrideTooltip( 'ovtt-storefront-breakpoint', Helpers::getOverrideOptionText( $this->themeName ) ) . $settings[690]['label'];

			return $settings;
		} );
	}
}
