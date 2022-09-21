<?php

/**
 * @dgwt_wcas_premium_only
 */
namespace DgoraWcas\Integrations\Plugins\WooCommercePrivateStore;

use  DgoraWcas\Helpers ;
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
/**
 * Integration with WooCommerce Private Store
 *
 * Plugin URL: https://barn2.co.uk/wordpress-plugins/woocommerce-private-store/
 * Author: Barn2 Plugins
 */
class WooCommercePrivateStore
{
    public function init()
    {
        if ( !defined( '\\Barn2\\Plugin\\WC_Private_Store\\PLUGIN_VERSION' ) ) {
            return;
        }
        if ( version_compare( \Barn2\Plugin\WC_Private_Store\PLUGIN_VERSION, '1.6.3' ) < 0 ) {
            return;
        }
        
        if ( !dgoraAsfwFs()->is_premium() ) {
            add_filter(
                'http_request_args',
                array( $this, 'httpRequestArgs' ),
                10,
                2
            );
            add_filter( 'dgwt/wcas/search_results/output', array( $this, 'hideSearchResults' ) );
        }
    
    }
    
    /**
     * Pass Private Store cookie to search request on search page
     *
     * @param $args
     * @param $url
     *
     * @return mixed
     */
    public function httpRequestArgs( $args, $url )
    {
        
        if ( defined( 'DGWT_WCAS_SEARCH_ACTION' ) && defined( 'WCPS_COOKIE_PREFIX' ) && strpos( $url, \WC_AJAX::get_endpoint( \DGWT_WCAS_SEARCH_ACTION ) ) !== false ) {
            $cookie = \filter_input( \INPUT_COOKIE, \WCPS_COOKIE_PREFIX . \COOKIEHASH );
            if ( !empty($cookie) ) {
                $args['cookies'] = array(
                    \WCPS_COOKIE_PREFIX . \COOKIEHASH => $cookie,
                );
            }
        }
        
        return $args;
    }
    
    /**
     * Return empty results if store is locked
     *
     * @param $output
     *
     * @return array
     */
    public function hideSearchResults( $output )
    {
        if ( !apply_filters( 'dgwt/wcas/integrations/woocommerce-private-store/hide-search-results', true ) ) {
            return $output;
        }
        if ( is_callable( '\\Barn2\\Plugin\\WC_Private_Store\\Util::store_locked' ) ) {
            
            if ( \Barn2\Plugin\WC_Private_Store\Util::store_locked() ) {
                $output['total'] = 0;
                $output['suggestions'] = array( array(
                    'value' => '',
                    'type'  => 'no-results',
                ) );
                $output['time'] = '0 sec';
            }
        
        }
        return $output;
    }

}