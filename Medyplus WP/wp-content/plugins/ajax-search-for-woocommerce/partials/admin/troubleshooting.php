<?php
// Exit if accessed directly
if ( ! defined( 'DGWT_WCAS_FILE' ) ) {
	exit;
}
?>
<div class="dgwt-wcas-troubleshooting-wrapper">
	<div class="progress_bar">
		<div class="pro-bar">
			<small class="progress_bar_title">
				<?php _e( 'Processing asynchronous tests...', 'ajax-search-for-woocommerce' ); ?>
				<span class="progress_number">0%</span>
			</small>
			<span class="progress-bar-inner" style="background-color: #e6a51d; width: 0;">
        </span>
		</div>
	</div>
	<p class="js-dgwt-wcas-troubleshooting-no-issues"><?php _e( 'Great! We have not detected any problems that could affect the correct functioning of our plugin.', 'ajax-search-for-woocommerce' ); ?></p>
	<div class="js-dgwt-wcas-troubleshooting-issues-critical"></div>
	<div class="js-dgwt-wcas-troubleshooting-issues-recommended"></div>
	<div>
		<?php submit_button( __( 'Recheck the status', 'ajax-search-for-woocommerce' ), 'primary', 'dgwt-wcas-reset-async-tests', false ); ?>
	</div>
</div>
<script id="tmpl-dgwt-wcas-troubleshooting-issue" type="text/template">
	<div class="dgwt-wcas-settings-info">
		<h3 class="dgwt-wcas-troubleshooting-issue-title">{{ data.label }}</h3>
		{{{ data.description }}}
		<!--{{{ data.actions }}}-->
	</div>
</script>
