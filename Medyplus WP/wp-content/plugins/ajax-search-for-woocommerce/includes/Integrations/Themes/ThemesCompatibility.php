<?php

namespace DgoraWcas\Integrations\Themes;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ThemesCompatibility {
	private $themeSlug = '';
	private $themeName = '';
	private $parentThemeName = '';
	private $theme = null;
	private $supportActive = false;

	public function __construct() {
		$this->setCurrentTheme();

		$this->loadCompatibilities();
	}

	private function setCurrentTheme() {
		$theme = wp_get_theme();

		if ( is_object( $theme ) && is_a( $theme, 'WP_Theme' ) ) {
			$template        = $theme->get_template();
			$stylesheet      = $theme->get_stylesheet();
			$isChildTheme    = $template !== $stylesheet;
			$this->themeSlug = sanitize_title( $theme->Name );

			if ( $isChildTheme ) {
				$this->themeSlug = strtolower( $template );
			}

			$this->theme           = $theme;
			$this->themeName       = $theme->name;
			$this->parentThemeName = ! empty( $theme->parent_theme ) ? $theme->parent_theme : '';
		}

		$this->themeSlug = apply_filters( 'dgwt/wcas/integrations/themes/current_theme_slug', $this->themeSlug );
		$this->themeName = apply_filters( 'dgwt/wcas/integrations/themes/current_theme_name', $this->themeName );
		if ( $this->isChildTheme() ) {
			$this->parentThemeName = apply_filters( 'dgwt/wcas/integrations/themes/current_parent_theme_name', $this->parentThemeName );
		}
	}

	/**
	 *  All supported themes
	 *
	 * @return array
	 */
	public function supportedThemes() {
		return array(
			'storefront'       => array(
				'slug' => 'storefront',
				'name' => 'Storefront',
			),
			'flatsome'         => array(
				'slug' => 'flatsome',
				'name' => 'Flatsome',
			),
			'astra'            => array(
				'slug' => 'astra',
				'name' => 'Astra',
			),
			'thegem'           => array(
				'slug' => 'thegem',
				'name' => 'TheGem',
			),
			'impreza'          => array(
				'slug' => 'impreza',
				'name' => 'Impreza',
				'args' => array(
					'alwaysEnabled' => true,
				),
			),
			'woodmart'         => array(
				'slug' => 'woodmart',
				'name' => 'Woodmart',
			),
			'enfold'           => array(
				'slug' => 'enfold',
				'name' => 'Enfold',
			),
			'shopkeeper'       => array(
				'slug' => 'shopkeeper',
				'name' => 'Shopkeeper',
			),
			'the7'             => array(
				'slug' => 'the7',
				'name' => 'The7',
			),
			'dt-the7'          => array(
				'slug' => 'dt-the7',
				'name' => 'The7',
			),
			'avada'            => array(
				'slug' => 'avada',
				'name' => 'Avada',
			),
			'shop-isle'        => array(
				'slug' => 'shop-isle',
				'name' => 'Shop Isle',
			),
			'shopical'         => array(
				'slug' => 'shopical',
				'name' => 'Shopical',
			),
			'shopical-pro'     => array(
				'slug' => 'shopical-pro',
				'name' => 'ShopicalPro',
				'args' => array(
					'partialFilename' => 'shopical.php',
				)
			),
			'ekommart'         => array(
				'slug' => 'ekommart',
				'name' => 'Ekommart',
			),
			'savoy'            => array(
				'slug' => 'savoy',
				'name' => 'Savoy',
			),
			'sober'            => array(
				'slug' => 'sober',
				'name' => 'Sober',
			),
			'bridge'           => array(
				'slug' => 'bridge',
				'name' => 'Bridge',
			),
			'divi'             => array(
				'slug' => 'divi',
				'name' => 'Divi',
			),
			'block-shop'       => array(
				'slug' => 'block-shop',
				'name' => 'BlockShop',
			),
			'dfd-ronneby'      => array(
				'slug' => 'dfd-ronneby',
				'name' => 'DFDRonneby',
			),
			'restoration'      => array(
				'slug' => 'restoration',
				'name' => 'Restoration',
			),
			'salient'          => array(
				'slug' => 'salient',
				'name' => 'Salient',
			),
			'konte'            => array(
				'slug' => 'konte',
				'name' => 'Konte',
			),
			'rehub-theme'      => array(
				'slug' => 'rehub-theme',
				'name' => 'Rehub',
			),
			'supro'            => array(
				'slug' => 'supro',
				'name' => 'Supro',
			),
			'open-shop'        => array(
				'slug' => 'open-shop',
				'name' => 'OpenShop',
			),
			'ciyashop'         => array(
				'slug' => 'ciyashop',
				'name' => 'CiyaShop',
			),
			'bigcart'          => array(
				'slug' => 'bigcart',
				'name' => 'BigCart',
			),
			'top-store-pro'    => array(
				'slug' => 'top-store-pro',
				'name' => 'TopStorePro',
			),
			'top-store'        => array(
				'slug' => 'top-store',
				'name' => 'TopStore',
				'args' => array(
					'partialFilename' => 'top-store-pro.php',
				)
			),
			'goya'             => array(
				'slug' => 'goya',
				'name' => 'Goya',
			),
			'electro'          => array(
				'slug' => 'electro',
				'name' => 'Electro',
			),
			'shopisle-pro'     => array(
				'slug' => 'shopisle-pro',
				'name' => 'ShopIsle PRO',
				'args' => array(
					'partialFilename' => 'shop-isle.php',
				)
			),
			'estore'           => array(
				'slug' => 'estore',
				'name' => 'eStore',
			),
			'estore-pro'       => array(
				'slug' => 'estore-pro',
				'name' => 'eStore Pro',
				'args' => array(
					'partialFilename' => 'estore.php',
				)
			),
			'generatepress'    => array(
				'slug' => 'generatepress',
				'name' => 'GeneratePress',
			),
			'open-shop-pro'    => array(
				'slug' => 'open-shop-pro',
				'name' => 'Open Shop Pro',
				'args' => array(
					'partialFilename' => 'open-shop.php',
				)
			),
			'uncode'           => array(
				'slug' => 'uncode',
				'name' => 'Uncode',
			),
			'xstore'           => array(
				'slug' => 'xstore',
				'name' => 'XStore',
			),
			'kadence'          => array(
				'slug' => 'kadence',
				'name' => 'Kadence',
			),
			'thegem-elementor' => array(
				'slug' => 'thegem-elementor',
				'name' => 'TheGem (Elementor)',
			),
			'thegem-wpbakery'  => array(
				'slug' => 'thegem-wpbakery',
				'name' => 'TheGem (WPBakery)',
				'args' => array(
					'partialFilename' => 'thegem-elementor.php',
				)
			),
		);
	}

