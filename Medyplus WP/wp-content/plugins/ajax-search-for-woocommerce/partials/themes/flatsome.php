<?php
// Exit if accessed directly
if ( ! defined( 'DGWT_WCAS_FILE' ) ) {
	exit;
}

add_filter( 'body_class', function ( $classes ) {
	$classes[] = 'dgwt-wcas-theme-flatsome';

	return $classes;
} );

add_action( 'wp_loaded', function () {
	remove_shortcode( 'search' );
	add_shortcode( 'search', array( 'DgoraWcas\\Shortcode', 'addBody' ) );
} );

// Change mobile breakpoint from 992 to 850
add_filter( 'dgwt/wcas/scripts/mobile_breakpoint', function () {
	return 850;
} );

add_action( 'wp_head', function () { ?>
	<style>
		.dgwt-wcas-flatsome-up {
			margin-top: -40vh;
		}

		#search-lightbox .dgwt-wcas-sf-wrapp input[type=search].dgwt-wcas-search-input {
			height: 60px;
			font-size: 20px;
		}

		#search-lightbox .dgwt-wcas-search-wrapp {
			-webkit-transition: all 100ms ease-in-out;
			-moz-transition: all 100ms ease-in-out;
			-ms-transition: all 100ms ease-in-out;
			-o-transition: all 100ms ease-in-out;
			transition: all 100ms ease-in-out;
		}

		.dgwt-wcas-overlay-mobile-on .mfp-wrap .mfp-content {
			width: 100vw;
		}

		.dgwt-wcas-overlay-mobile-on .mfp-close,
		.dgwt-wcas-overlay-mobile-on .nav-sidebar {
			display: none;
		}

		.dgwt-wcas-overlay-mobile-on .main-menu-overlay {
			display: none;
		}

		.dgwt-wcas-open .header-search-dropdown .nav-dropdown {
			opacity: 1;
			max-height: inherit;
			left: -15px !important;
		}

		.dgwt-wcas-open:not(.dgwt-wcas-theme-flatsome-dd-sc) .nav-right .header-search-dropdown .nav-dropdown {
			left: auto;
			/*right: -15px;*/
		}

		.dgwt-wcas-theme-flatsome .nav-dropdown .dgwt-wcas-search-wrapp {
			min-width: 450px;
		}

		.header-search-form {
			min-width: 250px;
		}
	</style>
	<?php
} );

add_action( 'wp_footer', function () {

	$minChars = DGWT_WCAS()->settings->getOption( 'min_chars' );
	if ( empty( $minChars ) || ! is_numeric( $minChars ) ) {
		$minChars = 3;
	}

	// @TODO Dropdown on search hover
	?>
	<script>
		(function ($) {
			$(document).ready(function () {
				$(document).on('keyup', '#search-lightbox .dgwt-wcas-search-wrapp .dgwt-wcas-search-input', function () {
					if (this.value.length >= <?php echo $minChars; ?>) {
						$(this).closest('.dgwt-wcas-search-wrapp').addClass('dgwt-wcas-flatsome-up')
					}
				});

				var refreshDropdownPosition;
				var style = '';
				var positioning = false;
				$(document).on('mouseenter', '.header-search-dropdown a', function (e) {
					if (positioning) {
						return;
					}

					setTimeout(function () {
						var pos = $(e.target).closest('.header-search').find('.nav-dropdown').attr('style');

						if (typeof pos == 'string' && pos.length > 0) {
							style = pos;
						}

						refreshDropdownPosition = setInterval(function () {

							if ($('body').hasClass('dgwt-wcas-open') && style.length > 0) {
								$('.nav-dropdown').attr('style', style);
							}


							if (!$('body').hasClass('dgwt-wcas-open') && !$('.header-search').hasClass('current-dropdown')) {
								clearInterval(refreshDropdownPosition);
								$('.nav-dropdown').removeAttr('style');
								style = '';
								positioning = false;
							}

						}, 10)

					}, 400);

					positioning = true;
				});

				$(document).on('click', '.header-search-lightbox .header-button a', function () {
					var formWrapper = $('#search-lightbox').find('.dgwt-wcas-search-wrapp');
					setTimeout(function () {
						if (formWrapper.find('.dgwt-wcas-close')[0]) {
							formWrapper.find('.dgwt-wcas-close')[0].click();
						}

						formWrapper.removeClass('dgwt-wcas-flatsome-up');
						formWrapper.find('.dgwt-wcas-search-input').focus();
					}, 1);
				});

				// Mobile
				$(document).on('click', '.mobile-nav .header-search .icon-search', function () {

					var $handler = $('.mobile-nav .header-search').find('.js-dgwt-wcas-enable-mobile-form');
					if ($handler.length) {
						$handler[0].click();
					}
				});
			});
		})(jQuery);
	</script>
	<?php
}, 1000 );

add_action( 'wp_footer', function () { ?>
	<script>
		(function ($) {
			// Fix Quantity buttons
			$(document).on('dgwtWcasDetailsPanelLoaded', function () {
				var $quantityFields = $('.dgwt-wcas-details-wrapp .quantity');

				if ($quantityFields.length) {
					$quantityFields.addQty();
				}
			});
		})(jQuery);
	</script>
	<?php
}, 1001 );
