<?php

namespace DgoraWcas\Integrations;

use DgoraWcas\Helpers;
use DgoraWcas\Term;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Brands
 * @package DgoraWcas\Integrations
 *
 * Support for plugins:
 * 1. WooCommerce Brands since v1.6.9 by WooCommerce
 * 2. YITH WooCommerce Brands Add-on since v1.3.3 by YITH
 * 3. Perfect WooCommerce Brands since v1.8.3 by Alberto de Vera Sevilla
 * 4. Martfury Addons since v2.2.2 by drfuri.com
 * 5. Brands for WooCommerce since v3.5.2 by BeRocket
 * 6. WP Bingo by wpbingo
 * 7. Premmerce Brands for WooCommerce since v1.2.11 by premmerce
 */
class Brands {
	/**
	 * Brands plugin metadata
	 *
	 * @var array
	 */
	private $pluginInfo = array();

	/**
	 * Brands plugin slug
	 *
	 * @var string
	 */
	private $pluginSlug = '';

	/**
	 * Brand taxonomy name
	 *
	 * @var string
	 */
	private $brandTaxonomy = '';

	public function __construct() {
	}

	public function init() {
		// Init brand stuff after theme is ready (to allow apply filters)
		add_action( 'init', function () {
			$this->setPluginInfo();
			$this->setBrandTaxonomy();
			$this->addSettings();
		}, 100 );

		add_filter( 'dgwt/wcas/suggestion_details/taxonomy/headline', array( $this, 'rebuildDetailsHeader' ), 10, 4 );
		add_filter( 'dgwt/wcas/term/thumbnail_src', array( $this, 'termThumbnailSrc' ), 10, 4 );
		add_filter( 'dgwt/wcas/indexer/taxonomies', array( $this, 'addBrandToCollectionOfTaxonomies' ), 5 );
	}

	/**
	 * Set current brands vendor plugin
	 *
	 * @return void
	 */
	private function setPluginInfo() {
		foreach ( $this->getBrandsPlugins() as $pluginPath ) {
			if ( is_plugin_active( $pluginPath ) ) {

				$file = WP_PLUGIN_DIR . '/' . $pluginPath;

				if ( file_exists( $file ) ) {
					$this->pluginInfo = get_plugin_data( $file );
					$this->pluginSlug = $pluginPath;
				}

				break;
			}
		}
	}

	/**
	 * Set brand taxonomy name
	 *
	 * @return void
	 */
	private function setBrandTaxonomy() {
		$brandTaxonomy = '';

		if ( ! empty( $this->pluginSlug ) ) {
			switch ( $this->pluginSlug ) {
				case 'woocommerce-brands/woocommerce-brands.php':
				case 'martfury-addons/martfury-addons.php':
				case 'wpbingo/wpbingo.php':
				case 'premmerce-woocommerce-brands/premmerce-brands.php':
					$brandTaxonomy = 'product_brand';
					break;
				case 'yith-woocommerce-brands-add-on-premium/init.php':
				case 'yith-woocommerce-brands-add-on/init.php':
					$brandTaxonomy = 'yith_product_brand';
					break;
				case 'perfect-woocommerce-brands/main.php':
				case 'perfect-woocommerce-brands/perfect-woocommerce-brands.php':
					$brandTaxonomy = 'pwb-brand';
					break;
				case 'brands-for-woocommerce/woocommerce-brand.php':
					$brandTaxonomy = 'berocket_brand';
					break;
			}
		}

		$brandTaxonomy = apply_filters( 'dgwt/wcas/brands/taxonomy', $brandTaxonomy );

		if ( ! empty( $brandTaxonomy ) && taxonomy_exists( $brandTaxonomy ) ) {
			$this->brandTaxonomy = $brandTaxonomy;
		}
	}

