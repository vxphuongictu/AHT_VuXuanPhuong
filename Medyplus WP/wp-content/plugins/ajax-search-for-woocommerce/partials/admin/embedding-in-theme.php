<?php

use DgoraWcas\Admin\Promo\Upgrade;

// Exit if accessed directly
if ( ! defined( 'DGWT_WCAS_FILE' ) ) {
	exit;
}

if ( DGWT_WCAS()->themeCompatibility->isCurrentThemeSupported() ):

	if ( ! DGWT_WCAS()->themeCompatibility->isWhiteLabel() ):

		$name = DGWT_WCAS()->themeCompatibility->getThemeName();
		$parentLabel = '';

		if ( DGWT_WCAS()->themeCompatibility->isChildTheme() ) {
			$parentLabel = ', ' . sprintf( __( 'child theme of <b>%s</b>', 'ajax-search-for-woocommerce' ), DGWT_WCAS()->themeCompatibility->getParentThemeName() );
		}

		?>
		<h2><?php printf( __( 'You are using the <b>%s</b> theme%s. Fantastic!', 'ajax-search-for-woocommerce' ), $name, $parentLabel ); ?></h2>
		<p><?php _e( 'We support this theme so you can easily replace all default search bars.', 'ajax-search-for-woocommerce' ); ?></p>
	<?php endif; ?>
<?php endif; ?>
