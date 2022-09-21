<?php

namespace DgoraWcas\Admin;

use  DgoraWcas\Engines\TNTSearchMySQL\Config ;
use  DgoraWcas\Engines\TNTSearchMySQL\Indexer\Builder ;
use  DgoraWcas\Settings ;
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
class AdminMenu
{
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'addMenu' ), 20 );
    }
    
    /**
     * Add meun items
     *
     * @return void
     */
    public function addMenu()
    {
        $menuSuffix = '';
        if ( dgoraAsfwFs()->is_activation_mode() ) {
            add_action( 'admin_print_styles', function () {
                ?>
				<style>
					#adminmenu > .toplevel_page_dgwt_wcas_settings {
						display: none;
					}
				</style>
				<?php 
            } );
        }
        add_submenu_page(
            'woocommerce',
            __( 'FiboSearch', 'ajax-search-for-woocommerce' ),
            __( 'FiboSearch', 'ajax-search-for-woocommerce' ) . $menuSuffix,
            'manage_options',
            'dgwt_wcas_settings',
            array( $this, 'settingsPage' )
        );
        if ( !dgoraAsfwFs()->is_activation_mode() ) {
            add_submenu_page(
                'dgwt_wcas_settings',
                'FiboSearch Debug',
                'FiboSearch [Hidden]',
                'manage_options',
                'dgwt_wcas_debug',
                array( $this, 'debugPage' )
            );
        }
    }
    
    /**
     * Settings page
     *
     * @return void
     */
    public function settingsPage()
    {
        Settings::output();
    }
    
    /**
     * Debug page
     *
     * @return void
     */
    public function debugPage()
    {
        include_once DGWT_WCAS_DIR . 'partials/admin/debug/debug.php';
    }

}