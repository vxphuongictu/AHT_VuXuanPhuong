<?php

/**
 * @dgwt_wcas_premium_only
 */
namespace DgoraWcas\Integrations\Plugins\XforWooCommerceFilter;

use  DgoraWcas\Helpers ;
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
/**
 * Integration with Product Filter for WooCommerce
 *
 * Plugin URL: https://xforwoocommerce.com
 * Author: 7VX LLC, USA CA
 */
class XforWooCommerceFilter
{
    protected  $post_ids = array() ;
    public function init()
    {
        if ( !class_exists( 'XforWC_Product_Filters' ) ) {
            return;
        }
        if ( version_compare( \XforWC_Product_Filters::$version, '7.2.3' ) < 0 ) {
            return;
        }
        add_action( 'prdctfltr_add_inputs', array( $this, 'prdctfltr_add_inputs' ) );
        add_action( 'pre_get_posts', array( $this, 'search_products' ), 1000000 );
    }
    
    /**
     * Adding an input to be submitted during an AJAX query when changing filters
     *
     * Only on search page or during AJAX query on the search page.
     */
    public function prdctfltr_add_inputs()
    {
        
        if ( Helpers::isProductSearchPage() || defined( 'DOING_AJAX' ) && isset( $_POST['action'] ) && $_POST['action'] === 'prdctfltr_respond_550' && isset( $_POST['pf_id'] ) && isset( $_POST['pf_filters'][$_POST['pf_id']]['dgwt_wcas'] ) ) {
            echo  '<input type="hidden" name="dgwt_wcas" value="1"  class="pf_added_input" />' ;
            echo  '<input type="hidden" name="post_type" value="product"  class="pf_added_input" />' ;
        }
    
    }
    
    /**
     * Narrow the list of products in the AJAX search to those returned by our search engine
     *
     * Filtered custom WP_Query used by this plugin: wp-content/plugins/xforwoocommerce/x-pack/prdctfltr/includes/pf-shortcode.php:1333
     *
     * @param \WP_Query $query
     */
    public function search_products( $query )
    {
        if ( !$this->is_prdctfltr_ajax_search() ) {
            return;
        }
        $orderby = ( isset( $_POST['pf_filters'][$_POST['pf_id']]['orderby'] ) ? wc_clean( wp_unslash( $_POST['pf_filters'][$_POST['pf_id']]['orderby'] ) ) : 'relevance' );
        $order = 'desc';
        if ( $orderby === 'price' ) {
            $order = 'asc';
        }
        $phrase = $_POST['pf_filters'][$_POST['pf_id']]['s'];
        $post_ids = array();
        if ( !dgoraAsfwFs()->is_premium() ) {
            $post_ids = Helpers::searchProducts( $phrase );
        }
        $this->post_ids = $post_ids;
        
        if ( $post_ids ) {
            $query->set( 's', '' );
            $query->is_search = false;
            $query->set( 'post__in', $post_ids );
            $query->set( 'orderby', 'post__in' );
        }
    
    }
    
    /**
     * Checking if we are in the middle of an AJAX query that handles filter and search results refreshing
     *
     * @return bool
     */
    private function is_prdctfltr_ajax_search()
    {
        if ( !defined( 'DOING_AJAX' ) ) {
            return false;
        }
        if ( !isset( $_POST['action'] ) ) {
            return false;
        }
        if ( $_POST['action'] !== 'prdctfltr_respond_550' ) {
            return false;
        }
        if ( !isset( $_POST['pf_id'] ) ) {
            return false;
        }
        if ( !isset( $_POST['pf_filters'][$_POST['pf_id']] ) ) {
            return false;
        }
        if ( !isset( $_POST['pf_filters'][$_POST['pf_id']]['s'] ) ) {
            return false;
        }
        if ( !isset( $_POST['pf_filters'][$_POST['pf_id']]['dgwt_wcas'] ) ) {
            return false;
        }
        return true;
    }

}