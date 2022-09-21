<?php

use DgoraWcas\Admin\Promo\Upgrade;

// Exit if accessed directly
if ( ! defined( 'DGWT_WCAS_FILE' ) ) {
	exit;
}

$utmLink = 'https://fibosearch.com/pro-vs-free/?utm_source=wp-admin&utm_medium=referral&utm_campaign=settings&utm_content=features&utm_gen=utmdc';
?>

<div class="dgwt-wcas-upgrade-s">
	<h2 class="dgwt-wcas-upgrade-s__title"><?php _e( 'If users can’t find the product they’re searching for, they can’t buy it. Help your customers to find the right products even <span>10× faster</span>.', 'ajax-search-for-woocommerce' ); ?></h2>
	<p class="dgwt-wcas-upgrade-s__subtitle">
		<?php _e( 'Update now and boost your sales. You will receive a <b>30-day satisfaction guarantee</b>.  A return on investment will come very quickly.', 'ajax-search-for-woocommerce' ); ?>
	</p>
	<ul>
		<li><strong>+ <?php _e( '<b>New Ultra-Fast Search Engine</b> – works very fast even with 100,000+ products ', 'ajax-search-for-woocommerce' ); ?></strong></li>
		<li><strong>+ <?php _e( 'Professional and fast <b>help to embed</b> or <b>replacing</b> the search bar in your theme', 'ajax-search-for-woocommerce' ); ?></strong></li>
		<li><strong>+ <?php _e( 'Fuzzy search', 'ajax-search-for-woocommerce' ); ?></strong></li>
		<li><strong>+ <?php _e( 'Search in custom fields', 'ajax-search-for-woocommerce' ); ?></strong></li>
		<li><strong>+ <?php _e( 'Search in attributes', 'ajax-search-for-woocommerce' ); ?></strong></li>
		<li><strong>+ <?php _e( 'Search in categories', 'ajax-search-for-woocommerce' ); ?></strong></li>
		<li><strong>+ <?php _e( 'Search in tags', 'ajax-search-for-woocommerce' ); ?></strong></li>
		<li><strong>+ <?php _e( 'Search by variation product SKU', 'ajax-search-for-woocommerce' ); ?></strong></li>
		<li><strong>+ <?php _e( 'Search in brands (WooCommerce Brands or YITH WooCommerce Brands)', 'ajax-search-for-woocommerce' ); ?></strong></li>
		<li><strong>+ <?php _e( 'Search for posts and pages', 'ajax-search-for-woocommerce' ); ?></strong></li>
		<li><strong>+ <?php _e( 'Synonyms', 'ajax-search-for-woocommerce' ); ?></strong></li>
		<li><strong>+ <?php _e( 'Individual customization of the search bar (simple CSS improvements)', 'ajax-search-for-woocommerce' ); ?></strong></li>
		<li><strong>+ <?php _e( 'And more...', 'ajax-search-for-woocommerce' ); ?> <a target="_blank" href="<?php echo $utmLink; ?>"><?php _e( 'See a comparison of all free and premium features!', 'ajax-search-for-woocommerce' ); ?></a></strong></li>
	</ul>
	<a target="_blank" href="<?php echo Upgrade::getUpgradeUrl(); ?>" class="button ajax-build-index-primary"><?php _e( 'Upgrade Now!', 'ajax-search-for-woocommerce' ); ?></a>
</div>
