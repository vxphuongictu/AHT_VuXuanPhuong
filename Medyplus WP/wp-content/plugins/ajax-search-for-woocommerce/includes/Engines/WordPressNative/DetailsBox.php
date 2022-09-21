<?php

namespace DgoraWcas\Engines\WordPressNative;

use  DgoraWcas\Post ;
use  DgoraWcas\Product ;
use  DgoraWcas\Helpers ;
use  DgoraWcas\ProductVariation ;
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
class DetailsBox
{
    public function __construct()
    {
        if ( defined( 'DGWT_WCAS_WC_AJAX_ENDPOINT' ) ) {
            // Searched result details ajax action
            
            if ( DGWT_WCAS_WC_AJAX_ENDPOINT ) {
                add_action( 'wc_ajax_' . DGWT_WCAS_RESULT_DETAILS_ACTION, array( $this, 'getResultDetails' ) );
            } else {
                add_action( 'wp_ajax_nopriv_' . DGWT_WCAS_RESULT_DETAILS_ACTION, array( $this, 'getResultDetails' ) );
                add_action( 'wp_ajax_' . DGWT_WCAS_RESULT_DETAILS_ACTION, array( $this, 'getResultDetails' ) );
            }
        
        }
    }
    
    /**
     * Get searched result details
     */
    public function getResultDetails()
    {
        if ( !defined( 'DGWT_WCAS_AJAX_DETAILS_PANEL' ) ) {
            define( 'DGWT_WCAS_AJAX_DETAILS_PANEL', true );
        }
        $output = array();
        $items = array();
        
        if ( !empty($_POST['items']) && is_array( $_POST['items'] ) ) {
            foreach ( $_POST['items'] as $item ) {
                if ( empty($item['objectID']) ) {
                    continue;
                }
                $suggestionValue = '';
                $postType = '';
                $postID = 0;
                $variationID = 0;
                $termID = 0;
                $taxonomy = '';
                // Suggestion value
                if ( !empty($item['value']) ) {
                    $suggestionValue = sanitize_text_field( $item['value'] );
                }
                $parts = explode( '__', $item['objectID'] );
                $type = ( !empty($parts[0]) ? sanitize_key( $parts[0] ) : '' );
                
                if ( $type === 'taxonomy' ) {
                    $termID = ( !empty($parts[1]) ? absint( $parts[1] ) : 0 );
                    $taxonomy = ( !empty($parts[2]) ? sanitize_key( $parts[2] ) : '' );
                } elseif ( $type === 'product' ) {
                    $postType = $type;
                    $postID = ( !empty($parts[1]) ? absint( $parts[1] ) : 0 );
                } elseif ( $type === 'product_variation' ) {
                    $postType = $type;
                    $postID = ( !empty($parts[1]) ? absint( $parts[1] ) : 0 );
                    $variationID = ( !empty($parts[2]) ? absint( $parts[2] ) : 0 );
                } elseif ( $type === 'post' ) {
                    $postType = ( !empty($parts[2]) ? $parts[2] : 0 );
                    $postID = ( !empty($parts[1]) ? absint( $parts[1] ) : 0 );
                }
                
                // Get product details
                
                if ( !empty($postID) && !empty($postType) && in_array( $postType, array( 'product', 'product_variation' ) ) ) {
                    
                    if ( $postType === 'product_variation' ) {
                        $productDetails = $this->getProductDetails( $postID, $variationID );
                    } else {
                        $productDetails = $this->getProductDetails( $postID );
                    }
                    
                    $items[] = array(
                        'objectID' => $item['objectID'],
                        'html'     => $productDetails['html'],
                        'price'    => $productDetails['price'],
                    );
                }
                
                // Get taxonomy details
                if ( !empty($termID) && !empty($taxonomy) ) {
                    $items[] = array(
                        'objectID' => $item['objectID'],
                        'html'     => $this->getTaxonomyDetails( $termID, $taxonomy, $suggestionValue ),
                    );
                }
            }
            $output['items'] = $items;
            echo  json_encode( apply_filters( 'dgwt/wcas/suggestion_details/output', $output ) ) ;
            die;
        }
    
    }
    
