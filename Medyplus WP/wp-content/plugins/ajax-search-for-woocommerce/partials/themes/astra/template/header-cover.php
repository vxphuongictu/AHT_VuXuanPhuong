<?php

// Exit if accessed directly
if ( ! defined( 'DGWT_WCAS_FILE' ) ) {
	exit;
}

/**
 * Advanced Search - Header Cover Template
 */
$header_break_point = dgwt_wcas_astra_header_break_point();
?>
<div class="ast-search-box header-cover" id="ast-search-form">
	<div class="ast-search-wrapper">
		<div class="ast-container">
			<div class="search-form">
				<div class="search-text-wrap">
					<input class="search-field" type="text" style="display:none;">
					<?php echo do_shortcode( '[wcas-search-form layout="classic" mobile_overlay="1" mobile_breakpoint="' . $header_break_point . '" ]' ); ?>
				</div>
				<span id="close" class="close"><?php Astra_Icons::get_icons( 'close', true ); ?></span>
			</div>
		</div>
	</div>
</div>
