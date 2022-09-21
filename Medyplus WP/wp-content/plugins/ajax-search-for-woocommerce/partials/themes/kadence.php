<?php
// Exit if accessed directly

if ( ! defined( 'DGWT_WCAS_FILE' ) ) {
	exit;
}

function fibosearchKadenceRenderColor( $color ) {
	if ( empty( $color ) ) {
		return false;
	}
	if ( ! is_array( $color ) && strpos( $color, 'palette' ) !== false ) {
		$color = 'var(--global-' . $color . ')';
	}

	return $color;
}

add_action( 'after_setup_theme', function () {
	remove_action( 'kadence_header_search', 'Kadence\header_search' );
} );

add_action( 'kadence_header_search', function () {
	echo do_shortcode( '[fibosearch layout="icon"]' );
} );

add_action( 'wp_footer', function () {
	$color      = '';
	$hoverColor = '';
	if ( function_exists( 'Kadence\kadence' ) ) {
		$color      = Kadence\kadence()->sub_option( 'header_search_color', 'color' );
		$hoverColor = Kadence\kadence()->sub_option( 'header_search_color', 'hover' );
	}
	?>
	<style>
		<?php if (!empty($color)) { ?>
		.dgwt-wcas-search-icon path {
			fill: <?php echo fibosearchKadenceRenderColor($color); ?>;
		}

		<?php } ?>

		<?php if (!empty($hoverColor)) { ?>
		.dgwt-wcas-search-icon:hover path {
			fill: <?php echo fibosearchKadenceRenderColor($hoverColor); ?>;
		}

		<?php } ?>
	</style>
	<?php
} );