    /**
     * Prepare products details to the ajax output
     *
     * @param int $productID
     * @param int $variationID
     *
     * @return array
     */
    private function getProductDetails( $productID, $variationID = 0 )
    {
        
        if ( $variationID ) {
            $product = new ProductVariation( $variationID );
        } else {
            $product = new Product( $productID );
        }
        
        $details = array(
            'html'  => '',
            'price' => '',
        );
        if ( !$product->isCorrect() ) {
            return $details;
        }
        $thumbSize = apply_filters( 'dgwt/wcas/suggestion_details/product/thumb_size', 'woocommerce_thumbnail' );
        $responsiveImages = apply_filters( 'dgwt/wcas/suggestion_details/responsive_images', true );
        $wooProduct = $product->getWooObject();
        $vars = array(
            'ID'                => $product->getID(),
            'name'              => $product->getName(),
            'desc'              => $product->getDescription( 'details-panel' ),
            'link'              => $product->getPermalink(),
            'imageSrc'          => $product->getThumbnailSrc( $thumbSize ),
            'imageSrcset'       => ( $responsiveImages ? $product->getThumbnailSrcset( $thumbSize ) : '' ),
            'imageSizes'        => ( $responsiveImages ? $product->getThumbnailSizes( $thumbSize ) : '' ),
            'sku'               => $product->getSKU(),
            'reviewCount'       => $product->getReviewCount(),
            'ratingHtml'        => $product->getRatingHtml(),
            'priceHtml'         => $product->getPriceHTML(),
            'showQuantity'      => false,
            'stockAvailability' => $product->getStockAvailability(),
            'attributes'        => array(),
            'wooObject'         => $product->getWooObject(),
        );
        if ( $variationID ) {
            $vars['attributes'] = $product->getVariationAttributes();
        }
        if ( ($product->isType( 'simple' ) || $product->isType( 'variation' )) && $wooProduct->is_purchasable() && $wooProduct->is_in_stock() && !$wooProduct->is_sold_individually() && apply_filters( 'dgwt/wcas/suggestion_details/show_quantity', true ) ) {
            $vars['showQuantity'] = true;
        }
        $vars = (object) apply_filters(
            'dgwt/wcas/suggestion_details/product/vars',
            $vars,
            $productID,
            $product
        );
        $file = ( $variationID ? 'product-variation' : 'product' );
        ob_start();
        Helpers::loadTemplate( 'details-panel/' . $file . '.php', $vars );
        $details['html'] = ob_get_clean();
        
        if ( $variationID ) {
            $details['html'] = apply_filters(
                'dgwt/wcas/suggestion_details/product_variation/html',
                $details['html'],
                $variationID,
                $product
            );
        } else {
            $details['html'] = apply_filters(
                'dgwt/wcas/suggestion_details/product/html',
                $details['html'],
                $productID,
                $product
            );
        }
        
        $details['price'] = $vars->priceHtml;
        return $details;
    }
    
