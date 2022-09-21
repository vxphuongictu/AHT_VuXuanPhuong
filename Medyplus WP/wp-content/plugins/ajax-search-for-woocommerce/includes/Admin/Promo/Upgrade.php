<?php

namespace DgoraWcas\Admin\Promo;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class Upgrade {

	function __construct() {

		add_action( 'current_screen', array( $this, 'printScripts' ) );

	}


	public function printScripts() {

		if ( dgoraAsfwFs()->is_premium() ) {
			return;
		}

		$cs = get_current_screen();

		if ( ! empty( $cs->base ) && strpos( $cs->base, 'dgwt_wcas' ) !== false ) {

			add_thickbox();
			add_action( 'admin_footer', array( $this, 'renderUpgradeModal' ), 10 );
			add_action( 'admin_footer', array( $this, 'upgradeModalHandler' ), 20 );

		}

	}

	/**
	 * Render plugin upgrade modal
	 *
	 * @return void
	 */
	public function renderUpgradeModal() {
		$utmLink = 'https://fibosearch.com/pro-vs-free/?utm_source=wp-admin&utm_medium=referral&utm_campaign=upgrade-popup&utm_content=features&utm_gen=utmdc';

		$features = array(
			__( 'Speed up search! (even 10× faster) - users love it!', 'ajax-search-for-woocommerce' ),
			__( 'Modern search engine based on an inverted index and advanced matching algorithms', 'ajax-search-for-woocommerce' ),
			__( 'Fuzzy search', 'ajax-search-for-woocommerce' ),
			__( 'Synonyms', 'ajax-search-for-woocommerce' ),
			__( 'Search in attributes and variation products SKUs (option)', 'ajax-search-for-woocommerce' ),
			__( 'Individual tips and support from the FiboSearch team', 'ajax-search-for-woocommerce' )
		);
		echo '<a style="display:none;" class="thickbox js-dgwt-wcas-modal-pro-handler" href="#TB_inline?width=600&height=380&inlineId=dgwt-wcas-modal" title="' . __( 'FiboSearch Pro - Upgrade Now', 'ajax-search-for-woocommerce' ) . '"></a>';
		echo '<div id="dgwt-wcas-modal" class="dgwt-wcas-modal-upgrade" style="display:none;">';
		echo '<img class="dgwt-wcas-modal-logo" src="' . DGWT_WCAS_URL . 'assets/img/logo-128.png" width="128" height="128" />';
		echo '
		<h2 class="dgwt-wcas-modal-title">' . __( 'Update now and increase your sales. You will receive a 30-day satisfaction guarantee.  A return on investment will come very quickly.', 'ajax-search-for-woocommerce' ) . '</h2>';
		echo '<ul>';
		foreach ( $features as $feature ) {
			echo '<li><strong>+ ' . $feature . '</strong></li>';
		}
		echo '<li><strong>+ ' . __( 'and more...', 'ajax-search-for-woocommerce' ) . ' <a target="_blank" href="' . $utmLink . '">' . __( 'See a comparison of all free and premium features!', 'ajax-search-for-woocommerce' ) . '</a></strong></li>';
		echo '</ul>';
		echo '<p><a class="button-primary" target="_blank" href="' . self::getUpgradeUrl() . '">' . __( 'Upgrade Now', 'ajax-search-for-woocommerce' ) . '</a>';
		echo '</p>';
		echo '</div>';
	}

	/**
	 * JS for the upgrade modal
	 *
	 * @return void
	 */
	public function upgradeModalHandler() {
		?>
		<script>
			(function ($) {
				$(document).on('click', '.dgwt-wcas-premium-only label, .dgwt-wcas-premium-only input, .dgwt-wcas-premium-only button, .dgwt-wcas-premium-only textarea, .dgwt-wcas-premium-only--trigger', function (e) {
					triggerModal(e);
				});

				$('.dgwt-wcas-premium-only select').on('change', function (e) {
					$(this).val($(this).attr('data-default'));
					triggerModal(e);
				});

				function triggerModal(e) {
					e.preventDefault();
					$('.js-dgwt-wcas-modal-pro-handler').trigger('click');
				}
			})(jQuery);
		</script>
		<?php
	}

	/**
	 * Ge upgrade URL
	 *
	 * @param string $type
	 *
	 * @return string
	 */
	public static function getUpgradeUrl( $type = 'external' ) {

		$url = "https://fibosearch.com/pricing/?utm_source=wp-admin&utm_medium=referral&utm_campaign=upgrade-link&utm_content=upgrade-now-btn";

		if ( $type === 'internal' ) {
			$url = esc_url( dgoraAsfwFs()->get_upgrade_url() );
		}

		return $url;
	}

}