	/**
	 * Load class with compatibilities logic for current theme
	 *
	 * @return void
	 */
	private function loadCompatibilities() {
		foreach ( $this->supportedThemes() as $theme ) {
			if ( $theme['slug'] === $this->themeSlug ) {
				$this->supportActive = true;

				$class = '\\DgoraWcas\\Integrations\\Themes\\';

				if ( isset( $theme['className'] ) ) {
					$class .= $theme['className'] . '\\' . $theme['className'];
				} else {
					$class .= $theme['name'] . '\\' . $theme['name'];
				}

				$args = isset( $theme['args'] ) && is_array( $theme['args'] ) ? $theme['args'] : array();

				if ( $this->isWhiteLabel() ) {
					$args['whiteLabel'] = true;
				}

				if ( class_exists( $class ) ) {
					new $class( $this->themeSlug, $this->themeName, $args );
				} else {
					new GenericTheme( $this->themeSlug, $this->themeName, $args );
				}

				break;
			}
		}
	}

	/**
	 * Check if current theme is supported
	 *
	 * @return bool
	 */
	public function isCurrentThemeSupported() {
		return $this->supportActive;
	}

	/**
	 * Get current theme info
	 *
	 * @return null|object
	 */
	public function getTheme() {
		return $this->theme;
	}

	/**
	 * Get the name of the current theme
	 *
	 * @return string
	 */
	public function getThemeName() {
		return ! empty( $this->themeName ) && is_string( $this->themeName ) ? $this->themeName : '';
	}

	/**
	 * Check if the current them is child theme
	 *
	 * @return bool
	 */
	public function isChildTheme() {
		return ! empty( $this->parentThemeName );
	}

	/**
	 * Check if the integration is under white label
	 *
	 * @return bool
	 */
	public function isWhiteLabel() {
		return apply_filters( 'dgwt/wcas/integrations/themes/white_label', false );
	}

	/**
	 * Get the name of the current parent theme
	 *
	 * @return string
	 */
	public function getParentThemeName() {
		return ! empty( $this->parentThemeName ) ? $this->parentThemeName : '';
	}

	/**
	 * Get current theme image src
	 *
	 * @return string
	 */
	public function getThemeImageSrc() {
		$src = '';

		if ( ! empty( $this->theme ) ) {

			foreach ( array( 'png', 'jpg' ) as $ext ) {
				if ( empty( $src ) && file_exists( $this->theme->get_template_directory() . '/screenshot.' . $ext ) ) {
					$src = $this->theme->get_template_directory_uri() . '/screenshot.' . $ext;
					break;
				}
			}

		}

		return ! empty( $src ) ? esc_url( $src ) : '';
	}

}


