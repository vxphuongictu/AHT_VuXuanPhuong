<?php

use DgoraWcas\Helpers;

// Exit if accessed directly
if ( ! defined( 'DGWT_WCAS_FILE' ) ) {
	exit;
}

$submitText = Helpers::getLabel( 'submit' );
$hasSubmit  = DGWT_WCAS()->settings->getOption( 'show_submit_button' );

$labelNoResults = Helpers::getLabel( 'no_results' );
$labelSeeAll    = Helpers::getLabel( 'show_more' );

?>
<div class="dgwt-wcas-preview js-dgwt-wcas-preview">

	<div class="js-dgwt-wcas-preview-inner dgwt-wcas-preview-inner">
		<div class="js-dgwt-wcas-preview-head dgwt-wcas-preview-head">
			<span class="dgwt-wcas-preview-header"><?php _e( 'Preview', 'ajax-search-for-woocommerce' ); ?></span>
			<span class="dgwt-wcas-preview-subheader dgwt-wcas-preview-subheader__sb"><?php _e( 'Search bar', 'ajax-search-for-woocommerce' ); ?></span>
			<span class="dgwt-wcas-preview-subheader dgwt-wcas-preview-subheader__ac"><?php _e( 'Autocomplete', 'ajax-search-for-woocommerce' ); ?></span>
		</div>

		<div class="js-dgwt-wcas-preview-source dgwt-wcas-preview-source">

			<span class="js-dgwt-wcas-preview-device-info dgwt-wcas-preview-device-info dgwt-wcas-hidden" data-device="desktop">
				<span><?php _e( 'On mobile', 'ajax-search-for-woocommerce' ); ?></span>
				<span><?php _e( 'On desktop', 'ajax-search-for-woocommerce' ); ?></span>
			</span>
			<div class="js-dgwt-wcas-search-wrapp js-dgwt-wcas-preview-bar-example dgwt-wcas-search-wrapp <?php echo Helpers::searchWrappClasses(); ?>" data-wcas-context="75c2">
				<form class="dgwt-wcas-search-form" role="search" action="" method="get">
					<div class="dgwt-wcas-sf-wrapp">
						<?php echo Helpers::getMagnifierIco('dgwt-wcas-ico-magnifier js-dgwt-wcas-ico-magnifier-solaris' ); ?>
						<?php echo Helpers::getMagnifierIco('dgwt-wcas-ico-magnifier js-dgwt-wcas-ico-magnifier-pirx', 'magnifier-pirx'); ?>

						<label class="screen-reader-text"><?php _e( 'Products search', 'ajax-search-for-woocommerce' ); ?></label>

						<input
							type="search"
							class="js-dgwt-wcas-search-input dgwt-wcas-search-input"
							name="<?php echo Helpers::getSearchInputName(); ?>"
							value="<?php echo get_search_query() ?>"
							autocomplete="off"
							placeholder="<?php echo Helpers::getLabel( 'search_placeholder' ); ?>"
						/>
						<div class="dgwt-wcas-preloader"></div>

						<button type="submit" class="js-dgwt-wcas-search-submit dgwt-wcas-search-submit"><?php
							echo '<span class="js-dgwt-wcas-search-submit-l">' . esc_html( $submitText ) . '</span>';
							echo '<span class="js-dgwt-wcas-search-submit-m">';
							echo Helpers::getMagnifierIco('dgwt-wcas-ico-magnifier js-dgwt-wcas-ico-magnifier-solaris');
							echo Helpers::getMagnifierIco('dgwt-wcas-ico-magnifier js-dgwt-wcas-ico-magnifier-pirx', 'magnifier-pirx');
							echo '</span>';
							?>
						</button>

						<input type="hidden" name="post_type" value="product">
						<input type="hidden" name="dgwt_wcas" value="1">

						<input type="hidden" name="lang" value="en">

					</div>
				</form>
			</div>

			<span class="js-dgwt-wcas-preview-device-info dgwt-wcas-preview-device-info dgwt-wcas-hidden" data-device="mobile">
				<span><?php _e( 'On mobile', 'ajax-search-for-woocommerce' ); ?></span>
				<span><?php _e( 'On desktop', 'ajax-search-for-woocommerce' ); ?></span>
			</span>
			<div class="js-dgwt-wcas-search-wrapp dgwt-wcas-layout-icon-open js-dgwt-wcas-preview-icon-example dgwt-wcas-search-wrapp dgwt-wcas-hidden <?php echo Helpers::searchWrappClasses(); ?>" data-wcas-context="75c3">
				<div class="dgwt-wcas-search-icon js-dgwt-wcas-search-icon-handler"><?php echo Helpers::getMagnifierIco( 'dgwt-wcas-ico-magnifier-handler' ); ?></div>
				<div class="dgwt-wcas-search-icon-arrow"></div>
				<form class="dgwt-wcas-search-form" role="search" action="" method="get">
					<div class="dgwt-wcas-sf-wrapp">
						<?php echo Helpers::getMagnifierIco('dgwt-wcas-ico-magnifier js-dgwt-wcas-ico-magnifier-solaris' ); ?>
						<?php echo Helpers::getMagnifierIco('dgwt-wcas-ico-magnifier js-dgwt-wcas-ico-magnifier-pirx', 'magnifier-pirx'); ?>

						<label class="screen-reader-text"><?php _e( 'Products search', 'ajax-search-for-woocommerce' ); ?></label>
						<input
							type="search"
							class="js-dgwt-wcas-search-input dgwt-wcas-search-input"
							name="<?php echo Helpers::getSearchInputName(); ?>"
							value="<?php echo get_search_query() ?>"
							autocomplete="off"
							placeholder="<?php echo Helpers::getLabel( 'search_placeholder' ); ?>"
						/>
						<div class="dgwt-wcas-preloader"></div>

						<button type="submit" class="js-dgwt-wcas-search-submit dgwt-wcas-search-submit"><?php
							echo '<span class="js-dgwt-wcas-search-submit-l">' . esc_html( $submitText ) . '</span>';
							echo '<span class="js-dgwt-wcas-search-submit-m">';
							echo Helpers::getMagnifierIco('dgwt-wcas-ico-magnifier js-dgwt-wcas-ico-magnifier-solaris');
							echo Helpers::getMagnifierIco('dgwt-wcas-ico-magnifier js-dgwt-wcas-ico-magnifier-pirx', 'magnifier-pirx');
							echo '</span>';
							?>
						</button>

						<input type="hidden" name="post_type" value="product">
						<input type="hidden" name="dgwt_wcas" value="1">

						<input type="hidden" name="lang" value="en">

					</div>
				</form>
			</div>

			<div class="dgwt-wcas-autocomplete">

				<div class="dgwt-wcas-suggestions-wrapp js-dgwt-wcas-suggestions-wrapp woocommerce dgwt-wcas-has-price dgwt-wcas-has-desc dgwt-wcas-has-sku" unselectable="on">

					<div class="dgwt-wcas-suggestion dgwt-wcas-suggestion-headline-brand dgwt-wcas-suggestion-headline">
                    <span class="dgwt-wcas-st">
                      <?php echo Helpers::getLabel( 'tax_' . DGWT_WCAS()->brands->getBrandTaxonomy() . '_plu' ); ?>
                    </span>
					</div>

					<div class="dgwt-wcas-suggestion dgwt-wcas-suggestion-brand js-dgwt-wcas-suggestion-brand">
					<span class="dgwt-wcas-si">
                        <img src="<?php echo DGWT_WCAS_URL; ?>assets/img/product-preview.png">
                    </span>
						<span class="dgwt-wcas-st">
                        <span class="dgwt-wcas-st--direct-headline"><?php echo Helpers::getLabel( 'tax_' . DGWT_WCAS()->brands->getBrandTaxonomy() ); ?></span>
                        <?php _e( 'Sample brand <strong>name</strong>', 'ajax-search-for-woocommerce' ); ?>
                    </span>
					</div>

					<div class="dgwt-wcas-suggestion dgwt-wcas-suggestion-headline-cat dgwt-wcas-suggestion-headline">
                    <span class="dgwt-wcas-st">
                      <?php echo Helpers::getLabel( 'tax_product_cat_plu' ); ?>
                    </span>
					</div>

					<div class="dgwt-wcas-suggestion dgwt-wcas-suggestion-cat js-dgwt-wcas-suggestion-cat">
					<span class="dgwt-wcas-si">
                        <img src="<?php echo DGWT_WCAS_URL; ?>assets/img/product-preview.png">
                    </span>
						<span class="dgwt-wcas-st">
                        <span class="dgwt-wcas-st--direct-headline"><?php echo Helpers::getLabel( 'tax_product_cat' ); ?></span>
                        <?php _e( 'Sample category <strong>name</strong>', 'ajax-search-for-woocommerce' ); ?>
                    </span>
					</div>

					<div class="dgwt-wcas-suggestion dgwt-wcas-suggestion-headline-tag dgwt-wcas-suggestion-headline">
                    <span class="dgwt-wcas-st">
                      <?php echo Helpers::getLabel( 'tax_product_tag_plu' ); ?>
                    </span>
					</div>

					<div class="dgwt-wcas-suggestion dgwt-wcas-suggestion-tag">
                    <span class="dgwt-wcas-st">
                        <span class="dgwt-wcas-st--direct-headline"><?php echo Helpers::getLabel( 'tax_product_tag' ); ?></span>
                        <?php _e( 'Sample tag <strong>name</strong>', 'ajax-search-for-woocommerce' ); ?>
                    </span>
					</div>

					<div class="dgwt-wcas-suggestion dgwt-wcas-suggestion-headline-post dgwt-wcas-suggestion-headline">
                    <span class="dgwt-wcas-st">
                      <?php echo Helpers::getLabel( 'post_plu' ); ?>
                    </span>
					</div>

					<div class="dgwt-wcas-suggestion dgwt-wcas-suggestion-post">
                    <span class="dgwt-wcas-st">
                        <span class="dgwt-wcas-st--direct-headline"><?php echo Helpers::getLabel( 'post' ); ?></span>
                        <?php _e( 'Sample post <strong>name</strong>', 'ajax-search-for-woocommerce' ); ?>
                    </span>
					</div>

					<div class="dgwt-wcas-suggestion dgwt-wcas-suggestion-headline-page dgwt-wcas-suggestion-headline">
                    <span class="dgwt-wcas-st">
                      <?php echo Helpers::getLabel( 'page_plu' ); ?>
                    </span>
					</div>

					<div class="dgwt-wcas-suggestion dgwt-wcas-suggestion-page">
                    <span class="dgwt-wcas-st">
                        <span class="dgwt-wcas-st--direct-headline"><?php echo Helpers::getLabel( 'page' ); ?></span>
                        <?php _e( 'Sample page <strong>name</strong>', 'ajax-search-for-woocommerce' ); ?>
                    </span>
					</div>

					<div class="dgwt-wcas-suggestion js-dgwt-wcas-suggestion-headline dgwt-wcas-suggestion-headline">
                    <span class="dgwt-wcas-st">
                      <?php echo Helpers::getLabel( 'product_plu' ); ?>
                    </span>
					</div>

					<div class="dgwt-wcas-suggestion dgwt-wcas-suggestion-product">
                    <span class="js-dgwt-wcas-si dgwt-wcas-si">
                        <img src="<?php echo DGWT_WCAS_URL; ?>assets/img/product-preview.png">
                    </span>
						<div class="js-dgwt-wcas-content-wrapp dgwt-wcas-content-wrapp">
                        <span class="dgwt-wcas-st">
                            <span class="dgwt-wcas-st-title"><?php _e( 'Sample product <strong>name</strong>', 'ajax-search-for-woocommerce' ); ?></span>
                            <span class="dgwt-wcas-sku js-dgwt-wcas-sku">(<?php echo Helpers::getLabel( 'sku_label' ); ?> 0001)</span>
                            <span class="dgwt-wcas-sd js-dgwt-wcas-sd"><?php _e( 'Lorem <strong>ipsum</strong> dolor sit amet, consectetur adipiscing elit. Quisque gravida lacus nec diam porttitor pharetra. Nulla facilisi. Proin pharetra imperdiet neque, non varius.', 'ajax-search-for-woocommerce' ); ?></span>
                        </span>
							<span class="dgwt-wcas-sp js-dgwt-wcas-sp">
                            <?php echo wc_price( 99 ); ?>
                        </span>
						</div>
					</div>


					<div class="dgwt-wcas-suggestion js-dgwt-wcas-suggestion-more dgwt-wcas-suggestion-more dgwt-wcas-suggestion-no-border-bottom" data-index="7">
						<span class="dgwt-wcas-st-more"><span class="js-dgwt-wcas-st-more-label"><?php echo $labelSeeAll; ?></span> (73)</span>
					</div>

					<div class="dgwt-wcas-suggestion dgwt-wcas-suggestion-nores js-dgwt-wcas-suggestion-nores dgwt-wcas-hide">
						<span class="dgwt-wcas-st"><?php echo $labelNoResults; ?></span>
					</div>

				</div>

				<div class="dgwt-wcas-details-wrapp js-dgwt-wcas-details-wrapp woocommerce">

					<div class="dgwt-wcas-details-inner">

						<div class="dgwt-wcas-product-details">

							<a href="#">
								<div class="dgwt-wcas-details-main-image">
									<img src="<?php echo DGWT_WCAS_URL; ?>assets/img/product-preview.png"/>
								</div>
							</a>

							<div class="dgwt-wcas-details-space">

								<a class="dgwt-wcas-details-product-title" href="#">
									<?php _e( 'Sample product name', 'ajax-search-for-woocommerce' ); ?>
								</a>
								<span class="dgwt-wcas-details-product-sku">0001</span>

								<div class="dgwt-wcas-pd-price">
									<?php echo wc_price( 99 ); ?>
								</div>

								<div class="dgwt-wcas-details-hr"></div>

								<div class="dgwt-wcas-pd-desc">
									<?php _e( 'Lorem <strong>ipsum</strong> dolor sit amet, consectetur adipiscing elit. Quisque gravida lacus nec diam porttitor pharetra. Nulla facilisi. Proin pharetra imperdiet neque, non varius.',
										'ajax-search-for-woocommerce' ); ?>
								</div>

								<div class="dgwt-wcas-details-hr"></div>

								<div class="dgwt-wcas-pd-addtc js-dgwt-wcas-pd-addtc">
									<form class="dgwt-wcas-pd-addtc-form" action="" method="post" enctype="multipart/form-data">
										<div class="quantity buttons_added">
											<input type="button" value="-" class="minus button is-form">
											<input type="number" class="input-text qty text" step="1" min="0" max="9999" name="js-dgwt-wcas-quantity" value="1" title="Qty" size="4" inputmode="numeric">
											<input type="button" value="+" class="plus button is-form">
										</div>
										<p class="product woocommerce add_to_cart_inline " style="">
											<a href="#" data-quantity="1" class="button product_type_simple add_to_cart_button ajax_add_to_cart" rel="nofollow"><?php _e( 'Add to cart', 'woocommerce' ); ?></a>
										</p>
									</form>
								</div>

							</div>


						</div>

					</div>

				</div>

			</div>
		</div>

	</div>

	<div class="dgwt-wcas-preview-elements dgwt-wcas-hidden">
		<div class="js-dgwt-wcas-preview-elements-close"><?php echo Helpers::getIcon( 'close' );  ?></div>
	</div>
</div>
