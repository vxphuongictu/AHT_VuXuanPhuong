<?php

// Exit if accessed directly
if ( ! defined( 'DGWT_WCAS_FILE' ) ) {
	exit;
}

add_action( 'wp_head', function () {
	?>
	<style>
		.page_header .qode_search_form .dgwt-wcas-search-wrapp {
			max-width: 100%;
			margin-top: 5px;
		}

		.page_header .qode_search_form_2 .dgwt-wcas-search-wrapp {
			max-width: 100%;
			margin-top: 30px;
		}

		.page_header .qode_search_form_2 .dgwt-wcas-search-wrapp input[type=search],
		.page_header .qode_search_form_3 .dgwt-wcas-search-wrapp input[type=search] {
			text-transform: none;
		}

		.page_header .qode_search_form_2:not(.animated) .dgwt-wcas-preloader {
			display: none;
		}

		.page_header .qode_search_form_3 .dgwt-wcas-search-wrapp {
			max-width: 100%;
		}
	</style>
	<?php
} );

add_action( 'wp_footer', function () {
	if ( ! function_exists( 'bridge_qode_options' ) ) {
		return;
	}
	if ( bridge_qode_options()->getOptionValue( 'enable_search' ) !== 'yes' ) {
		return;
	}
	$search_type = bridge_qode_options()->getOptionValue( 'search_type' );
	if ( $search_type === 'search_slides_from_window_top' ) { ?>
		<div id="wcas-bridge-search" style="display: block;">
			<div class="qode_search_form">
				<div class="container">
					<div class="container_inner clearfix">
						<?php echo do_shortcode( '[wcas-search-form]' ); ?>
					</div>
				</div>
			</div>
		</div>
		<script>
			var bridgeSearch = document.querySelector('#searchform');
			if (bridgeSearch !== null) {
				bridgeSearch.replaceWith(document.querySelector('#wcas-bridge-search > div'));
			}
		</script>
	<?php } else if ( $search_type === 'search_slides_from_header_bottom' ) { ?>
		<div id="wcas-bridge-search" style="display: block;">
			<div class="qode_search_form_2">
				<div class="container">
					<div class="container_inner clearfix">
						<div class="form_holder_outer">
							<?php echo do_shortcode( '[wcas-search-form]' ); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script>
			var bridgeSearch = document.querySelector('.page_header .qode_search_form_2');
			if (bridgeSearch !== null) {
				bridgeSearch.replaceWith(document.querySelector('#wcas-bridge-search > div'));
			}
		</script>
	<?php } else if ( $search_type === 'search_covers_header' ) { ?>
		<div id="wcas-bridge-search" style="display: block;">
			<div class="qode_search_form_3">
				<div class="container">
					<div class="container_inner clearfix">
						<div class="form_holder_outer">
							<div class="form_holder">
								<?php echo do_shortcode( '[wcas-search-form]' ); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script>
			var bridgeSearch = document.querySelector('.page_header .qode_search_form_3');
			if (bridgeSearch !== null) {
				bridgeSearch.replaceWith(document.querySelector('#wcas-bridge-search > div'));
			}
		</script>
	<?php } else if ( $search_type === 'fullscreen_search' ) { ?>
		<div id="wcas-bridge-search" style="display: block;">
			<div class="fullscreen_search_form">
				<div class="form_holder">
					<?php echo do_shortcode( '[wcas-search-form]' ); ?>
				</div>
			</div>
		</div>
		<script>
			var bridgeSearch = document.querySelector('.fullscreen_search_form');
			if (bridgeSearch !== null) {
				bridgeSearch.replaceWith(document.querySelector('#wcas-bridge-search > div'));
			}
		</script>
	<?php }
} );
