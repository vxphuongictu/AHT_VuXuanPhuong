<?php

// Exit if accessed directly
if ( ! defined( 'DGWT_WCAS_FILE' ) ) {
	exit;
}

// Change mobile breakpoint
add_filter( 'dgwt/wcas/scripts/mobile_breakpoint', function () {
	return 1200;
} );

add_action( 'wp_footer', function () {
	echo '<div id="wcas-block-shop-search" style="display: block;">' . do_shortcode( '[wcas-search-form layout="classic" mobile_overlay="1" mobile_breakpoint="1200" ]' ) . '</div>';
	?>
	<script>
		(function ($) {
			//Autofocus
			if($(window).width() >= 1200) {

				$('.show-search').on('click', function () {
					setTimeout(function () {
					var $input = $('.search-wrapper .dgwt-wcas-search-input');
					if ($input.length > 0) {
						$input.focus();
					}
					}, 1300)
				});

			}

			$(window).on('load', function () {
				var body = $('body');
				var blockShopSearch = $('.search-wrapper');
				var search = $('#wcas-block-shop-search > div');
				if (!blockShopSearch.eq(0)) {
					return;
				}
				blockShopSearch.html(search);

				// Open overlay automatically
				$('#masthead .mobile-search-toggle').on('click', function () {
					if ($(window).width() < 1200) {
						var $handler = $('#masthead .search-wrapper .js-dgwt-wcas-enable-mobile-form');
						if ($handler.length) {
							$handler[0].click();
						}

						setTimeout(function () {
							var $closeBtn = $('#masthead .close-search');
							if ($closeBtn.length) {
								$closeBtn[0].click();
							}
						}, 1000)
					}
				});
			});
		}(jQuery));
	</script>
	<?php
} );
