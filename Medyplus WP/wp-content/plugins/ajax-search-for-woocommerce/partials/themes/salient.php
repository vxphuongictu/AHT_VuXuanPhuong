<?php
// Exit if accessed directly
if ( ! defined( 'DGWT_WCAS_FILE' ) ) {
	exit;
}

// Change mobile breakpoint
add_filter( 'dgwt/wcas/scripts/mobile_breakpoint', function () {
	return 1000;
} );

add_action( 'wp_footer', function () {
	if ( ! function_exists( 'get_nectar_theme_options' ) ) {
		return;
	}
	$nectar_options = get_nectar_theme_options();
	// Skin: 'original', 'material' or 'ascend'
	$theme_skin = ( ! empty( $nectar_options['theme-skin'] ) ) ? $nectar_options['theme-skin'] : 'original';
	$hint       = '';
	if ( $theme_skin === 'material' ) {
		$hint = '<span>' . esc_html__( 'Hit enter to search or ESC to close', 'salient' ) . '</span>';
	}
	echo '<div id="wcas-search-instance" style="display: block;"><div>' . do_shortcode( '[wcas-search-form layout="classic" mobile_overlay="1" mobile_breakpoint="1000" ]' ) . $hint . '</div></div>';
	?>
	<script>
		(function ($) {
			var searchBtn = $('#header-outer #search-btn a');

			// Replace search form
			$(window).on('load', function () {
				var themeSearch = $('#search .inner-wrap > div');
				var wcasSearch = $('#wcas-search-instance > div');
				if (wcasSearch.length === 0) {
					return;
				}
				if (themeSearch.eq(0)) {
					themeSearch.html(wcasSearch);
				}
				$('#wcas-search-instance').remove();
			});

			// Autofocus
			if ($(window).width() >= 1000) {
				searchBtn.on('click', function () {
					setTimeout(function () {
						var $input = $('#search .dgwt-wcas-search-input');
						if ($input.length > 0) {
							$input.focus();
						}
					}, 500)
				});

				$('body:not(.material) #search-box').on('blur', '.dgwt-wcas-search-input', function () {
					$('#search-outer').stop(true).fadeOut(450, 'easeOutExpo');
					searchBtn.removeClass('open-search');
				});
			}

			// Mobile search
			if ($(window).width() < 1000) {
				setTimeout(function () {
					var mobileSearchBtn = $('#header-outer .mobile-search');
					mobileSearchBtn.addClass('open-search');
					mobileSearchBtn.on('click', function () {
						var $handler = $('#search .inner-wrap .js-dgwt-wcas-enable-mobile-form');
						if ($handler.length) {
							$handler[0].click();
						}
					});
				}, 1000);
			}
		}(jQuery));
	</script>
	<?php
} );

add_action( 'wp_head', function () {
	if ( ! function_exists( 'get_nectar_theme_options' ) ) {
		return;
	}
	$nectar_options = get_nectar_theme_options();
	// Skin: 'original', 'material' or 'ascend'
	$theme_skin = ( ! empty( $nectar_options['theme-skin'] ) ) ? $nectar_options['theme-skin'] : 'original';
	?>
	<style>
		#search #close {
			display: none;
		}

		#search-outer #search .span_12 span {
			max-width: 600px;
			margin: 0 auto;
		}

		#search .dgwt-wcas-search-input:focus,
		.dgwt-wcas-overlay-mobile input[id^="dgwt-wcas-search-input"]:focus {
			border-color: #ddd;
			background-color: #ffffff !important;
		}

		<?php if ($theme_skin === 'ascend') { ?>
		#search-box {
			top: 25%;
			-webkit-transform: translateY(-25%);
			transform: translateY(-25%);
		}

		<?php } ?>
	</style>
	<?php
} );
