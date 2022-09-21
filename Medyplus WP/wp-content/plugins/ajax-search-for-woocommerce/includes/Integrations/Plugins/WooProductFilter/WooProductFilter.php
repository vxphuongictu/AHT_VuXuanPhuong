<?php

namespace DgoraWcas\Integrations\Plugins\WooProductFilter;

use  DgoraWcas\Helpers ;
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
/**
 * Integration with WooCommerce Product Filter by WooBeWoo
 *
 * Plugin URL: https://wordpress.org/plugins/woo-product-filter/
 * Author: WooBeWoo
 */
class WooProductFilter
{
    public function init()
    {
        if ( !defined( 'WPF_VERSION' ) ) {
            return;
        }
        if ( version_compare( WPF_VERSION, '1.2.8' ) < 0 ) {
            return;
        }
        // TODO This filter must be added by the plugin author
        add_filter( 'wpf_getFilteredPriceSql', array( $this, 'filter_price_sql' ) );
        add_action( 'pre_get_posts', array( $this, 'pre_get_posts' ) );
    }
    
    /**
     * Narrowing the list of products for determining edge prices to those returned by our search engine
     *
     * @param string $sql
     *
     * @return string
     */
    public function filter_price_sql( $sql )
    {
        global  $wpdb ;
        $post_ids = apply_filters( 'dgwt/wcas/search_page/result_post_ids', array() );
        if ( $post_ids ) {
            $sql .= " AND {$wpdb->posts}.ID IN(" . implode( ',', $post_ids ) . ")";
        }
        return $sql;
    }
    
    /**
     * Narrow the list of products in the AJAX search to those returned by our search engine
     *
     * Filtered custom WP_Query used by this plugin: wp-content/plugins/woo-product-filter/modules/woofilters/controller.php~152
     *
     * @param \WP_Query $query
     */
    public function pre_get_posts( $query )
    {
        if ( !defined( 'DOING_AJAX' ) ) {
            return;
        }
        if ( !isset( $_POST['action'] ) || isset( $_POST['action'] ) && $_POST['action'] !== 'filtersFrontend' ) {
            return;
        }
        if ( !isset( $_POST['mod'] ) || isset( $_POST['mod'] ) && $_POST['mod'] !== 'woofilters' ) {
            return;
        }
        if ( !isset( $_POST['currenturl'] ) ) {
            return;
        }
        $orderby = 'relevance';
        $order = 'desc';
        // parse args from url passed as POST var
        $url_query = wp_parse_url( $_POST['currenturl'] );
        $url_query_args = array();
        wp_parse_str( $url_query['query'], $url_query_args );
        if ( !isset( $url_query_args['dgwt_wcas'] ) || !isset( $url_query_args['s'] ) ) {
            return;
        }
        if ( !empty($url_query_args['orderby']) ) {
            $orderby = wc_clean( wp_unslash( $url_query_args['orderby'] ) );
        }
        if ( !empty($url_query_args['order']) ) {
            $order = strtolower( wc_clean( wp_unslash( $url_query_args['order'] ) ) );
        }
        if ( $orderby === 'price' ) {
            $order = 'asc';
        }
        $post_ids = array();
        if ( !dgoraAsfwFs()->is_premium() ) {
            $post_ids = Helpers::searchProducts( $url_query_args['s'] );
        }
        
        if ( $post_ids ) {
            if ( version_compare( WPF_VERSION, '1.4.8', '>=' ) ) {
                $query->set( 's', '' );
            }
            $query->set( 'post__in', $post_ids );
            $query->set( 'orderby', 'post__in' );
        }
    
    }

}