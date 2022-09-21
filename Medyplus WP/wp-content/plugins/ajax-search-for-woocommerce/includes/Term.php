<?php

namespace DgoraWcas;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Term {
	protected $termID = 0;
	protected $term = null;
	protected $taxonomy = null;

	public function __construct( $term, $taxonomy = '' ) {
		if ( ! empty( $term ) && is_object( $term ) && is_a( $term, 'WP_Term' ) ) {
			$this->termID   = $term->term_id;
			$this->term     = $term;
			$this->taxonomy = $term->taxonomy;
		}

		if ( is_numeric( $term ) && ! empty( $taxonomy ) && term_exists( $term, $taxonomy ) ) {
			$termObj = get_term( $term, $taxonomy );
			if ( is_a( $termObj, 'WP_Term' ) ) {
				$this->termID   = $termObj->term_id;
				$this->term     = $termObj;
				$this->taxonomy = $termObj->taxonomy;
			}
		}
	}

	/**
	 * Get term ID (term_id)
	 * @return int
	 */
	public function getID() {
		return $this->termID;
	}

	public function getTaxonomy() {
		return $this->taxonomy;
	}

	/**
	 * @return \WP_Term|null
	 */
	public function getTermObject() {
		return $this->term;
	}

	/**
	 * Check, if class is initialized correctly
	 * @return bool
	 */
	public function isValid() {
		return is_a( $this->term, 'WP_Term' );
	}

	/**
	 * Get term thumbnail url
	 *
	 * @param string $size
	 *
	 * @return string
	 */
	public function getThumbnailSrc( $size = '' ) {
		$src  = '';
		$size = empty( $size ) ? 'dgwt-wcas-product-suggestion' : $size;

		if ( ! $this->isValid() ) {
			return $src;
		}

		$imageID = get_term_meta( $this->getID(), 'thumbnail_id', true );

		if ( ! empty( $imageID ) ) {
			$imageSrc = wp_get_attachment_image_src( $imageID, $size );

			if ( is_array( $imageSrc ) && ! empty( $imageSrc[0] ) ) {
				$src = $imageSrc[0];
			}
		}

		if ( empty( $src ) ) {
			$src = wc_placeholder_img_src( $size );
		}

		return apply_filters( 'dgwt/wcas/term/thumbnail_src', $src, $this->getID(), $size, $this );
	}
}
