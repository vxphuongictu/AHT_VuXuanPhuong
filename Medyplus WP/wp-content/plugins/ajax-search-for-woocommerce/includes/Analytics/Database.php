<?php

namespace DgoraWcas\Analytics;

use DgoraWcas\Helpers;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Database {

	const DB_NAME           = 'dgwt_wcas_stats';
	const DB_VERSION        = 2;
	const DB_VERSION_OPTION = 'dgwt_wcas_stats_db_version';

	/**
	 * Add table names to the $wpdb object
	 *
	 * @return void
	 */
	public static function registerTables() {
		global $wpdb;

		$wpdb->dgwt_wcas_stats = $wpdb->prefix . self::DB_NAME;
		$wpdb->tables[]        = self::DB_NAME;
	}

	/**
	 * Install DB if necessary
	 *
	 * @return void
	 */
	public static function maybeInstall() {
		if ( ! self::exist() ) {
			self::install();
		} else {
			$dbVersion = get_option( self::DB_VERSION_OPTION );

			if ( absint( $dbVersion ) !== self::DB_VERSION ) {
				self::install();
			}
		}
	}

	/**
	 * Install DB table
	 *
	 * @return void
	 */
	private static function install() {
		global $wpdb;

		$wpdb->hide_errors();
		$freshInstall = ! self::exist();

		$upFile = ABSPATH . 'wp-admin/includes/upgrade.php';

		if ( file_exists( $upFile ) ) {

			require_once( $upFile );

			$collate = Helpers::getCollate( 'stats/main' );

			$table = "CREATE TABLE $wpdb->dgwt_wcas_stats (
				id              BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
				phrase          VARCHAR(255) NOT NULL,
				hits            INT NOT NULL,
				created_at      DATETIME NOT NULL DEFAULT '1000-01-01 00:00:00',
				autocomplete    TINYINT(1) NULL DEFAULT 1,
				solved          TINYINT(1) NULL DEFAULT 0,
				lang            VARCHAR(7) NULL,
				PRIMARY KEY    (id)
			) ENGINE=InnoDB $collate;";

			dbDelta( $table );

			if ( $freshInstall ) {
				//@TODO mount index for columns if necessary
			}

			update_option( self::DB_VERSION_OPTION, self::DB_VERSION );
		}
	}

	/**
	 * Check if the table exists
	 *
	 * @return bool
	 */
	public static function exist() {
		global $wpdb;

		return Helpers::isTableExists( $wpdb->dgwt_wcas_stats );
	}

	/**
	 * Remove DB table
	 *
	 * @return void
	 */
	public static function remove() {
		global $wpdb;

		$wpdb->hide_errors();

		$wpdb->query( "DROP TABLE IF EXISTS $wpdb->dgwt_wcas_stats" );
		delete_option( self::DB_VERSION_OPTION );
	}

	/**
	 * Wipe old analytics records
	 *
	 * @param int $daysAgo Minimum age of records to be deleted.
	 *
	 * @return void
	 */
	public static function wipeOldRecords( $daysAgo = 0 ) {
		global $wpdb;

		if ( ! self::exist() ) {
			return;
		}

		if ( intval( $daysAgo ) <= 0 ) {
			$daysAgo = Maintenance::ANALYTICS_EXPIRATION_IN_DAYS;
		}

		$wpdb->hide_errors();

		// Delete expired records.
		$daysAgo = date( 'Y-m-d H:i:s', strtotime( "today - $daysAgo days" ) );
		$wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->dgwt_wcas_stats WHERE DATE(created_at) <= %s", $daysAgo ) );

		// Delete non-critical records.
		if (
			defined( 'DGWT_WCAS_ANALYTICS_ONLY_CRITICAL' ) &&
			DGWT_WCAS_ANALYTICS_ONLY_CRITICAL
		) {
			$wpdb->query( "DELETE FROM $wpdb->dgwt_wcas_stats WHERE hits > 0" );
		}
	}

	/**
	 * Wipe all analytics records
	 *
	 * @return void
	 */
	public static function wipeAllRecords() {
		global $wpdb;

		if ( ! self::exist() ) {
			return;
		}

		$wpdb->hide_errors();

		$wpdb->query( "DELETE FROM $wpdb->dgwt_wcas_stats" );
	}

	/**
	 * Get the number of records
	 *
	 * @return int
	 */
	public static function getRecordsCount() {
		global $wpdb;

		if ( ! self::exist() ) {
			return 0;
		}

		$wpdb->hide_errors();

		return intval( $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->dgwt_wcas_stats" ) );
	}
}