	/**
	 * Get all supported brands plugins files
	 *
	 * @return array
	 */
	public function getBrandsPlugins() {
		return array(
			'woocommerce-brands/woocommerce-brands.php',
			'yith-woocommerce-brands-add-on/init.php',
			'yith-woocommerce-brands-add-on-premium/init.php',
			'perfect-woocommerce-brands/main.php',
			'perfect-woocommerce-brands/perfect-woocommerce-brands.php',
			'martfury-addons/martfury-addons.php',
			'brands-for-woocommerce/woocommerce-brand.php',
			'wpbingo/wpbingo.php',
			'premmerce-woocommerce-brands/premmerce-brands.php',
		);
	}

	/**
	 * Check if some brands plugin is enabled
	 *
	 * @return bool
	 */
	public function hasBrands() {
		return ! empty( $this->brandTaxonomy );
	}

	/**
	 * Get brand taxonomy
	 *
	 * @return string
	 */
	public function getBrandTaxonomy() {
		return ! empty( $this->brandTaxonomy ) ? sanitize_key( $this->brandTaxonomy ) : '';
	}

	/**
	 * Get the name of the plugin vendor
	 *
	 * @return string
	 */
	public function getPluginName() {
		return ! empty( $this->pluginInfo['Name'] ) ? sanitize_text_field( $this->pluginInfo['Name'] ) : '';
	}

	/**
	 * Get the name of the plugin vendor
	 *
	 * @return string
	 */
	public function getPluginVersion() {
		return ! empty( $this->pluginInfo['Version'] ) ? sanitize_text_field( $this->pluginInfo['Version'] ) : '';
	}

	/**
	 * Register settings
	 *
	 * @return void
	 */
	private function addSettings() {
		if ( $this->hasBrands() ) {
			add_filter( 'dgwt/wcas/settings/section=search', function ( $settingsScope ) {

				$label = '';

				$pluginName = $this->getPluginName();

				if ( ! empty( $pluginName ) ) {
					$pluginInfo = $pluginName . ' v' . $this->getPluginVersion();
					$label      = ' ' . Helpers::createQuestionMark( 'search-in-brands', sprintf( __( 'Based on the plugin %s', 'ajax-search-for-woocommerce' ), $pluginInfo ) );
				}

				$settingsScope[220] = array(
					'name'    => 'search_in_product_tax_' . $this->getBrandTaxonomy(),
					'label'   => __( 'Search in brands', 'ajax-search-for-woocommerce' ) . $label,
					'class'   => 'dgwt-wcas-premium-only',
					'type'    => 'checkbox',
					'default' => 'off',
				);

				return $settingsScope;
			} );

			add_filter( 'dgwt/wcas/settings/section=autocomplete', function ( $settingsScope ) {

				$label = '';

				$pluginName = $this->getPluginName();

				if ( ! empty( $pluginName ) ) {
					$pluginInfo = $pluginName . ' v' . $this->getPluginVersion();
					$label      = ' ' . Helpers::createQuestionMark( 'show-matching-brands', sprintf( __( 'Based on the plugin %s', 'ajax-search-for-woocommerce' ), $pluginInfo ) );
				}

				$settingsScope[1260] = array(
					'name'       => 'show_product_tax_' . $this->getBrandTaxonomy(),
					'label'      => __( 'Show brands', 'ajax-search-for-woocommerce' ) . $label,
					'class'      => 'dgwt-wcas-premium-only js-dgwt-wcas-options-toggle-sibling',
					'type'       => 'checkbox',
					'default'    => 'off',
					'input_data' => 'data-option-trigger="show_matching_brands"',
				);

				if ( $this->doesPluginSupportImages() ) {
					$settingsScope[1270] = array(
						'name'       => 'show_product_tax_' . $this->getBrandTaxonomy() . '_images',
						'label'      => __( 'show images', 'ajax-search-for-woocommerce' ),
						'class'      => 'dgwt-wcas-premium-only',
						'type'       => 'checkbox',
						'default'    => 'off',
						'desc'       => __( 'show images', 'ajax-search-for-woocommerce' ),
						'move_dest'  => 'show_product_tax_' . $this->getBrandTaxonomy(),
						'input_data' => 'data-option-trigger="show_brands_images"',
					);
				}

				return $settingsScope;
			} );
		}
	}

