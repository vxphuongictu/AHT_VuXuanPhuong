<?php

namespace DgoraWcas\Integrations\Themes\TheGem;

use DgoraWcas\Abstracts\ThemeIntegration;
use DgoraWcas\Helpers;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class TheGem extends ThemeIntegration {
	public function extraFunctions() {
		add_filter( 'dgwt/wcas/settings', array( $this, 'registerSettingsExtra' ), 20 );

		add_action( 'init', function () {
			// Header Vertical
			remove_filter( 'wp_nav_menu_items', 'thegem_menu_item_search', 10, 2 );
			add_filter( 'wp_nav_menu_items', array( $this, 'replaceSearchInMenu' ), 10, 2 );

			// Header Fullwidth hamburger
			remove_filter( 'wp_nav_menu_items', 'thegem_menu_item_hamburger_widget', 100, 2 );
			add_action( 'thegem_before_nav_menu', function () {
				if ( in_array( thegem_get_option( 'header_layout' ), array( 'perspective', 'fullwidth_hamburger' ) ) ) {
					echo do_shortcode( '[wcas-search-form]' );
				}
			} );

			// Perspective header
			remove_filter( 'get_search_form', 'thegem_serch_form_vertical_header' );
			add_action( 'thegem_perspective_menu_buttons', function () {
				echo do_shortcode( '[wcas-search-form]' );
			} );

		} );

		add_filter( 'get_search_form', array( $this, 'removeSearchBarFromVerticalHeader' ), 100 );
		add_action( 'thegem_before_header', array( $this, 'addSearchBarToVerticalHeader' ), 20 );

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

	/**
	 * Add settings
	 *
	 * @param array $settings
	 *
	 * @return array
	 */
	public function registerSettingsExtra( $settings ) {
		// Remove overlay search from settings because enable mobile overlay will be forced
		if ( $this->canReplaceSearch() ) {
			unset( $settings['dgwt_wcas_form_body'][1300] );
			unset( $settings['dgwt_wcas_form_body'][1400] );
		}

		return $settings;
	}

	/**
	 * Replace the search in main menu
	 *
	 * @param $items
	 * @param $args
	 *
	 * @return string
	 */
	public function replaceSearchInMenu( $items, $args ) {
		if ( $args->theme_location == 'primary' && ! thegem_get_option( 'hide_search_icon' ) ) {
			$items .= '<li class="menu-item menu-item-search dgwt-wcas-thegem-menu-search">';
			$items .= '<a href="#"></a>';
			$items .= '<div class="minisearch">';
			$items .= do_shortcode( '[wcas-search-form]' );
			$items .= '</div>';
			$items .= '</li>';

		}

		return $items;
	}

	/**
	 * Remove the search bar from vertical header
	 *
	 * @param string $form
	 *
	 * @return string
	 */
	public function removeSearchBarFromVerticalHeader( $form ) {
		if ( in_array( thegem_get_option( 'header_layout' ), array( 'fullwidth_hamburger', 'vertical' ) ) ) {
			$form = '';
		}

		return $form;
	}

	/**
	 * Remove the search bar from vertical header
	 *
	 * @return void
	 */
	public function addSearchBarToVerticalHeader() {
		if ( ! in_array( thegem_get_option( 'header_layout' ), array( 'vertical' ) ) ) {
			return;
		}

		$html = '<div class="dgwt-wcas-thegem-vertical-search">';
		$html .= do_shortcode( '[wcas-search-form]' );
		$html .= '</div>';

		echo $html;
	}
}
