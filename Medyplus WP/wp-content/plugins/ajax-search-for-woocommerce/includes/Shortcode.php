<?php

namespace DgoraWcas;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Shortcode {

	public static function register() {

		add_shortcode( 'wcas-search-form', array( __CLASS__, 'addBody' ) );
		add_shortcode( 'fibosearch', array( __CLASS__, 'addBody' ) );

	}

	/**
	 * Register Woo Ajax Search shortcode
	 *
	 * @param array $atts bool show_details_box
	 */
	public static function addBody( $atts, $content, $tag ) {
		$layout = Helpers::getLayoutSettings();

		$searchArgs = shortcode_atts( array(
			'class'                     => '',
			'style'                     => $layout->style,
			'icon'                      => $layout->icon,
			'layout'                    => $layout->layout,
			'layout_breakpoint'         => '',
			'mobile_overlay'            => $layout->mobile_overlay,
			'mobile_overlay_breakpoint' => '',
			'darken_bg'                 => $layout->darken_background,
			'submit_btn'                => null,
			'submit_text'               => null
		), $atts, $tag );

		$searchArgs['class'] .= empty( $searchArgs['class'] ) ? 'woocommerce' : ' woocommerce';

		$args = apply_filters( 'dgwt/wcas/shortcode/args', $searchArgs );

		return self::getForm( $args );
	}

	/**
	 * Display search form
	 *
	 * @param array args
	 *
	 * @return string
	 */

	public static function getForm( $args ) {

		// Enqueue required scripts (only if AMP is not active)
		if ( ! Helpers::isAMPEndpoint() ) {
			wp_enqueue_script( 'jquery-dgwt-wcas' );
			if ( DGWT_WCAS()->settings->getOption( 'show_details_box' ) === 'on' ) {
				wp_enqueue_script( 'woocommerce-general' );
			}
		}

		$args = self::mapAlternativeFormArgs( $args );

		$args = self::applyCondtitionalFormArgs( $args );

		ob_start();
		$filename = apply_filters( 'dgwt/wcas/form/partial_path', DGWT_WCAS_DIR . 'partials/search-form.php' );
		if ( file_exists( $filename ) ) {
			include $filename;

			if ( function_exists( 'opcache_invalidate' ) ) {
				@opcache_invalidate( $filename, true );
			}
		}
		$html = ob_get_clean();

		return apply_filters( 'dgwt/wcas/form/html', $html, $args );
	}

	/**
	 * Map alternative form of shortcode params values
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	public static function mapAlternativeFormArgs( $args ) {

		// Show submit button
		if ( isset( $args['submit_btn'] ) ) {
			if ( in_array( $args['submit_btn'], array( '1', 'yes', 'show' ) ) ) {
				$args['submit_btn'] = 'on';
			}

			if ( in_array( $args['submit_btn'], array( '0', 'no', 'hide' ) ) ) {
				$args['submit_btn'] = 'off';
			}
		}

		// Style: solaris, pirx
		if ( ! empty( $args['style'] ) ) {
			if ( in_array( $args['style'], array( 'default', 'classic' ) ) ) {
				$args['style'] = 'solaris';
			}
			if ( in_array( $args['style'], array( 'bean', 'rounded' ) ) ) {
				$args['style'] = 'pirx';
			}
		}

		// Layout: classic, icon, icon-flexible, icon-flexible-inv
		if ( ! empty( $args['layout'] ) ) {
			if ( in_array( $args['layout'], array( 'search-bar', 'default' ) ) ) {
				$args['layout'] = 'classic';
			}
			if ( in_array( $args['layout'], array( 'flex-icon-on-mobile', 'flex-icon-mob', ) ) ) {
				$args['layout'] = 'icon-flexible';
			}
			if ( in_array( $args['layout'], array( 'flex-icon-on-desktop', 'flex-icon-desktop', ) ) ) {
				$args['layout'] = 'icon-flexible-inv';
			}
		}

		return $args;
	}

	/**
	 * Apply some conditions before pass shortcode args forward
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	public static function applyCondtitionalFormArgs( $args ) {

		// Force requires options for Pirx style
		if ( ! empty( $args['style'] ) && $args['style'] === 'pirx' ) {
			$args['submit_btn']  = 'on';
			$args['submit_text'] = '';
		}

		return $args;
	}

}
