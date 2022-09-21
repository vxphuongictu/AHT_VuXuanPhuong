<?php

namespace DgoraWcas\Analytics;

use DgoraWcas\Helpers;
use DgoraWcas\Multilingual;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Recorder {

	public function listen() {

		Database::registerTables();

		add_action( 'dgwt/wcas/after_searching/products', array( $this, 'listener' ), 10, 3 );
	}

	/**
	 * Validate input data and save them to the index
	 *
	 * @param string $phrase
	 * @param int $hits
	 * @param string $lang
	 *
	 * @return void
	 */
	public function listener( $phrase, $hits, $lang ) {
		$autocomplete = true;

		if ( empty( $phrase ) || ! is_string( $phrase ) ) {
			return;
		}

		if ( ! is_numeric( $hits ) || $hits < 0 ) {
			return;
		}

		// Save only critical searches.
		if (
			defined( 'DGWT_WCAS_ANALYTICS_ONLY_CRITICAL' ) &&
			DGWT_WCAS_ANALYTICS_ONLY_CRITICAL &&
			$hits > 0
		) {
			return;
		}

		if ( Helpers::isProductSearchPage() || Helpers::isRemoteSearchRequest() ) {
			$autocomplete = false;
		}

		$phrase = strtolower( substr( $phrase, 0, 255 ) );
		$lang   = ! empty( $lang ) && Multilingual::isLangCode( strtolower( $lang ) ) ? strtolower( $lang ) : '';

		$this->push( $phrase, $hits, $autocomplete, $lang );
	}

	/**
	 * Push a record to the index.
	 *
	 * @param string $phrase
	 * @param int $hits
	 * @param bool $autocomplete
	 * @param string $lang
	 *
	 * @return void
	 */
	public function push( $phrase, $hits, $autocomplete, $lang ) {
		global $wpdb;

		$data = array(
			'phrase'       => $phrase,
			'hits'         => $hits,
			'created_at'   => date( 'Y-m-d H:i:s', current_time( 'timestamp', true ) ),
			'autocomplete' => $autocomplete
		);

		$format = array(
			'%s',
			'%d',
			'%s',
			'%d',
		);

		if ( ! empty( $lang ) ) {
			$data['lang'] = $lang;
			$format[]     = '%s';
		}

		$wpdb->insert( $wpdb->dgwt_wcas_stats, $data, $format );
	}
}
