<?php
// Exit if accessed directly
if ( ! defined( 'DGWT_WCAS_FILE' ) ) {
	exit;
}

if (
	! empty( $_REQUEST['dgwt-wcas-debug-analytics-delete-all-records'] ) &&
	! empty( $_REQUEST['_wpnonce'] ) &&
	wp_verify_nonce( $_REQUEST['_wpnonce'], 'dgwt_wcas_debug_analytics' )
) {
	\DgoraWcas\Analytics\Database::wipeAllRecords();
	?>
	<div class="dgwt-wcas-notice notice notice-success">
		<p>All analytics records have been deleted.</p>
	</div>
	<?php
}

if (
	! empty( $_REQUEST['dgwt-wcas-debug-analytics-run-maintenance-task'] ) &&
	! empty( $_REQUEST['_wpnonce'] ) &&
	wp_verify_nonce( $_REQUEST['_wpnonce'], 'dgwt_wcas_debug_analytics' )
) {
	do_action( \DgoraWcas\Analytics\Maintenance::HOOK );
	?>
	<div class="dgwt-wcas-notice notice notice-success">
		<p>The task of maintaining the search analytics has been completed.</p>
	</div>
	<?php
}

?>
	<h3>Analytics</h3>
	<form action="<?php echo admin_url( 'admin.php' ); ?>" method="get">
		<input type="hidden" name="page" value="dgwt_wcas_debug">
		<?php wp_nonce_field( 'dgwt_wcas_debug_analytics', '_wpnonce', false ); ?>
		<input type="submit" name="dgwt-wcas-debug-analytics-delete-all-records" class="button" value="Delete all records">
		<input type="submit" name="dgwt-wcas-debug-analytics-run-maintenance-task" class="button" value="Run maintenance task">
	</form>

	<table class="wc_status_table widefat dgwt-wcas-table-debug-analytics">
		<tr>
			<td><b>Does the table exist?</b></td>
			<td><?php echo \DgoraWcas\Analytics\Database::exist() ? 'yes' : 'no'; ?></td>
		</tr>
		<tr>
			<td><b>Total records</b></td>
			<td><?php echo \DgoraWcas\Analytics\Database::getRecordsCount(); ?></td>
		</tr>
		<tr>
			<td><b>Is the maintenance task scheduled?</b></td>
			<td><?php echo wp_next_scheduled( \DgoraWcas\Analytics\Maintenance::HOOK ) ? 'yes' : 'no'; ?></td>
		</tr>
		<?php if ( defined( 'DGWT_WCAS_ANALYTICS_ONLY_CRITICAL' ) ) { ?>
			<tr>
				<td><b>Constant <code>DGWT_WCAS_ANALYTICS_ONLY_CRITICAL</code></b></td>
				<td><?php echo DGWT_WCAS_ANALYTICS_ONLY_CRITICAL; ?></td>
			</tr>
		<?php } ?>
		<?php if ( defined( 'DGWT_WCAS_ANALYTICS_EXPIRATION_IN_DAYS' ) ) { ?>
			<tr>
				<td><b>Constant <code>DGWT_WCAS_ANALYTICS_EXPIRATION_IN_DAYS</code></b></td>
				<td><?php echo DGWT_WCAS_ANALYTICS_EXPIRATION_IN_DAYS; ?></td>
			</tr>
		<?php } ?>
	</table>
<?php
