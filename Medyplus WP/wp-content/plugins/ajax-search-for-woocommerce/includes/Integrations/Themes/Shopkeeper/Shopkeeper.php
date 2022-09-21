<?php

namespace DgoraWcas\Integrations\Themes\Shopkeeper;

use DgoraWcas\Abstracts\ThemeIntegration;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Shopkeeper extends ThemeIntegration {
	public function init() {
		add_filter( 'dgwt/wcas/settings', array( $this, 'registerSettingsExtra' ), 20 );
	}

	/**
	 * Add settings
	 *
	 * @param array $settings
	 *
	 * @return array
	 */
	public function registerSettingsExtra( $settings ) {
		$key = 'dgwt_wcas_basic';

		if ( class_exists( 'Shopkeeper_Opt' ) && ! \Shopkeeper_Opt::getOption( 'predictive_search', true ) ) {

			$desc = '<p>' . __( 'To replace the search bar you have to enable the "Predictive Search" option in the Shopkeeper settings.', 'ajax-search-for-woocommerce' ) . '<p>';
			$desc .= '<p>' . sprintf( __( 'Go to <code>Appearance -> <a target="_blank" href="%s">Customize</a> -> Header -> Search</code> and enable <code>Predictive Search</code>', 'ajax-search-for-woocommerce' ), admin_url( 'customize.php' ) ) . '<p>';


			$settings[ $key ][58] = array(
				'name'  => 'shopkeeper_replace_search_info',
				'label' => __( 'Warning!', 'ajax-search-for-woocommerce' ),
				'desc'  => $desc,
				'type'  => 'desc',
				'class' => 'dgwt-wcas-sgs-themes-label',
			);
		}

		return $settings;
	}
}
