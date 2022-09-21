<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<tr class="js-dgwt-wcas-critical-searches-row">
	<td><?php echo $i; ?></td>
	<td><?php echo esc_html( $row['phrase'] ); ?></td>
	<td><?php echo esc_html( $row['qty'] ); ?></td>
	<td>
		<button class="button button-small js-dgwt-wcas-stats-critical-check"><?php _e( "Check", 'ajax-search-for-woocommerce' ); ?></button>
	</td>
</tr>
