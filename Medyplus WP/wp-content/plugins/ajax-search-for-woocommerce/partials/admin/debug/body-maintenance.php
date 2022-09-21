<?php
// Exit if accessed directly
if ( ! defined( 'DGWT_WCAS_FILE' ) ) {
	exit;
}

if (
	! empty( $_REQUEST['dgwt-wcas-debug-setting-reset-to-default'] ) &&
	! empty( $_REQUEST['_wpnonce'] ) &&
	wp_verify_nonce( $_REQUEST['_wpnonce'], 'dgwt_wcas_debug_reset_settings' )
) {
	global $dgwtWcasSettings;

	$dgwtWcasSettings = array();
	delete_option( DGWT_WCAS_SETTINGS_KEY );
	DgoraWcas\Admin\Install::createOptions();
	?>
	<div class="dgwt-wcas-notice notice notice-success">
		<p>The settings have been reset to the default values.</p>
	</div>
	<?php
}

?>
	<h3>Settings</h3>
	<form action="<?php echo admin_url( 'admin.php' ); ?>" method="get"
		  onsubmit="return confirm('Are you sure you want to reset the settings?');">
		<input type="hidden" name="page" value="dgwt_wcas_debug">
		<?php wp_nonce_field( 'dgwt_wcas_debug_reset_settings', '_wpnonce', false ); ?>
		<input type="submit" name="dgwt-wcas-debug-setting-reset-to-default" class="button"
			   value="Reset the plugin settings to default values">
	</form>
<?php
