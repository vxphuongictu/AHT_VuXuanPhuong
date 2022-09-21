<?php
// Exit if accessed directly
if ( ! defined( 'DGWT_WCAS_FILE' ) ) {
	exit;
}

add_action( 'wp_head', function () {
	?>
	<style>
		.et_b_header-search > form {
			display: none;
		}
	</style>
	<?php
} );

add_action( 'wp_footer', function () {
	$search_type_desktop      = get_theme_mod( 'search_type_et-desktop', 'input' );
	$top_header_color_desktop = get_theme_mod( 'top_header_color_et-desktop', '#ffffff' );
	$search_type_mobile       = get_theme_mod( 'search_type_et-mobile', 'icon' );
	$top_header_color_mobile  = get_theme_mod( 'top_header_color_et-mobile', '#ffffff' );

	if ( $search_type_desktop === 'input' ) {
		echo '<div id="wcas-desktop-search" style="display: none;">' . do_shortcode( '[wcas-search-form]' ) . '</div>';
		?>
		<script>
			var desktopSearch = document.querySelector('.header-wrapper .et_b_header-search > form');
			if (desktopSearch !== null) {
				desktopSearch.replaceWith(document.querySelector('#wcas-desktop-search > div'));
			}
			document.querySelector('#wcas-desktop-search').remove()
		</script>
		<style>
			.header-wrapper .dgwt-wcas-search-wrapp {
				max-width: none;
			}
		</style>
		<?php
	} elseif ( $search_type_desktop === 'icon' ) {
		echo '<div id="wcas-desktop-search" style="display: none;">' . do_shortcode( '[wcas-search-form layout="icon"]' ) . '</div>';
		?>
		<script>
			var desktopSearch = document.querySelector('.header-wrapper .et_b_header-search > .et_b_search-icon');
			if (desktopSearch !== null) {
				desktopSearch.replaceWith(document.querySelector('#wcas-desktop-search > div'));
			}
			document.querySelector('#wcas-desktop-search').remove();
		</script>
		<style>
			.header-wrapper .et_b_header-search .dgwt-wcas-ico-magnifier-handler {
				max-width: 18px;
				width: 1.5em !important;
				height: 1.5em !important;
			}

			.header-wrapper .dgwt-wcas-search-icon path {
				fill: <?php echo $top_header_color_desktop; ?>;
			}

			.header-wrapper .dgwt-wcas-search-icon:hover path {
				fill: <?php echo $top_header_color_desktop; ?>;
			}
		</style>
		<?php
	}

	if ( $search_type_mobile === 'input' ) {
		echo '<div id="wcas-mobile-search" style="display: none;">' . do_shortcode( '[wcas-search-form]' ) . '</div>';
		?>
		<script>
			var mobileSearch = document.querySelector('.mobile-header-wrapper .et_b_header-search > form');
			if (mobileSearch !== null) {
				mobileSearch.replaceWith(document.querySelector('#wcas-mobile-search > div'));
			}
			document.querySelector('#wcas-mobile-search').remove();
		</script>
		<?php
	} elseif ( $search_type_mobile === 'icon' ) {
		echo '<div id="wcas-mobile-search" style="display: none;">' . do_shortcode( '[wcas-search-form layout="icon"]' ) . '</div>';
		?>
		<script>
			var mobileSearch = document.querySelector('.mobile-header-wrapper .et_b_header-search > .et_b_search-icon');
			if (mobileSearch !== null) {
				mobileSearch.replaceWith(document.querySelector('#wcas-mobile-search > div'));
			}
			document.querySelector('#wcas-mobile-search').remove();
		</script>
		<style>
			.mobile-header-wrapper .dgwt-wcas-search-wrapp {
				max-width: 30px !important;
				min-width: 30px;
			}

			.mobile-header-wrapper .et_b_header-search .dgwt-wcas-ico-magnifier-handler {
				max-width: 18px;
				width: 1.5em !important;
				height: 1.5em !important;
			}

			.mobile-header-wrapper .dgwt-wcas-search-icon path {
				fill: <?php echo $top_header_color_mobile; ?>;
			}

			.mobile-header-wrapper .dgwt-wcas-search-icon:hover path {
				fill: <?php echo $top_header_color_mobile; ?>;
			}
		</style>
		<?php
	}
} );
