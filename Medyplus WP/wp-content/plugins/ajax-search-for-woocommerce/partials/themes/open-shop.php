<?php
// Exit if accessed directly
if ( ! defined( 'DGWT_WCAS_FILE' ) ) {
	exit;
}

add_action( 'wp_footer', function () {
	echo '<div id="wcas-theme-search" style="display: block;">' . do_shortcode( '[wcas-search-form]' ) . '</div>';
	?>
	<script>
		var wcasThemeSearch = document.querySelector('.below-header #search-box');
		if (wcasThemeSearch !== null) {
			wcasThemeSearch.replaceWith(document.querySelector('#wcas-theme-search > div'));
		}
		wcasThemeSearch = document.querySelector('.below-header .thmk-woocommerce-search-wrap');
		if (wcasThemeSearch !== null) {
			wcasThemeSearch.replaceWith(document.querySelector('#wcas-theme-search > div'));
		}
		document.querySelector('#wcas-theme-search').remove();
	</script>
	<style>
		.dgwt-wcas-search-wrapp {
			min-width: 200px;
		}

		.dgwt-wcas-sf-wrapp input[type=search].dgwt-wcas-search-input {
			height: 49px;
		}

		@media (max-width: 1200px) {
			.dgwt-wcas-search-wrapp {
				padding-left: 30px;
			}
		}

		@media (max-width: 850px) {
			.dgwt-wcas-search-wrapp {
				padding-left: 10px;
			}

			.dgwt-wcas-sf-wrapp input[type=search].dgwt-wcas-search-input {
				height: 45px;
			}
		}


		@media (max-width: 768px) {
			.dgwt-wcas-sf-wrapp input[type=search].dgwt-wcas-search-input {
				height: 34px;
			}
		}

		.dgwt-wcas-overlay-mobile .dgwt-wcas-search-wrapp {
			padding-left: 0;
		}
		.below-header-bar .dgwt-wcas-search-wrapp {
			padding-left: 18px;
		}
	</style>
	<?php
	if ( get_theme_mod( 'open_shop_sticky_header', false ) === true ) {
		echo '<div id="wcas-theme-search-sticky" style="display: block;">' . do_shortcode( '[wcas-search-form]' ) . '</div>';
		?>
		<script>
			var wcasThemeSearchInStickyHeader = document.querySelector('.search-wrapper #search-box');
			if (wcasThemeSearchInStickyHeader !== null) {
				wcasThemeSearchInStickyHeader.replaceWith(document.querySelector('#wcas-theme-search-sticky > div'));
			}
			wcasThemeSearchInStickyHeader = document.querySelector('.search-wrapper .thmk-woocommerce-search-wrap');
			if (wcasThemeSearchInStickyHeader !== null) {
				wcasThemeSearchInStickyHeader.replaceWith(document.querySelector('#wcas-theme-search-sticky > div'));
			}
			document.querySelector('#wcas-theme-search-sticky').remove();

			(function ($) {
				$(window).on('load', function () {
					$('.prd-search').on('click', function () {
						// Autofocus
						if ($(window).width() >= 1024) {
							setTimeout(function () {
								var $input = $('.search-wrapper .dgwt-wcas-search-input');
								if ($input.length > 0) {
									$input.focus();
								}
							}, 500);
						} else {
							var $mobile = $('.search-wrapper .js-dgwt-wcas-enable-mobile-form');
							if ($mobile.length > 0) {
								$mobile.click();
							}
						}
					});
				});
			}(jQuery));
		</script>
		<style>
			.search-close {
				float: none;
				margin-bottom: 0;
				position: absolute;
				top: calc(50% - 10px);
				right: -10px;
			}

			.search-wrapper .dgwt-wcas-search-wrapp {
				max-width: none;
			}
		</style>
		<?php
	}
} );
