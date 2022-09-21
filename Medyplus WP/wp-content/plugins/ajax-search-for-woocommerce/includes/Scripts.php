<?php

namespace DgoraWcas;

use DgoraWcas\Integrations\Solver;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Scripts {

	public function __construct() {

		add_action( 'wp_enqueue_scripts', array( $this, 'loadScripts' ) );

	}

	/**
	 * Loads scripts and styles
	 * Uses a WP hook wp_enqueue_scripts
	 *
	 * @return void
	 */

	public function loadScripts() {

		$min = SCRIPT_DEBUG ? '' : '.min';

		//Register
		wp_register_style( 'dgwt-wcas-style', apply_filters( 'dgwt/wcas/scripts/css_style_url', DGWT_WCAS_URL . 'assets/css/style' . $min . '.css' ), array(), DGWT_WCAS_VERSION );
		wp_register_script( 'jquery-dgwt-wcas', apply_filters( 'dgwt/wcas/scripts/js_url', DGWT_WCAS_URL . 'assets/js/search' . $min . '.js' ), array( 'jquery' ), DGWT_WCAS_VERSION, true );

		// Enqueue
		wp_enqueue_style( 'dgwt-wcas-style' );

		// Don't localize script if AMP is active
		if ( Helpers::isAMPEndpoint() ) {
			wp_register_style( 'dgwt-wcas-style-amp', apply_filters( 'dgwt/wcas/scripts/css_style_amp_url', DGWT_WCAS_URL . 'assets/css/style-amp' . $min . '.css' ), array(), DGWT_WCAS_VERSION );
			wp_enqueue_style( 'dgwt-wcas-style-amp' );

			return;
		}

		$localize = Helpers::getScriptsSettings();

		wp_localize_script( 'jquery-dgwt-wcas', 'dgwt_wcas', $localize );

	}

}
