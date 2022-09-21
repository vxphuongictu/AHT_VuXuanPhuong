<?php
// Exit if accessed directly
if ( ! defined( 'DGWT_WCAS_FILE' ) ) {
	exit;
}

$astra_settings           = get_option( 'astra-settings' );
$is_header_footer_builder = isset( $astra_settings['is-header-footer-builder'] ) ? (bool) $astra_settings['is-header-footer-builder'] : true;

if ( $is_header_footer_builder ) {
	require_once 'astra/builder.php';
} else {
	require_once 'astra/legacy.php';
}
