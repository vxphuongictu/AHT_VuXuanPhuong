<?php
// Exit if accessed directly
if ( ! defined( 'DGWT_WCAS_FILE' ) ) {
	exit;
}

add_filter( 'electro_use_third_party_navbar_search', '__return_true' );

add_action( 'electro_navbar_search_third_party', function () {
	?>
	<div class="navbar-search">
		<?php echo do_shortcode( '[wcas-search-form layout="classic"]' ) ?>
	</div>
	<?php
} );

if ( ! function_exists( 'electro_product_search' ) ) {
	function electro_product_search() {
		?>
		<div class="site-search">
			<?php echo do_shortcode( '[wcas-search-form layout="classic"]' ) ?>
		</div>
		<?php
	}
}

add_action( 'wp_footer', function () {
	$breakpoint    = DGWT_WCAS()->settings->getOption( 'mobile_breakpoint', 992 );
	$mobileOverlay = DGWT_WCAS()->settings->getOption( 'enable_mobile_overlay' ) === 'on';
	?>
	<script>
		(function ($) {

			function fiboEletroThemeFocusInput() {
				$('.handheld-header-links .search > a').on('click', function (e) {
					setTimeout(function () {
						var $input = $('.handheld-header-links .site-search .dgwt-wcas-search-input');
						if ($input.length > 0 && $input.val().length === 0) {
							$input.focus();
						}
					}, 500);
				});
			}

			$(window).on('load', function () {
				<?php if(! $mobileOverlay): ?>

				fiboEletroThemeFocusInput();

				$('.handheld-header-links .search.active > a').on('click', function (e) {
					var $input = $('.handheld-header-links .site-search .dgwt-wcas-close');
					if ($input.length > 0) {
						$input[0].click();
					}
				});
				<?php else: ?>
				// Search icon - mobile
				if ($(window).width() <= <?php echo $breakpoint; ?>) {
					$('.handheld-header-links .search > a').off('click').on('click', function (e) {
						var $handler = $('.handheld-header-links .site-search .js-dgwt-wcas-enable-mobile-form');
						if ($handler.length) {
							$handler[0].click();
						}
						e.preventDefault();
					});
				} else {
					// Search icon - almost desktop
					fiboEletroThemeFocusInput();
				}
				<?php endif; ?>
			});
		}(jQuery));
	</script>
	<style>
		/** Desktop */
		.navbar-search .dgwt-wcas-search-wrapp {
			max-width: 800px;
		}

		.navbar-search {
			flex-basis: 0;
			flex-grow: 1;
			margin-bottom: 0;
		}

		/** Mobile: Default Handled Header */
		.handheld-header-links .site-search .dgwt-wcas-search-wrapp {
			max-width: 100%;
		}

		/** Mobile: Mobile Header v1 */
		.mobile-header-v1 .site-search .dgwt-wcas-search-wrapp {
			max-width: 100%;
		}

		/** Mobile: Mobile Header v2 */
		.mobile-header-v2 .mobile-header-v2-inner > .site-search .dgwt-wcas-search-wrapp {
			max-width: 100%;
		}
	</style>
	<?php
} );
