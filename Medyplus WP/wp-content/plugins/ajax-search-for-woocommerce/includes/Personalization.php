<?php

namespace DgoraWcas;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Personalization {

	public function __construct() {

		add_action( 'wp_head', array( $this, 'printStyle' ) );

	}

	/**
	 * Add personalized CSS
	 *
	 * @return void
	 */
	public function printStyle() {


		// Search form
		$show_submit        = DGWT_WCAS()->settings->getOption( 'show_submit_button' );
		$bg_search_input    = DGWT_WCAS()->settings->getOption( 'bg_input_color' );
		$text_input_color   = DGWT_WCAS()->settings->getOption( 'text_input_color' );
		$border_input_color = DGWT_WCAS()->settings->getOption( 'border_input_color' );
		$bg_submit_color    = DGWT_WCAS()->settings->getOption( 'bg_submit_color' );
		$text_submit_color  = DGWT_WCAS()->settings->getOption( 'text_submit_color' );

		// Suggestions
		$sug_hover_color     = DGWT_WCAS()->settings->getOption( 'sug_hover_color' );
		$sug_highlight_color = DGWT_WCAS()->settings->getOption( 'sug_highlight_color' );
		$sug_text_color      = DGWT_WCAS()->settings->getOption( 'sug_text_color' );
		$sug_bg_color        = DGWT_WCAS()->settings->getOption( 'sug_bg_color' );
		$sug_border_color    = DGWT_WCAS()->settings->getOption( 'sug_border_color' );

		$preloader_url = trim( DGWT_WCAS()->settings->getOption( 'preloader_url' ) );

		$max_form_width = absint( DGWT_WCAS()->settings->getOption( 'max_form_width' ) );

		$search_icon_color = DGWT_WCAS()->settings->getOption( 'search_icon_color' );

		ob_start();
		?>
		<style>

			.dgwt-wcas-ico-magnifier,
			.dgwt-wcas-ico-magnifier-handler {
				max-width: 20px;
			}

			.dgwt-wcas-search-wrapp {
			<?php if(!empty($max_form_width)): ?> max-width: <?php echo $max_form_width; ?>px;
			<?php endif; ?>
			}

			<?php if ( !empty( $bg_search_input ) || !empty( $text_input_color ) || !empty( $border_input_color ) ): ?>
			.dgwt-wcas-search-wrapp .dgwt-wcas-sf-wrapp input[type="search"].dgwt-wcas-search-input,
			.dgwt-wcas-search-wrapp .dgwt-wcas-sf-wrapp input[type="search"].dgwt-wcas-search-input:hover,
			.dgwt-wcas-search-wrapp .dgwt-wcas-sf-wrapp input[type="search"].dgwt-wcas-search-input:focus {
			<?php echo!empty( $bg_search_input ) ? 'background-color:' . sanitize_text_field( $bg_search_input ) . ';' : ''; ?><?php echo!empty( $text_input_color ) ? 'color:' . sanitize_text_field( $text_input_color ) . ';' : ''; ?><?php echo!empty( $border_input_color ) ? 'border-color:' . sanitize_text_field( $border_input_color ) . ';' : ''; ?>
			}

			<?php if ( !empty( $text_input_color ) ): ?>
			.dgwt-wcas-sf-wrapp input[type="search"].dgwt-wcas-search-input::placeholder {
				color: <?php echo sanitize_text_field( $text_input_color ); ?>;
				opacity: 0.3;
			}

			.dgwt-wcas-sf-wrapp input[type="search"].dgwt-wcas-search-input::-webkit-input-placeholder {
				color: <?php echo sanitize_text_field( $text_input_color ); ?>;
				opacity: 0.3;
			}

			.dgwt-wcas-sf-wrapp input[type="search"].dgwt-wcas-search-input:-moz-placeholder {
				color: <?php echo sanitize_text_field( $text_input_color ); ?>;
				opacity: 0.3;
			}

			.dgwt-wcas-sf-wrapp input[type="search"].dgwt-wcas-search-input::-moz-placeholder {
				color: <?php echo sanitize_text_field( $text_input_color ); ?>;
				opacity: 0.3;
			}

			.dgwt-wcas-sf-wrapp input[type="search"].dgwt-wcas-search-input:-ms-input-placeholder {
				color: <?php echo sanitize_text_field( $text_input_color ); ?>;
			}

			.dgwt-wcas-no-submit.dgwt-wcas-search-wrapp .dgwt-wcas-ico-magnifier path,
			.dgwt-wcas-search-wrapp .dgwt-wcas-close path {
				fill: <?php echo sanitize_text_field( $text_input_color ); ?>;
			}

			.dgwt-wcas-loader-circular-path {
				stroke: <?php echo sanitize_text_field( $text_input_color ); ?>;
			}

			.dgwt-wcas-preloader {
				opacity: 0.6;
			}

			<?php endif; ?>
			<?php endif; ?>

			<?php
			// Submit button
			if ( $show_submit === 'on' && (!empty( $bg_submit_color ) || !empty( $text_submit_color )) ): ?>
			.dgwt-wcas-search-wrapp .dgwt-wcas-sf-wrapp .dgwt-wcas-search-submit::before {
			<?php echo !empty( $bg_submit_color ) ? 'border-color: transparent ' . sanitize_text_field( $bg_submit_color ) . ';' : ''; ?>
			}

			.dgwt-wcas-search-wrapp .dgwt-wcas-sf-wrapp .dgwt-wcas-search-submit:hover::before,
			.dgwt-wcas-search-wrapp .dgwt-wcas-sf-wrapp .dgwt-wcas-search-submit:focus::before {
			<?php echo!empty( $bg_submit_color ) ? 'border-right-color: ' . sanitize_text_field( $bg_submit_color ) . ';' : ''; ?>
			}

			.dgwt-wcas-search-wrapp .dgwt-wcas-sf-wrapp .dgwt-wcas-search-submit,
			.dgwt-wcas-om-bar .dgwt-wcas-om-return {
			<?php echo!empty( $bg_submit_color ) ? 'background-color: ' . sanitize_text_field( $bg_submit_color ) . ';' : ''; ?><?php echo!empty( $text_submit_color ) ? 'color: ' . sanitize_text_field( $text_submit_color ) . ';' : ''; ?>
			}

			.dgwt-wcas-search-wrapp .dgwt-wcas-ico-magnifier,
			.dgwt-wcas-search-wrapp .dgwt-wcas-sf-wrapp .dgwt-wcas-search-submit svg path,
			.dgwt-wcas-om-bar .dgwt-wcas-om-return svg path {
			<?php echo!empty( $text_submit_color ) ? 'fill: ' . sanitize_text_field( $text_submit_color ) . ';' : ''; ?>
			}

			<?php endif; ?>

			<?php if ( !empty( $sug_bg_color ) ): ?>
			.dgwt-wcas-suggestions-wrapp,
			.dgwt-wcas-details-wrapp {
			<?php echo!empty( $sug_bg_color ) ? 'background-color: ' . sanitize_text_field( $sug_bg_color ) . ';' : ''; ?>
			}

			<?php endif; ?>

			<?php if ( !empty( $sug_hover_color ) ): ?>
			.dgwt-wcas-suggestion-selected {
			<?php echo!empty( $sug_hover_color ) ? 'background-color: ' . sanitize_text_field( $sug_hover_color ) . ';' : ''; ?>
			}

			<?php endif; ?>

			<?php if ( !empty( $sug_text_color ) ): ?>
			.dgwt-wcas-suggestions-wrapp *,
			.dgwt-wcas-details-wrapp *,
			.dgwt-wcas-sd,
			.dgwt-wcas-suggestion * {
			<?php echo!empty( $sug_text_color ) ? 'color: ' . sanitize_text_field( $sug_text_color ) . ';' : ''; ?>
			}

			<?php endif; ?>

			<?php if ( !empty( $sug_highlight_color ) ): ?>
			.dgwt-wcas-st strong,
			.dgwt-wcas-sd strong {
			<?php echo 'color: ' . sanitize_text_field( $sug_highlight_color ) . ';'; ?>
			}

			<?php endif; ?>

			<?php if ( !empty( $sug_border_color ) ): ?>
			.dgwt-wcas-suggestions-wrapp,
			.dgwt-wcas-details-wrapp,
			.dgwt-wcas-suggestion,
			.dgwt-wcas-datails-title,
			.dgwt-wcas-details-more-products {
			<?php echo 'border-color: ' . sanitize_text_field( $sug_border_color ) . '!important;'; ?>
			}

			<?php endif; ?>

			<?php if ( !empty( $preloader_url ) ): ?>
			.dgwt-wcas-inner-preloader {
				background-image: url('<?php echo esc_url( $preloader_url ); ?>');
			}

			.dgwt-wcas-inner-preloader * {
				display: none;
			}

			<?php endif; ?>

			<?php
			if(!empty($search_icon_color)){
				echo '.dgwt-wcas-search-icon { color: ' . sanitize_text_field( $search_icon_color ) . ';}';
				echo '.dgwt-wcas-search-icon path { fill: ' . sanitize_text_field( $search_icon_color ) . ';}';
			}

		?>

		</style>
		<?php
		$css = ob_get_contents();
		ob_end_clean();

		echo Helpers::minifyCSS( $css );
	}

}
