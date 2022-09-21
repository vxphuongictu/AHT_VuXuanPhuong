<?php

namespace DgoraWcas\Analytics;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Maintenance {
	const HOOK                         = 'dgwt_wcas_analytics_maintenance';
	const ANALYTICS_EXPIRATION_IN_DAYS = 30;

	public function init() {
		$this->schedule();
		$this->listenCron();
	}

	/**
	 * Listen to cron action
	 *
	 * @return void
	 */
	public function listenCron() {
		add_action( self::HOOK, [ $this, 'handleMaintenance' ] );
	}

	/**
	 * Schedule maintenance task
	 *
	 * @return void
	 */
	public function schedule() {
		if ( ! wp_next_scheduled( self::HOOK ) ) {
			wp_schedule_event( strtotime( 'tomorrow' ) + 2 * HOUR_IN_SECONDS, 'daily', self::HOOK );
		}
	}

	/**
	 * Unschedule maintenance task
	 *
	 * @return void
	 */
	public function unschedule() {
		$timestamp = wp_next_scheduled( self::HOOK );
		if ( $timestamp ) {
			wp_unschedule_event( $timestamp, self::HOOK );
		}
	}

	/**
	 * Handle maintenance task
	 *
	 * @return void
	 */
	public function handleMaintenance() {
		$expiration = self::ANALYTICS_EXPIRATION_IN_DAYS;

		if (
			defined( 'DGWT_WCAS_ANALYTICS_EXPIRATION_IN_DAYS' ) &&
			intval( DGWT_WCAS_ANALYTICS_EXPIRATION_IN_DAYS ) > 0
		) {
			$expiration = intval( DGWT_WCAS_ANALYTICS_EXPIRATION_IN_DAYS );
		}

		Database::wipeOldRecords( $expiration );
	}
}
