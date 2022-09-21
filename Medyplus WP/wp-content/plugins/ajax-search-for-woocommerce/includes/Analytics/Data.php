<?php

namespace DgoraWcas\Analytics;

use DgoraWcas\Multilingual;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Data {

	/**
	 * @var string
	 */
	private $format = 'Y-m-d H:i:s';

	/**
	 * Date start in format Y-m-d H:i:s
	 * @var string
	 */
	private $dateFrom;

	/**
	 * Date end in format Y-m-d H:i:s
	 * @var string
	 */
	private $dateTo;

	/**
	 * Available values: 'autocomplete', 'search-results-page'
	 * @var string
	 */
	private $context;

	/**
	 * Language
	 * @var string
	 */
	private $lang = '';

	/**
	 * Minimum number of phrase repetitions which must occur to be recognized as critical
	 * @var int
	 */
	private $minCriticalRep = 3;

	/**
	 * Percentage limit of searches returning results.
	 * Above this limit searches will be marked as satisfying
	 * Below this limit searches will be marked as not satisfying
	 * @var int
	 */
	private $searchesReturningResutlsGoodPercent = 70;

	/**
	 * Constructor
	 *
	 */
	public function __construct() {
		$this->setDefaultDateRange();
	}

	/**
	 * Set data range
	 *
	 * @param string $from | timestamp or Y-m-d H:i:s
	 * @param string $to | timestamp or Y-m-d H:i:s
	 *
	 * @return void
	 */
	public function setDateRange( $from = '', $to = '' ) {
		$from = ! empty( $from ) && is_numeric( $from ) ? date( $this->format, $from ) : $from;
		$to   = ! empty( $to ) && is_numeric( $to ) ? date( $this->format, $to ) : $to;

		if ( ! empty( $from ) && $this->validateDate( $from ) ) {
			$this->dateFrom = $from;
		}

		if ( ! empty( $to ) && $this->validateDate( $to ) ) {
			$this->dateTo = $to;
		}
	}

	/**
	 * Set minimum number of phrase repetitions which must occur to be recognized as critical
	 *
	 * @param int $rep
	 *
	 * @return void
	 */
	public function minCriticalRep( $rep ) {
		if ( is_numeric( $rep ) && $rep > 0 ) {
			$this->minCriticalRep = $rep;
		}
	}

	/**
	 * Set default data range - last 30 days
	 *
	 * @return void
	 */
	public function setDefaultDateRange() {
		$this->dateFrom = date( $this->format, strtotime( 'today - 30 days' ) );
		$this->dateTo   = date( $this->format );
	}

	/**
	 * Set language
	 *
	 * @param string $lang
	 *
	 * @return void
	 */
	public function setLang( $lang ) {
		if ( Multilingual::isMultilingual() && ! empty( $lang ) ) {
			$this->lang = Multilingual::getDefaultLanguage();
		}

		if ( Multilingual::isLangCode( $lang ) ) {
			$this->lang = $lang;
		}
	}

	/**
	 * Set context
	 *
	 * @param string $context | Available values: 'autocomplete', 'search-results-page'
	 *
	 * @return void
	 */
	public function setContext( $context ) {
		if ( ! in_array( $context, array( 'autocomplete', 'search-results-page' ) ) ) {
			$context = 'autocomplete';
		}

		$this->context = $context;
	}

	/**
	 * Get phrases without search results
	 *
	 * @return array
	 */
	public function getPhrasesWithNoResults() {
		return $this->getPhrases( $this->dateFrom, $this->dateTo, $this->context, false );
	}

	/**
	 * Get phrases with search results
	 *
	 * @param int $limit
	 * @param int $offset
	 *
	 * @return array
	 */
	public function getPhrasesWithResults( $limit = 10, $offset = 0 ) {
		return $this->getPhrases( $this->dateFrom, $this->dateTo, $this->context, true, null, $limit, $offset );
	}

	/**
	 * Get total searches
	 *
	 * @param bool $hasResults
	 * @param bool $unique - count only unique values
	 *
	 * @return int
	 */
	public function getTotalSearches( $hasResults, $unique = false ) {
		global $wpdb;
		$total  = 0;
		$select = 'COUNT(id)';
		$where  = '';

		if ( $unique ) {
			$select = 'COUNT(DISTINCT phrase)';
		}

		// Context
		if ( $this->context === 'autocomplete' ) {
			$where .= " AND autocomplete = 1";
		} else {
			$where .= " AND autocomplete = 0";
		}

		//With results or with no results
		if ( $hasResults ) {
			$where .= " AND hits > 0";
		} else {
			$where .= " AND hits = 0";
		}

		// Language
		$where .= $this->getLanguageSql();

		$sql = $wpdb->prepare( "SELECT $select
                                     FROM $wpdb->dgwt_wcas_stats
                                     WHERE 1=1
                                     $where
                                     AND created_at > %s AND created_at < %s", $this->dateFrom, $this->dateTo );

		$res = $wpdb->get_var( $sql );
		if ( ! empty( $res ) && is_numeric( $res ) ) {
			$total = absint( $res );
		}

		return $total;
	}

	/**
	 * Get SQL where clause related to language
	 *
	 * @param string $lang
	 *
	 * @return string
	 */
	public function getLanguageSql( $lang = '' ) {
		global $wpdb;

		$where = '';

		if ( Multilingual::isMultilingual() ) {
			if ( empty( $lang ) ) {
				$lang = $this->lang;
			}

			if ( Multilingual::getDefaultLanguage() === $lang ) {
				$where .= $wpdb->prepare( " AND (lang = %s OR lang IS NULL)", $lang );
			} else {
				$where .= $wpdb->prepare( " AND lang = %s", $lang );
			}
		}

		return $where;
	}

	/**
	 * Get total critical searches
	 *
	 * @return int
	 */
	public function getTotalCriticalSearches() {
		global $wpdb;

		$total = 0;
		$where = '';

		// Language
		$where .= $this->getLanguageSql();

		$sql = $wpdb->prepare( "SELECT COUNT(*) AS total FROM (
                                     SELECT phrase, COUNT(id) AS qty
                                     FROM $wpdb->dgwt_wcas_stats
                                     WHERE 1=1
                                     AND created_at > %s AND created_at < %s
                                     AND solved = 0
                                     AND hits = 0
                                     $where
                                     GROUP BY phrase
                                     HAVING qty >= %d) AS total", $this->dateFrom, $this->dateTo, $this->minCriticalRep );

		$res = $wpdb->get_var( $sql );
		if ( ! empty( $res ) && is_numeric( $res ) ) {
			$total = absint( $res );
		}

		return $total;
	}

	/**
	 * Get critical searches
	 *
	 * @param int $limit
	 * @param int $offset
	 *
	 * @return array
	 */
	public function getCriticalSearches( $limit = 10, $offset = 0 ) {
		global $wpdb;
		$phrases = array();
		$where   = '';

		// Language
		$where .= $this->getLanguageSql();

		$sql = $wpdb->prepare( "SELECT phrase, COUNT(id) AS qty
                                     FROM $wpdb->dgwt_wcas_stats
                                     WHERE hits = 0
                                     AND created_at > %s AND created_at < %s
                                     AND solved = 0
                                     $where
                                     GROUP BY phrase
                                     HAVING qty >= %d
                                     ORDER BY qty DESC, phrase ASC LIMIT %d,%d", $this->dateFrom, $this->dateTo, $this->minCriticalRep, $offset, $limit );

		$res = $wpdb->get_results( $sql, ARRAY_A );
		if ( ! empty( $res ) && is_array( $res ) ) {
			$phrases = $res;

			foreach ( $res as $key => $search ) {
				$phrases[ $key ] = $search;
			}
		}

		return $phrases;
	}


	/**
	 *
	 * Get search phrases with the frequency of occurrences
	 *
	 * @param string $dateFrom
	 * @param string $dateTo
	 * @param string $context
	 * @param bool $hasResults
	 * @param bool $solved
	 * @param int $limit
	 * @param int $offset
	 *
	 * @return array
	 */
	private function getPhrases( $dateFrom, $dateTo, $context, $hasResults, $solved = null, $limit = 10, $offset = 0 ) {
		global $wpdb;

		$output = array();
		$where  = '';

		// Context
		if ( $context === 'autocomplete' ) {
			$where .= " AND autocomplete = 1";
		} else {
			$where .= " AND autocomplete = 0";
		}

		//With results or with no results
		if ( $hasResults ) {
			$where .= " AND hits > 0";
		} else {
			$where .= " AND hits = 0";
		}

		if ( is_bool( $solved ) ) {
			if ( $solved ) {
				$where .= " AND solved = 1";
			} else {
				$where .= " AND solved = 0";
			}
		}

		// Language
		$where .= $this->getLanguageSql();

		$sql = $wpdb->prepare( "SELECT phrase, COUNT(id) AS qty
                                     FROM $wpdb->dgwt_wcas_stats
                                     WHERE 1=1
                                     AND created_at > %s AND created_at < %s
                                     $where
                                     GROUP BY phrase
                                     ORDER BY qty DESC, phrase ASC LIMIT %d,%d", $dateFrom, $dateTo, $offset, $limit );

		$res = $wpdb->get_results( $sql, ARRAY_A );
		if ( ! empty( $res ) && is_array( $res ) ) {
			$output = $res;
		}

		return $output;
	}

	/**
	 * Mark as solved. Exclude the phrase from critical phrases module.
	 *
	 * @return bool
	 */
	function markAsSolved( $phrase ) {
		global $wpdb;
		$success = false;

		$data = array(
			'solved' => 1
		);

		$where = array(
			'phrase' => $phrase
		);

		$format = array( '%s' );

		if ( ! empty( $this->lang ) ) {
			$where['lang'] = $this->lang;
			$format[]      = '%s';
		}

		if ( $wpdb->update( $wpdb->dgwt_wcas_stats, $data, $where, $format ) ) {
			$success = true;
		}

		return $success;
	}

	/**
	 * Check if the date has properly format
	 *
	 * @return bool
	 */
	function validateDate( $date, $format = 'Y-m-d H:i:s' ) {
		$d = \DateTime::createFromFormat( $format, $date );

		return $d && $d->format( $format ) === $date;
	}

	/**
	 * Check if the date has properly format
	 *
	 * @param int $percentage
	 *
	 * @return bool
	 */
	function isSearchesReturningResutlsSatisfying( $percentage ) {
		return $percentage >= $this->searchesReturningResutlsGoodPercent;
	}
}
