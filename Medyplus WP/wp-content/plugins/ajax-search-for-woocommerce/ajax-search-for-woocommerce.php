<?php

/**
 * Plugin Name: FiboSearch - AJAX Search for WooCommerce
 * Plugin URI: https://fibosearch.com?utm_source=wp-admin&utm_medium=referral&utm_campaign=author_uri&utm_gen=utmdc
 * Description: The most popular WooCommerce product search. Gives your users a well-designed advanced AJAX search bar with live search suggestions.
 * Version: 1.19.0
 * Author: FiboSearch Team
 * Author URI: https://fibosearch.com?utm_source=wp-admin&utm_medium=referral&utm_campaign=author_uri&utm_gen=utmdc
 * Text Domain: ajax-search-for-woocommerce
 * Domain Path: /languages
 * WC requires at least: 5.5
 * WC tested up to: 6.8
 *
 */
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'DGWT_WC_Ajax_Search' ) && !function_exists( 'dgoraAsfwFs' ) ) {
    $fspath = dirname( __FILE__ ) . '/fs/config.php';
    if ( file_exists( $fspath ) ) {
        require_once $fspath;
    }
    final class DGWT_WC_Ajax_Search
    {
        private static  $instance ;
        private  $tnow ;
        public  $engine = 'native' ;
        /**
         * @var \DgoraWcas\Settings
         */
        public  $settings ;
        public  $multilingual ;
        /**
         * @var \DgoraWcas\Integrations\Themes\ThemesCompatibility
         */
        public  $themeCompatibility ;
        /**
         * @var \DgoraWcas\Integrations\Brands
         */
        public  $brands ;
        /**
         * @var \DgoraWcas\Integrations\Marketplace\Marketplace
         */
        public  $marketplace ;
        /**
         * @var \DgoraWcas\Engines\WordPressNative\Search
         */
        public  $nativeSearch ;
        /**
         * @var \DgoraWcas\Engines\TNTSearchMySQL\TNTSearch
         */
        public  $tntsearchMySql ;
        public  $tntsearchMySqlValid = false ;
        public  $searchInstances = 0 ;
        public static function getInstance()
        {
            
            if ( !isset( self::$instance ) && !self::$instance instanceof DGWT_WC_Ajax_Search ) {
                self::$instance = new DGWT_WC_Ajax_Search();
                self::$instance->constants();
                self::$instance->loadTextdomain();
                if ( !self::$instance->checkRequirements() ) {
                    return;
                }
                self::$instance->systemHooks();
                self::$instance->autoload();
                $setup = new \DgoraWcas\Setup();
                $setup->init();
                self::$instance->settings = new \DgoraWcas\Settings();
                self::$instance->hooks();
                new \DgoraWcas\Integrations\Plugins\PluginsCompatibility();
                self::$instance->multilingual = new \DgoraWcas\Multilingual();
                self::$instance->nativeSearch = new \DgoraWcas\Engines\WordPressNative\Search();
                // @TODO Temporary always use native WordPress DetailsBox engine.
                // Replace with details.php and shortinit in future releases
                new \DgoraWcas\Engines\WordPressNative\DetailsBox();
                new \DgoraWcas\Personalization();
                new \DgoraWcas\Scripts();
                $embeddingViaMenu = new \DgoraWcas\EmbeddingViaMenu();
                $embeddingViaMenu->init();
                self::$instance->themeCompatibility = new \DgoraWcas\Integrations\Themes\ThemesCompatibility();
                self::$instance->brands = new \DgoraWcas\Integrations\Brands();
                self::$instance->brands->init();
                \DgoraWcas\Shortcode::register();
                
                if ( is_admin() ) {
                    \DgoraWcas\Admin\Install::maybeInstall();
                    new \DgoraWcas\Admin\AdminMenu();
                    new \DgoraWcas\Admin\Promo\FeedbackNotice();
                    new \DgoraWcas\Admin\Promo\Upgrade();
                    new \DgoraWcas\Admin\Troubleshooting();
                    $regenerateImages = new \DgoraWcas\Admin\RegenerateImages();
                    $regenerateImages->init();
                }
                
                
                if ( is_admin() || wp_doing_cron() ) {
                    $analytics = new \DgoraWcas\Analytics\Analytics();
                    $analytics->init();
                }
                
                new \DgoraWcas\Integrations\Solver();
            }
            
            self::$instance->tnow = time();
            return self::$instance;
        }
        
        /**
         * Constructor Function
         */
        private function __construct()
        {
            self::$instance = $this;
        }
        
        /**
         * Uninstall, Activate, Deactivate hooks
         *
         * @return void
         */
        private function systemHooks()
        {
            register_deactivation_hook( __FILE__, function () {
            } );
        }
        
        /**
         * Check requirements
         *
         * @return bool
         */
        private function checkRequirements()
        {
            
            if ( version_compare( PHP_VERSION, '7.0' ) < 0 ) {
                add_action( 'admin_notices', array( $this, 'adminNoticeReqPhp70' ) );
                return false;
            }
            
            
            if ( !class_exists( 'WooCommerce' ) || !class_exists( 'WC_AJAX' ) ) {
                add_action( 'admin_notices', array( $this, 'adminNoticeNoWoocommerce' ) );
                return false;
            }
            
            
            if ( !file_exists( DGWT_WCAS_DIR . 'vendor/autoload.php' ) ) {
                add_action( 'admin_notices', array( $this, 'adminNoticeNoVendor' ) );
                return false;
            }
            
            return true;
        }
        
        /**
         * Notice: Minimum required PHP version is 7.0
         *
         * @return void
         */
        public function adminNoticeReqPhp70()
        {
            if ( defined( 'DISABLE_NAG_NOTICES' ) && DISABLE_NAG_NOTICES ) {
                return;
            }
            ?>
		    <div class="notice notice-error dgwt-wcas-notice">
			    <p>
				    <?php 
            printf( __( '%s: You need PHP version at least 7.0 to run this plugin. You are currently using PHP version ', 'ajax-search-for-woocommerce' ), '<b>' . DGWT_WCAS_NAME . '</b>' );
            echo  PHP_VERSION . '.' ;
            ?>
			    </p>
		    </div>
		    <?php 
        }
        
        /**
         * Notice: requires WooCommerce
         *
         * @return void
         */
        public function adminNoticeNoWoocommerce()
        {
            ?>
		    <div class="notice notice-error dgwt-wcas-notice">
			    <p>
				    <?php 
            printf( __( '%s is enabled but not effective. It requires %s in order to work.', 'ajax-search-for-woocommerce' ), '<b>' . DGWT_WCAS_FULL_NAME . '</b>', '<a href="https://wordpress.org/plugins/woocommerce/"  target="_blank">WooCommerce</a>' );
            ?>
			    </p>
		    </div>
		    <?php 
        }
        
        /**
         * Notice: requires /vendor
         *
         * @return void
         */
        public function adminNoticeNoVendor()
        {
            ?>
			<div class="notice notice-error dgwt-wcas-notice">
				<p>
					<?php 
            printf( __( '%s is enabled but not effective. It is missing core files. Please reinstall the plugin.', 'ajax-search-for-woocommerce' ), '<b>' . DGWT_WCAS_FULL_NAME . '</b>' );
            ?>
				</p>
			</div>
			<?php 
        }
        
        /**
         * Setup plugin constants
         *
         * @return void
         */
        private function constants()
        {
            $v = get_file_data( __FILE__, array(
                'Version' => 'Version',
            ), 'plugin' );
            $this->define( 'DGWT_WCAS_NAME', 'FiboSearch' );
            $this->define( 'DGWT_WCAS_FULL_NAME', 'FiboSearch - AJAX Search for WooCommerce' );
            $this->define( 'DGWT_WCAS_VERSION', $v['Version'] );
            $this->define( 'DGWT_WCAS_FILE', __FILE__ );
            $this->define( 'DGWT_WCAS_DIR', plugin_dir_path( __FILE__ ) );
            $this->define( 'DGWT_WCAS_URL', plugin_dir_url( __FILE__ ) );
            $this->define( 'DGWT_WCAS_SETTINGS_KEY', 'dgwt_wcas_settings' );
            $this->define( 'DGWT_WCAS_SEARCH_ACTION', 'dgwt_wcas_ajax_search' );
            $this->define( 'DGWT_WCAS_RESULT_DETAILS_ACTION', 'dgwt_wcas_result_details' );
            $this->define( 'DGWT_WCAS_GET_PRICES_ACTION', 'dgwt_wcas_get_prices' );
            $this->define( 'DGWT_WCAS_WC_AJAX_ENDPOINT', true );
        }
        
        /**
         * Define constant if not already set
         *
         * @param  string $name
         * @param  string|bool $value
         *
         * @return void
         */
        private function define( $name, $value )
        {
            if ( !defined( $name ) ) {
                define( $name, $value );
            }
        }
        
        /**
         * PSR-4 autoload
         *
         * @return void
         */
        public function autoload()
        {
            require_once DGWT_WCAS_DIR . 'vendor/autoload.php';
            require_once DGWT_WCAS_DIR . 'widget.php';
        }
        
        /**
         * Actions and filters
         *
         * @return void
         */
        private function hooks()
        {
            add_action( 'admin_init', array( $this, 'adminScripts' ), 8 );
        }
        
        /**
         * Enqueue admin sripts
         *
         * @return void
         */
        public function adminScripts()
        {
            $min = ( SCRIPT_DEBUG ? '' : '.min' );
            // Register CSS
            wp_register_style(
                'dgwt-wcas-admin-style',
                DGWT_WCAS_URL . 'assets/css/admin-style.css',
                array(),
                DGWT_WCAS_VERSION
            );
            // Register JS
            wp_register_script(
                'dgwt-wcas-admin-js',
                DGWT_WCAS_URL . 'assets/js/admin' . $min . '.js',
                array( 'jquery' ),
                DGWT_WCAS_VERSION
            );
            
            if ( \DgoraWcas\Helpers::isSettingsPage() ) {
                $localize = array(
                    'labels' => \DgoraWcas\Helpers::getLabels(),
                    'nonces' => array(
                    'build_index'             => wp_create_nonce( 'dgwt_wcas_build_index' ),
                    'stop_build_index'        => wp_create_nonce( 'dgwt_wcas_stop_build_index' ),
                    'build_index_heartbeat'   => wp_create_nonce( 'dgwt_wcas_build_index_heartbeat' ),
                    'advanced_options_switch' => wp_create_nonce( 'dgwt_wcas_advanced_options_switch' ),
                ),
                    'images' => array(
                    'admin_preloader_url' => DGWT_WCAS_URL . 'assets/img/preloader.gif',
                ),
                );
                $localize = apply_filters( 'dgwt/wcas/scripts/admin/localize', $localize );
                wp_localize_script( 'dgwt-wcas-admin-js', 'dgwt_wcas', $localize );
                // Enqueue CSS
                wp_enqueue_style( 'dgwt-wcas-admin-style' );
                
                if ( !dgoraAsfwFs()->is_activation_mode() ) {
                    wp_enqueue_style( 'wp-color-picker' );
                    wp_enqueue_script( 'dgwt-wcas-admin-js' );
                    wp_enqueue_script( 'wp-color-picker' );
                    wp_enqueue_script(
                        'dgwt-wcas-admin-popper-js',
                        DGWT_WCAS_URL . 'assets/js/popper.min.js',
                        array( 'jquery' ),
                        DGWT_WCAS_VERSION
                    );
                    wp_enqueue_script(
                        'dgwt-wcas-admin-tooltip-js',
                        DGWT_WCAS_URL . 'assets/js/tooltip.min.js',
                        array( 'jquery' ),
                        DGWT_WCAS_VERSION
                    );
                    wp_enqueue_style(
                        'dgwt-wcas-style',
                        apply_filters( 'dgwt/wcas/scripts/css_style_url', DGWT_WCAS_URL . 'assets/css/style' . $min . '.css' ),
                        array(),
                        DGWT_WCAS_VERSION
                    );
                }
            
            }
            
            if ( \DgoraWcas\Helpers::isCheckoutPage() ) {
                wp_enqueue_style( 'dgwt-wcas-admin-style' );
            }
            if ( \DgoraWcas\Helpers::isDebugPage() ) {
                wp_enqueue_style( 'dgwt-wcas-admin-style' );
            }
        }
        
        /**
         * Register text domain
         *
         * @return void
         */
        private function loadTextdomain()
        {
            $lang_dir = dirname( plugin_basename( DGWT_WCAS_FILE ) ) . '/languages/';
            load_plugin_textdomain( 'ajax-search-for-woocommerce', false, $lang_dir );
        }
    
    }
    // Init the plugin
    function DGWT_WCAS()
    {
        return DGWT_WC_Ajax_Search::getInstance();
    }
    
    add_action( 'plugins_loaded', 'DGWT_WCAS', 15 );
}
