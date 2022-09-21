<?php

namespace DgoraWcas\Integrations\Themes\Storefront;

use DgoraWcas\Abstracts\ThemeIntegration;
use DgoraWcas\Helpers;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Storefront extends ThemeIntegration {
	public function extraFunctions() {
		// Force enabling the option "mobile overlay"
		add_filter( 'dgwt/wcas/settings/load_value/key=enable_mobile_overlay', function () {
			return 'on';
		} );

		// Mark that the value of the option "mobile overlay" is forced
		add_filter( 'dgwt/wcas/settings/section=form', function ( $settings ) {
			$settings[680]['disabled'] = true;
			$settings[680]['label']    = Helpers::createOverrideTooltip( 'ovtt-storefront-mobile-overlay', Helpers::getOverrideOptionText( $this->themeName ) ) . $settings[680]['label'];

			return $settings;
		} );
	}
}
