<?php

namespace DgoraWcas\Integrations\Plugins\WooCommerce;

use \DgoraWcas\Helpers;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Integration with native WooCommerce filters
 */
class WooCommerce {

	public function init() {

		add_filter( 'woocommerce_layered_nav_link', array( $this, 'add_search_param_to_link' ) );

		add_filter( 'woocommerce_widget_get_current_page_url', array( $this, 'add_search_param_to_link' ) );

		if ( Helpers::isProductSearchPage() ) {
			add_filter( 'woocommerce_price_filter_sql', array( $this, 'filter_price_sql' ) );

			add_filter( 'get_terms_args', array( $this, 'narrow_term_filters' ), 10, 2 );

			add_filter( 'woocommerce_get_filtered_term_product_counts_query', array( $this, 'filter_counts_query' ) );
		}

		$this->syncWithOutOfStockVisibility();
	}

	/**
	 * Sync with WooCommerce >> Settings >> Products >> Inventory >> "Out of stock visibility"
	 */
	private function syncWithOutOfStockVisibility() {
		if ( get_option( 'woocommerce_hide_out_of_stock_items' ) === 'yes' ) {
			// Hide "Exclude “out of stock” products" option
			add_filter( 'dgwt/wcas/settings', function ( $settingsFields ) {
				if ( is_array( $settingsFields['dgwt_wcas_search'] ) ) {
					foreach ( $settingsFields['dgwt_wcas_search'] as $index => $field ) {
						if ( $field['name'] === 'exclude_out_of_stock' ) {
							unset( $settingsFields['dgwt_wcas_search'][ $index ] );
							break;
						}
					}
				}

				return $settingsFields;
			}, PHP_INT_MAX - 10 );

			// Force value of "Exclude “out of stock” products" option as "on"
			add_filter( 'dgwt/wcas/settings/load_value/key=exclude_out_of_stock', function ( $value ) {
				return 'on';
			}, PHP_INT_MAX - 10 );
		}
	}

	/**
	 * Add &dgwt_wcas=1 to the link
	 *
	 * @return string
	 */
	public function add_search_param_to_link( $link ) {

		if ( isset( $_GET['s'] ) ) {
			$link = add_query_arg( array( 'dgwt_wcas' => '1' ), $link );
		}

		return $link;
	}

	/**
	 * Narrowing the list of products to those from our search engine
	 *
	 * @param string $sql
	 *
	 * @return string
	 */
	public function filter_price_sql( $sql ) {
		if ( ! Helpers::is_running_inside_class( 'WC_Widget_Price_Filter' ) ) {
			return $sql;
		}

		$post_ids = apply_filters( 'dgwt/wcas/search_page/result_post_ids', array() );

		if ( $post_ids ) {
			$sql .= " AND product_id IN(" . implode( ',', $post_ids ) . ")";
		}

		return $sql;
	}

	/**
	 * Passing our search results to plugin's terms filters
	 *
	 * The plugin will use our products IDs to determine terms in the displayed filters.
	 *
	 * @param array $args An array of get_terms() arguments.
	 * @param string[] $taxonomies An array of taxonomy names.
	 *
	 * @return array
	 */
	public function narrow_term_filters( $args, $taxonomies ) {
		if ( ! Helpers::is_running_inside_class( 'WC_Widget_Layered_Nav' ) ) {
			return $args;
		}

		$post_ids = apply_filters( 'dgwt/wcas/search_page/result_post_ids', array() );

		if ( $post_ids ) {
			$args['object_ids'] = $post_ids;
		}

		return $args;
	}

	/**
	 * Including products from our search engine in the term count display
	 *
	 * @param $query
	 *
	 * @return mixed
	 */
	public function filter_counts_query( $query ) {
		global $wpdb;

		$post_ids = apply_filters( 'dgwt/wcas/search_page/result_post_ids', array() );

		if ( $post_ids ) {
			$query['where'] .= " AND $wpdb->posts.ID IN(" . implode( ',', $post_ids ) . ")";
		}

		return $query;
	}
}