    /**
     * Prepare category details to the ajax output
     *
     * @param int $termID
     * @param string taxonomy
     * @param string $termName Suggestion value
     *
     * @return string HTML
     */
    private function getTaxonomyDetails( $termID, $taxonomy, $termName )
    {
        $html = '';
        $title = '';
        ob_start();
        $queryArgs = $this->getProductsQueryArgs( $termID, $taxonomy );
        $products = new \WP_Query( $queryArgs );
        
        if ( $products->have_posts() ) {
            $limit = $queryArgs['posts_per_page'];
            $totalProducts = absint( $products->found_posts );
            $showMore = ( $limit > 0 && $totalProducts > 0 && $totalProducts - $limit > 0 ? true : false );
            // Details panel title
            $title .= '<span class="dgwt-wcas-datails-title">';
            $title .= '<span class="dgwt-wcas-details-title-tax">';
            $title .= Helpers::getLabel( 'tax_' . $taxonomy ) . ': ';
            $title .= '</span>';
            $title .= esc_html( wp_unslash( $termName ) );
            $title .= '</span>';
            $title = apply_filters(
                'dgwt/wcas/suggestion_details/taxonomy/headline',
                $title,
                $termID,
                $taxonomy,
                $termName
            );
            echo  '<div class="dgwt-wcas-details-inner dgwt-wcas-details-inner-taxonomy dgwt-wcas-details-space">' ;
            do_action( 'dgwt/wcas/details_panel/term_products/container_before' );
            echo  '<div class="dgwt-wcas-products-in-cat">' ;
            echo  ( !empty($title) ? $title : '' ) ;
            $thumbSize = apply_filters( 'dgwt/wcas/suggestion_details/term_products/thumb_size', 'dgwt-wcas-product-suggestion' );
            $responsiveImages = apply_filters( 'dgwt/wcas/suggestion_details/responsive_images', true );
            while ( $products->have_posts() ) {
                $products->the_post();
                $product = new Product( get_the_ID() );
                
                if ( $product->isValid() ) {
                    $vars = array(
                        'ID'          => $product->getID(),
                        'name'        => $product->getName(),
                        'link'        => $product->getPermalink(),
                        'imageSrc'    => $product->getThumbnailSrc( $thumbSize ),
                        'imageSrcset' => ( $responsiveImages ? $product->getThumbnailSrcset( $thumbSize ) : '' ),
                        'imageSizes'  => ( $responsiveImages ? $product->getThumbnailSizes( $thumbSize ) : '' ),
                        'reviewCount' => $product->getReviewCount(),
                        'ratingHtml'  => $product->getRatingHtml(),
                        'priceHtml'   => $product->getPriceHTML(),
                        'wooObject'   => $product->getWooObject(),
                    );
                    $vars = (object) apply_filters(
                        'dgwt/wcas/suggestion_details/term_products/vars',
                        $vars,
                        $product->getID(),
                        $product
                    );
                    Helpers::loadTemplate( 'details-panel/term-product.php', $vars );
                }
            
            }
            echo  '</div>' ;
            
            if ( $showMore ) {
                $showMoreUrl = get_term_link( $termID, $taxonomy );
                echo  '<a class="dgwt-wcas-details-more-products" href="' . esc_url( $showMoreUrl ) . '">' . Helpers::getLabel( 'show_more_details' ) . ' (' . $totalProducts . ')</a>' ;
            }
            
            do_action( 'dgwt/wcas/details_panel/term_products/container_after' );
            echo  '</div>' ;
        }
        
        wp_reset_postdata();
        $html = ob_get_clean();
        return apply_filters(
            'dgwt/wcas/suggestion_details/term/html',
            $html,
            $termID,
            $taxonomy
        );
    }
    
    /**
     * Get query vars for products that should be displayed in the daxonomy details box
     *
     * @param int $termID
     * @param string $taxonomy
     *
     * @return array
     */
    private function getProductsQueryArgs( $termID, $taxonomy )
    {
        $productVisibilityTermIds = wc_get_product_visibility_term_ids();
        $queryArgs = array(
            'posts_per_page' => apply_filters( 'dgwt/wcas/suggestion_details/taxonomy/limit', 4 ),
            'post_status'    => 'publish',
            'post_type'      => 'product',
            'no_found_rows'  => false,
            'order'          => 'desc',
            'orderby'        => 'meta_value_num',
            'meta_key'       => 'total_sales',
            'tax_query'      => array(),
        );
        // Visibility
        $queryArgs['tax_query'][] = array(
            'taxonomy' => 'product_visibility',
            'field'    => 'term_taxonomy_id',
            'terms'    => $productVisibilityTermIds['exclude-from-search'],
            'operator' => 'NOT IN',
        );
        // Out of stock
        if ( 'yes' === get_option( 'woocommerce_manage_stock' ) && DGWT_WCAS()->settings->getOption( 'exclude_out_of_stock' ) === 'on' ) {
            $queryArgs['tax_query'][] = array(
                'taxonomy' => 'product_visibility',
                'field'    => 'term_taxonomy_id',
                'terms'    => $productVisibilityTermIds['outofstock'],
                'operator' => 'NOT IN',
            );
        }
        // Search with specific category
        $queryArgs['tax_query'][] = array(
            'taxonomy'         => $taxonomy,
            'field'            => 'id',
            'terms'            => $termID,
            'include_children' => true,
        );
        return apply_filters(
            'dgwt/wcas/suggestion_details/taxonomy/products_query_args',
            $queryArgs,
            $termID,
            $taxonomy
        );
    }

}