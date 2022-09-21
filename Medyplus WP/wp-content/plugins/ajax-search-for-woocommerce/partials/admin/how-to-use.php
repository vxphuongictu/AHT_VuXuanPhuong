<?php

use DgoraWcas\Admin\Promo\Upgrade;

// Exit if accessed directly
if ( ! defined( 'DGWT_WCAS_FILE' ) ) {
	exit;
}

$utmLink = 'https://fibosearch.com/pricing/?utm_source=wp-admin&utm_medium=referral&utm_campaign=upgrade-link&utm_content=how-to-use';

?>
	<h4><?php _e( 'There are four easy ways to display the search bar in your theme', 'ajax-search-for-woocommerce' ); ?>: </h4>
	<ol>
		<li><?php printf( __( 'As a menu item - go to the %s and add the menu item “FiboSearch bar”. Done!', 'ajax-search-for-woocommerce' ), '<a href="' . admin_url( 'nav-menus.php' ) . '" target="_blank">' . __( 'Menu Screen', 'ajax-search-for-woocommerce' ) . '</a>' ) ?>
		<li><?php printf( __( 'Using a shortcode - %s', 'ajax-search-for-woocommerce' ), '<code>[fibosearch]</code>' ); ?></li>
		<li><?php printf( __( 'As a widget - go to the %s and choose “FiboSearch bar”', 'ajax-search-for-woocommerce' ), '<a href="' . admin_url( 'widgets.php' ) . '" target="_blank">' . __( 'Widgets Screen', 'ajax-search-for-woocommerce' ) . '</a>' ) ?>
		<li><?php printf( __( 'Using PHP - %s', 'ajax-search-for-woocommerce' ), '<code>&lt;?php echo do_shortcode(\'[fibosearch]\'); ?&gt;</code>' ); ?></li>
	</ol>
<?php if ( ! dgoraAsfwFs()->is_premium() ): ?>
	<span class="dgwt-wcas-our-devs"><?php printf( __( 'Are there any difficulties? <b>We will do it for you!</b> We offer free of charge search bar implementation for Pro users. <a target="_blank" href="%s">Become one now!</a>', 'ajax-search-for-woocommerce' ), $utmLink ); ?></span>
<?php endif; ?>