	/**
	 * Rebuild details panel header for brands
	 *
	 * @param $title
	 * @param $termID
	 * @param $taxonomy
	 * @param $termName
	 *
	 * @return string
	 */
	public function rebuildDetailsHeader( $title, $termID, $taxonomy, $termName ) {

		if ( ! empty( $taxonomy ) && $taxonomy === $this->getBrandTaxonomy() ) {

			$title = '<span class="dgwt-wcas-datails-title">';
			$title .= '<span class="dgwt-wcas-details-title-tax">';
			$title .= Helpers::getLabel( 'tax_' . $this->getBrandTaxonomy() ) . ': ';
			$title .= '</span>';
			$title .= $termName;
			$title .= '</span>';
		}

		return $title;

	}

	/**
	 * @param string $src
	 * @param int    $termID
	 * @param string $size
	 * @param Term   $term
	 */
	public function termThumbnailSrc( $src, $termID, $size, $term ) {
		/**
		 * Notes:
		 * - "YITH WooCommerce Brands uses 'thumbnail_id' meta, so we don't need to overwrite URL in this filter
		 * - "WooCommerce Brands" uses 'thumbnail_id' meta, so we don't need to overwrite URL in this filter
		 */
		if ( $this->hasBrands() && $this->doesPluginSupportImages() ) {
			switch ( $this->pluginSlug ) {
				case 'perfect-woocommerce-brands/main.php':
				case 'perfect-woocommerce-brands/perfect-woocommerce-brands.php':
					$imageID = get_term_meta( $termID, 'pwb_brand_image', true );
					if ( ! empty( $imageID ) ) {
						$imageSrc = wp_get_attachment_image_src( $imageID, $size );

						if ( is_array( $imageSrc ) && ! empty( $imageSrc[0] ) ) {
							$src = $imageSrc[0];
						}
					}
					break;
				case 'brands-for-woocommerce/woocommerce-brand.php':
					$url = get_term_meta( $termID, 'brand_image_url', true );
					$src = empty( $url ) ? $src : $url;
					break;
			}
		}

		return $src;
	}

	/**
	 * Add brand to collection of supported taxonomies
	 *
	 * @param array $taxonomies Supported taxonomies
	 *
	 * @return array
	 */
	public function addBrandToCollectionOfTaxonomies( $taxonomies ) {
		if ( ! $this->hasBrands() ) {
			return $taxonomies;
		}

		$taxonomies = is_array( $taxonomies ) ? $taxonomies : array();

		$taxonomies[] = array(
			'taxonomy'      => $this->getBrandTaxonomy(),
			'labels'        => array(
				'name'          => __( 'Brands', 'ajax-search-for-woocommerce' ),
				'singular_name' => __( 'Brand', 'ajax-search-for-woocommerce' ),
			),
			'image_support' => $this->doesPluginSupportImages(),
		);

		return $taxonomies;
	}

	/**
	 * Check if a current brand plugin does support images
	 *
	 * @return bool
	 */
	private function doesPluginSupportImages() {
		$result = in_array(
			$this->pluginSlug,
			array(
				'yith-woocommerce-brands-add-on-premium/init.php',
				'yith-woocommerce-brands-add-on/init.php',
				'woocommerce-brands/woocommerce-brands.php',
				'perfect-woocommerce-brands/main.php',
				'perfect-woocommerce-brands/perfect-woocommerce-brands.php',
				'brands-for-woocommerce/woocommerce-brand.php',
				'premmerce-woocommerce-brands/premmerce-brands.php',
			)
		);

		return apply_filters( 'dgwt/wcas/brands/image_support', $result );
	}
}
