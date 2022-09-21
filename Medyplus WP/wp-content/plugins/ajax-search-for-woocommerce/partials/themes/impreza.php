<?php

// Exit if accessed directly
if ( ! defined( 'DGWT_WCAS_FILE' ) ) {
	exit;
}

add_action( 'wp_head', function () { ?>
	<style>
		.w-search.layout_modern .w-search-close {

		}

		.w-search.layout_modern .w-search-close {
			color: rgba(0, 0, 0, 0.5) !important;
		}

		.w-search.layout_modern .dgwt-wcas-close {
			display: none;
		}

		.w-search.layout_modern .dgwt-wcas-preloader {
			right: 20px;
		}

		.w-search.layout_fullscreen .w-form-row-field {
			top: 48px;
		}
	</style>
	<?php
} );

add_action( 'wp_footer', function () { ?>
	<script>
		(function ($) {
			function dgwtWcasImprezaGetActiveInstance() {
				var $el = $('.dgwt-wcas-search-wrapp.dgwt-wcas-active'),
					instance;
				if ($el.length > 0) {
					$el.each(function () {
						var $input = $(this).find('.dgwt-wcas-search-input');
						if (typeof $input.data('autocomplete') == 'object') {
							instance = $input.data('autocomplete');
							return false;
						}
					});
				}

				return instance;
			}

			$(document).ready(function () {
				$('.w-search.layout_modern .w-search-close').on('click', function () {
					var instance = dgwtWcasImprezaGetActiveInstance();

					if (typeof instance == 'object') {
						instance.suggestions = [];
						instance.hide();
						instance.el.val('');
					}
				});

				$('.w-search-open').on('click', function (e) {
					if ($(window).width() < 900) {
						e.preventDefault();

						var $mobileHandler = $(e.target).closest('.w-search').find('.js-dgwt-wcas-enable-mobile-form');

						if ($mobileHandler.length) {
							$mobileHandler[0].click();
						}

						setTimeout(function () {
							$('.w-search').removeClass('active');
						}, 500);
					}
				});
			});
		})(jQuery);
	</script>
	<?php
}, 1000 );
