<?php

namespace DgoraWcas\Admin;

use  DgoraWcas\Engines\TNTSearchMySQL\Config ;
use  DgoraWcas\Engines\TNTSearchMySQL\Indexer\Builder ;
use  DgoraWcas\Engines\TNTSearchMySQL\Indexer\Updater ;
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
class Install
{
    const  SETTINGS_VERSION = 2 ;
    /**
     * Call installation callback
     *
     * @return void
     */
    public static function maybeInstall()
    {
        if ( !(defined( 'DOING_AJAX' ) && DOING_AJAX) ) {
            add_action( 'admin_init', array( __CLASS__, 'checkVersion' ), 5 );
        }
    }
    
    /**
     * Install process
     *
     * @return void
     */
    public static function install()
    {
        if ( !defined( 'DGWT_WCAS_INSTALLING' ) ) {
            define( 'DGWT_WCAS_INSTALLING', true );
        }
        self::saveActivationDate();
        self::createOptions();
        self::maybeUpgradeOptions();
        // Update plugin version
        update_option( 'dgwt_wcas_version', DGWT_WCAS_VERSION );
    }
    
    /**
     * Save default options
     *
     * @return void
     */
    public static function createOptions()
    {
        global  $dgwtWcasSettings ;
        $sections = DGWT_WCAS()->settings->settingsFields();
        $settings = array();
        if ( is_array( $sections ) && !empty($sections) ) {
            foreach ( $sections as $options ) {
                if ( is_array( $options ) && !empty($options) ) {
                    foreach ( $options as $option ) {
                        if ( isset( $option['name'] ) && !isset( $dgwtWcasSettings[$option['name']] ) ) {
                            $settings[$option['name']] = ( isset( $option['default'] ) ? $option['default'] : '' );
                        }
                    }
                }
            }
        }
        $updateOptions = array_merge( $settings, $dgwtWcasSettings );
        update_option( DGWT_WCAS_SETTINGS_KEY, $updateOptions );
    }
    
    /**
     * Maybe update settings structure or values
     *
     * @param string $version free | pro
     */
    private static function maybeUpgradeOptions( $version = 'free' )
    {
        $freeSettingsVersion = (int) get_option( 'dgwt_wcas_settings_version', 0 );
        $proSettingsVersion = (int) get_option( 'dgwt_wcas_settings_version_pro', 0 );
        $mainVersion = $freeSettingsVersion;
        $mainKey = 'dgwt_wcas_settings_version';
        $upgraded = false;
        
        if ( $version === 'pro' ) {
            $mainVersion = $proSettingsVersion;
            $mainKey = 'dgwt_wcas_settings_version_pro';
        }
        
        
        if ( $freeSettingsVersion === 0 && $proSettingsVersion === 0 ) {
            self::upgradeOptionsTo1();
            self::upgradeOptionsTo2();
            $upgraded = true;
        }
        
        if ( !$upgraded ) {
            if ( $version === 'free' && $freeSettingsVersion < 2 && $proSettingsVersion !== 2 || $version === 'pro' && $proSettingsVersion < 2 && $freeSettingsVersion !== 2 ) {
                self::upgradeOptionsTo2();
            }
        }
        
        if ( $mainVersion < self::SETTINGS_VERSION ) {
            update_option( $mainKey, self::SETTINGS_VERSION );
            DGWT_WCAS()->settings->clearCache();
        }
    
    }
    
