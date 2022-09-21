<?php
// Exit if accessed directly
if ( ! defined( 'DGWT_WCAS_FILE' ) ) {
	exit;
}

add_action( 'wp_head', function () { ?>
	<style>
		.dgwt-wcas-thegem-menu-search .minisearch {
			width: 500px;
		}

		.header-layout-perspective > .dgwt-wcas-search-wrapp {
			top: 30px;
			position: absolute;
			max-width: 600px;
			left: 270px;
			right: auto;
			margin: 0 auto;
			z-index: 10;
		}

		@media (max-width: 979px) {
			.dgwt-wcas-thegem-menu-search .minisearch {
				width: 100%;
			}

			.header-layout-fullwidth_hamburger #primary-navigation > .dgwt-wcas-search-wrapp,
			.header-layout-perspective > .dgwt-wcas-search-wrapp {
				max-width: 350px;
			}

			.header-style-vertical #site-header-wrapper .dgwt-wcas-thegem-vertical-search {
				display: none;
			}
		}

		@media (max-width: 769px) {
			.header-layout-fullwidth_hamburger #primary-navigation > .dgwt-wcas-search-wrapp,
			.header-layout-perspective > .dgwt-wcas-search-wrapp {
				display: none;
			}
		}


		#page.vertical-header .dgwt-wcas-thegem-vertical-search {
			margin-right: auto;
			margin-left: auto;
			padding-left: 21px;
			padding-right: 21px;
		}

		.header-layout-fullwidth_hamburger #primary-navigation > .dgwt-wcas-search-wrapp {
			top: 30px;
			position: absolute;
			left: 50px;
			max-width: 600px;
		}

		.site-header.fixed .header-layout-fullwidth_hamburger #primary-navigation > .dgwt-wcas-search-wrapp,
		.site-header.fixed .header-layout-perspective > .dgwt-wcas-search-wrapp {
			top: 8px;
		}

		body .header-layout-overlay #primary-menu.no-responsive.overlay-search-form-show.animated-minisearch > li.menu-item-search > .minisearch {
			top: 0;
			bottom: auto;
		}
	</style>
	<?php
} );

add_action( 'wp_footer', function () { ?>
	<script>
		(function ($) {
			$('.header-layout-overlay .dgwt-wcas-thegem-menu-search').on('click', function () {
				var $searchHandler = $(this).find('.js-dgwt-wcas-enable-mobile-form');

				if ($searchHandler.length) {
					$searchHandler[0].click();
				}
			});

			$('.dgwt-wcas-thegem-menu-search').on('click', function () {
				var $input = $(this).find('.dgwt-wcas-search-input');

				if ($input.length) {
					setTimeout(function () {
						$input.focus();
					}, 300);
				}
			});
		})(jQuery);
	</script>
	<?php
} );
