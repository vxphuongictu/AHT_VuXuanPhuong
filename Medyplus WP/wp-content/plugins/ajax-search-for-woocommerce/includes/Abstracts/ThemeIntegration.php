<?php

namespace DgoraWcas\Abstracts;

use DgoraWcas\Helpers;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Base class for integration with themes
 */
abstract class ThemeIntegration {

	protected $themeSlug = '';
	protected $themeName = '';
	protected $args = array();

	public function __construct( $themeSlug = '', $themeName = '', $args = array() ) {
		$this->themeSlug = $themeSlug;
		$this->themeName = $themeName;

		if ( empty( $this->themeName ) || empty( $this->themeSlug ) ) {
			return;
		}

		$this->args = wp_parse_args( $args, array(
			'replaceSearchSuffix' => '',
			'partialFilename'     => '',
			'alwaysEnabled'       => false,
			'whiteLabel'          => false
		) );

		$this->maybeOverwriteSearch();

		// Run additional functions on init.
		if ( is_callable( array( $this, 'init' ) ) ) {
			$this->init();
		}

		// Run additional functions besides loading the file with integration.
		if ( is_callable( array( $this, 'extraFunctions' ) ) && $this->canReplaceSearch() ) {
			$this->extraFunctions();
		}

		add_filter( 'dgwt/wcas/settings', array( $this, 'registerSettings' ) );
	}

	/**
	 * Add settings
	 *
	 * @param array $settings
	 *
	 * @return array
	 */
	public function registerSettings( $settings ) {
		$key = 'dgwt_wcas_basic';

		$settings[ $key ][10] = array(
			'name'  => $this->themeSlug . '_main_head',
			'label' => sprintf( __( 'Replace the search bars', 'ajax-search-for-woocommerce' ), $this->themeName ),
			'type'  => 'head',
			'class' => 'dgwt-wcas-sgs-header'
		);


		if ( ! $this->args['whiteLabel'] ) {
			$settings[ $key ][52] = array(
				'name'  => $this->themeSlug . '_settings_head',
				'label' => sprintf( __( '%s Theme', 'ajax-search-for-woocommerce' ), $this->themeName ),
				'type'  => 'desc',
				'desc'  => Helpers::embeddingInThemeHtml(),
				'class' => 'dgwt-wcas-sgs-themes-label',
			);


			$img = DGWT_WCAS()->themeCompatibility->getThemeImageSrc();
			if ( ! empty( $img ) ) {
				$settings[ $key ][52]['label'] = '<img src="' . $img . '">';
			}
		}

		if ( $this->args['whiteLabel'] ) {
			$replaceDesc = __( "Replace your theme's default search bars.", 'ajax-search-for-woocommerce' ) . $this->args['replaceSearchSuffix'];
		} else {
			$replaceDesc = __( 'Replace them', 'ajax-search-for-woocommerce' ) . $this->args['replaceSearchSuffix'];
		}

		if ( ! $this->args['alwaysEnabled'] ) {
			$settings[ $key ][55] = array(
				'name'    => $this->themeSlug . '_replace_search',
				'label'   => __( 'Search bars', 'ajax-search-for-woocommerce' ),
				'desc'    => $replaceDesc,
				'type'    => 'checkbox',
				'default' => 'off',
			);
		}

		$settings[ $key ][90] = array(
			'name'  => $this->themeSlug . '_othersways__head',
			'label' => __( 'Alternative ways to embed a search bar', 'ajax-search-for-woocommerce' ),
			'type'  => 'head',
			'class' => 'dgwt-wcas-sgs-header'
		);

		return $settings;
	}

	/**
	 * Check if can replace the native search form with the FiboSearch form.
	 *
	 * @return bool
	 */
	protected function canReplaceSearch() {
		$canIntegrate = false;

		if ( $this->args['alwaysEnabled'] ) {
			$canIntegrate = true;
		} elseif ( DGWT_WCAS()->settings->getOption( $this->themeSlug . '_replace_search', 'off' ) === 'on' ) {
			$canIntegrate = true;
		}

		return $canIntegrate;
	}

	/**
	 * Overwrite search
	 *
	 * @return void
	 */
	protected function maybeOverwriteSearch() {
		$partialFilename = ! empty( $this->args['partialFilename'] ) ? $this->args['partialFilename'] : $this->themeSlug . '.php';
		$partialPath     = DGWT_WCAS_DIR . 'partials/themes/' . $partialFilename;

		if ( $this->canReplaceSearch() && file_exists( $partialPath ) ) {
			require_once( $partialPath );
		}
	}
}