    private static function upgradeOptionsTo1()
    {
        $settings = get_option( DGWT_WCAS_SETTINGS_KEY );
        if ( empty($settings) ) {
            return;
        }
        // Product categories
        
        if ( isset( $settings['show_matching_categories'] ) ) {
            $settings['show_product_tax_product_cat'] = $settings['show_matching_categories'];
            unset( $settings['show_matching_categories'] );
        }
        
        
        if ( isset( $settings['show_categories_images'] ) ) {
            $settings['show_product_tax_product_cat_images'] = $settings['show_categories_images'];
            unset( $settings['show_categories_images'] );
        }
        
        
        if ( isset( $settings['search_in_product_categories'] ) ) {
            $settings['search_in_product_tax_product_cat'] = $settings['search_in_product_categories'];
            unset( $settings['search_in_product_categories'] );
        }
        
        // Product tags
        
        if ( isset( $settings['show_matching_tags'] ) ) {
            $settings['show_product_tax_product_tag'] = $settings['show_matching_tags'];
            unset( $settings['show_matching_tags'] );
        }
        
        
        if ( isset( $settings['search_in_product_tags'] ) ) {
            $settings['search_in_product_tax_product_tag'] = $settings['search_in_product_tags'];
            unset( $settings['search_in_product_tags'] );
        }
        
        // Product brands
        
        if ( DGWT_WCAS()->brands->hasBrands() ) {
            
            if ( isset( $settings['show_matching_brands'] ) ) {
                $settings['show_product_tax_' . DGWT_WCAS()->brands->getBrandTaxonomy()] = $settings['show_matching_brands'];
                unset( $settings['show_matching_brands'] );
            }
            
            
            if ( isset( $settings['search_in_brands'] ) ) {
                $settings['search_in_product_tax_' . DGWT_WCAS()->brands->getBrandTaxonomy()] = $settings['search_in_brands'];
                unset( $settings['search_in_brands'] );
            }
            
            
            if ( isset( $settings['show_brands_images'] ) ) {
                $settings['show_product_tax_' . DGWT_WCAS()->brands->getBrandTaxonomy() . '_images'] = $settings['show_brands_images'];
                unset( $settings['show_brands_images'] );
            }
        
        }
        
        update_option( DGWT_WCAS_SETTINGS_KEY, $settings );
    }
    
    /**
     * Since v1.19.0 we've started to use two separated breakpoints in the search bar layout settings:
     *
     *   1. "mobile_breakpoint" - set the breakpoint for switching between icon and search bar layout
     *
     *   2. "mobile_overlay_breakpoint" - new option added since v1.19.0.
     *   Set the breakpoint after which the mobile search overlay is activated
     *
     *  Before v1.19.0 "mobile_breakpoint" was in charge of these two things.
     *  We don't know if users set "mobile_breakpoint" to handle overlay on mobile or icon/bar toggle.
     *  So we have to copy value of "mobile_breakpoint" to "mobile_overlay_breakpoint" to keep backwards compatibility
     */
    private static function upgradeOptionsTo2()
    {
        $settings = get_option( DGWT_WCAS_SETTINGS_KEY );
        if ( empty($settings) ) {
            return;
        }
        
        if ( isset( $settings['mobile_breakpoint'] ) && isset( $settings['mobile_overlay_breakpoint'] ) ) {
            $settings['mobile_overlay_breakpoint'] = $settings['mobile_breakpoint'];
            update_option( DGWT_WCAS_SETTINGS_KEY, $settings );
        }
    
    }
    
    /**
     * Save activation timestamp
     * Used to display notice, asking for a feedback
     *
     * @return void
     */
    private static function saveActivationDate()
    {
        $date = get_option( 'dgwt_wcas_activation_date' );
        if ( empty($date) ) {
            update_option( 'dgwt_wcas_activation_date', time() );
        }
    }
    
    /**
     * Check if SQL server support JSON data type
     */
    private static function checkIfDbSupportJson()
    {
        global  $wpdb ;
        $suppress_errors = $wpdb->suppress_errors;
        $wpdb->suppress_errors();
        $result = $wpdb->get_var( "SELECT JSON_CONTAINS('[1,2,3]', '2')" );
        $wpdb->suppress_errors( $suppress_errors );
        update_option( 'dgwt_wcas_db_json_support', ( $result === '1' && empty($wpdb->last_error) ? 'yes' : 'no' ) );
    }
    
    /**
     * Compare plugin version and install if a new version is available
     *
     * @return void
     */
    public static function checkVersion()
    {
        if ( !defined( 'IFRAME_REQUEST' ) ) {
            if ( !dgoraAsfwFs()->is_premium() && get_option( 'dgwt_wcas_version' ) != DGWT_WCAS_VERSION ) {
                self::install();
            }
        }
    }

}