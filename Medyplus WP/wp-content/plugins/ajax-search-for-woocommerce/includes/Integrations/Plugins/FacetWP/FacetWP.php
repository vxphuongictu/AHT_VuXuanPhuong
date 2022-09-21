<?php

namespace DgoraWcas\Integrations\Plugins\FacetWP;

use  DgoraWcas\Helpers ;
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
/**
 * Integration with FacetWP
 *
 * Plugin URL: https://facetwp.com/
 * Author: FacetWP, LLC
 */
class FacetWP
{
    private static  $engine = 'dgwt_wcas' ;
    public  $search_terms ;
    public function init()
    {
        if ( !defined( 'FACETWP_VERSION' ) ) {
            return;
        }
        if ( version_compare( FACETWP_VERSION, '3.5.5' ) < 0 ) {
            return;
        }
        // Search page
        add_filter(
            'facetwp_query_args',
            array( $this, 'query_args' ),
            10,
            2
        );
        add_filter(
            'dgwt/wcas/search_bar/value',
            array( $this, 'restore_search_phrase' ),
            10,
            2
        );
        // Search facet
        add_filter( 'facetwp_facet_search_engines', array( $this, 'search_engines' ) );
        add_filter(
            'facetwp_facet_filter_posts',
            array( $this, 'search_facet' ),
            10,
            2
        );
    }
    
    /**
     * Prevent the default WP search from running when our plugin is enabled
     */
    function query_args( $args, $class )
    {
        
        if ( $class->is_search && isset( $class->http_params['get']['dgwt_wcas'] ) ) {
            $this->search_terms = $args['s'];
            if ( !dgoraAsfwFs()->is_premium() ) {
                $products_ids = Helpers::searchProducts( $this->search_terms );
            }
            // Set "post__in" based on our plugin results
            
            if ( empty($args['post__in']) ) {
                $post_ids = $products_ids;
            } else {
                $post_ids = [];
                $haystack = array_flip( $args['post__in'] );
                foreach ( $products_ids as $post_id ) {
                    if ( isset( $haystack[$post_id] ) ) {
                        $post_ids[] = $post_id;
                    }
                }
            }
            
            $args['post__in'] = ( empty($post_ids) ? [ 0 ] : $post_ids );
            $args['orderby'] = 'post__in';
            $args['dgwt_wcas'] = $args['s'];
            unset( $args['s'] );
        }
        
        return $args;
    }
    
    /**
     * Restore search phrase in search input
     *
     * @return string
     */
    public function restore_search_phrase( $phrase, $searchInstances )
    {
        if ( !empty($this->search_terms) ) {
            $phrase = esc_attr( $this->search_terms );
        }
        return $phrase;
    }
    
    /**
     * Add our engine to the search facet
     */
    public function search_engines( $engines )
    {
        $engines[self::$engine] = DGWT_WCAS_FULL_NAME;
        return $engines;
    }
    
    /**
     * Intercept search facets using our engine
     */
    public function search_facet( $return, $params )
    {
        $facet = $params['facet'];
        $selected_values = $params['selected_values'];
        $selected_values = ( is_array( $selected_values ) ? $selected_values[0] : $selected_values );
        $search_engine = ( isset( $facet['search_engine'] ) ? $facet['search_engine'] : '' );
        
        if ( 'search' == $facet['type'] && $search_engine === self::$engine ) {
            if ( empty($selected_values) ) {
                return 'continue';
            }
            if ( !dgoraAsfwFs()->is_premium() ) {
                $return = Helpers::searchProducts( $selected_values );
            }
        }
        
        return $return;
    }

}