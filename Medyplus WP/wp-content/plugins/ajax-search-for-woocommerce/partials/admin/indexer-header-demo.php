<?php

// Exit if accessed directly
if ( ! defined( 'DGWT_WCAS_FILE' ) ) {
	exit;
}

?>
<div class="js-dgwt-wcas-indexing-wrapper">
	<div class="dgwt-wcas-indexing-header">
		<span class="dgwt-wcas-indexing-header__title" style="color:#aaaaaa"><?php _e( 'The search index does not exist yet. Build it now.', 'ajax-search-for-woocommerce' ); ?></span>


		<div class="dgwt-wcas-indexing-header__actions">
			<a class="button dgwt-wcas-premium-only--trigger" href="#"><?php _e( 'Build index', 'ajax-search-for-woocommerce' ); ?></a>
			<a href="#" class="show dgwt-wcas-premium-only--trigger dgwt-wcas-indexing-details-trigger"><?php _e( 'Show details', 'ajax-search-for-woocommerce' ); ?></a>
		</div>

	</div>
</div>
