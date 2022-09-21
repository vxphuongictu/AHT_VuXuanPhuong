<?php

namespace DgoraWcas;

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
class ProductVariation extends Product
{
    /**
     * @var bool|false|\WC_Product_Variation|null
     */
    protected  $wcProduct = null ;
    public function __construct( $product )
    {
        
        if ( !empty($product) && is_object( $product ) && is_a( $product, 'WC_Product_Variation' ) ) {
            $this->productID = $product->get_id();
            $this->wcProduct = $product;
        }
        
        
        if ( !empty($product) && is_object( $product ) && is_a( $product, 'WP_Post' ) ) {
            $this->productID = absint( $product->ID );
            $this->wcProduct = wc_get_product( $product );
        }
        
        
        if ( is_numeric( $product ) && 'product_variation' === get_post_type( $product ) ) {
            $this->productID = absint( $product );
            $this->wcProduct = wc_get_product( $product );
        }
        
        $this->setLanguage();
    }
    
    /**
     * Get parent product ID
     *
     * @return int
     */
    public function getParentID()
    {
        return $this->wcProduct->get_parent_id();
    }
    
    /**
     * Get parent product SKU
     *
     * @return string
     */
    public function getParentSKU()
    {
        $parentData = $this->wcProduct->get_parent_data();
        return ( isset( $parentData['sku'] ) ? $parentData['sku'] : '' );
    }
    
    /**
     * WooCommerce raw product object
     *
     * @return \WC_Product_Variation
     */
    public function getWooObject()
    {
        return $this->wcProduct;
    }
    
    /**
     * Check, if class is initialized correctly
     * @return bool
     */
    public function isValid()
    {
        $isValid = false;
        if ( is_a( $this->wcProduct, 'WC_Product_Variation' ) ) {
            $isValid = true;
        }
        return $isValid;
    }
    
    /**
     * Prepare attributes for display
     * @return array
     */
    public function getVariationAttributes()
    {
        $formattedAttributes = array();
        $attributes = $this->wcProduct->get_variation_attributes();
        if ( !empty($attributes) && is_array( $attributes ) ) {
            foreach ( $attributes as $key => $termSlug ) {
                
                if ( strpos( $key, 'attribute_' ) !== false ) {
                    $taxonomy = str_replace( 'attribute_', '', $key );
                    $term = get_term_by( 'slug', $termSlug, $taxonomy );
                    
                    if ( !empty($term) && is_object( $term ) && is_a( $term, 'WP_Term' ) ) {
                        $attributeLabel = wc_attribute_label( $taxonomy );
                        $formattedAttributes[] = array(
                            'label' => $attributeLabel,
                            'value' => $term->name,
                        );
                    }
                
                }
            
            }
        }
        return apply_filters( 'dgwt/wcas/product/variation_attributes', $formattedAttributes );
    }
    
    /**
     * Set info about product language
     *
     * For variation we use language of parent product
     *
     * @return void
     */
    public function setLanguage()
    {
        if ( !$this->isValid() ) {
            return;
        }
        $this->langCode = Multilingual::getPostLang( $this->getParentID() );
    }

}