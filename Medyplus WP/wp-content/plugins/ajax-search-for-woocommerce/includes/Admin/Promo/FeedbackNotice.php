<?php

namespace DgoraWcas\Admin\Promo;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class FeedbackNotice {

	const ACTIVATION_DATE_OPT = 'dgwt_wcas_activation_date';

	const HIDE_NOTICE_OPT = 'dgwt_wcas_dismiss_review_notice';

	const DISMISS_AJAX_ACTION = 'dgwt_wcas_dismiss_notice';

	const REVIEW_URL = 'https://wordpress.org/support/plugin/ajax-search-for-woocommerce/reviews/?filter=5';

	/**
	 * Admin notice offset
	 * @var int timestamp
	 */
	private $offset;

	function __construct() {
		$this->offset = strtotime( '-7 days' );

		add_action( 'admin_init', array( $this, 'checkInstallationDate' ) );

		add_action( 'wp_ajax_' . self::DISMISS_AJAX_ACTION, array( $this, 'dismissNotice' ) );

		add_action( 'admin_head', array( $this, 'loadStyle' ) );

		add_action( 'admin_footer', array( $this, 'printDismissJS' ) );
	}

	/**
	 * Check if is possible to display admin notice on the current screen
	 *
	 * @return bool
	 */
	private function allowDisplay() {
		$currentScreen = get_current_screen();
		if (
			! empty( $currentScreen )
			&& (
				in_array( $currentScreen->base, array( 'dashboard', 'post', 'edit' ) )
				|| strpos( $currentScreen->base, DGWT_WCAS_SETTINGS_KEY ) !== false
			)
		) {
			return true;
		}

		return false;
	}

	/**
	 * Display feedback notice
	 *
	 * @return void
	 */
	public function displayNotice() {
		global $current_user;

		if ( $this->allowDisplay() && ! dgoraAsfwFs()->is_premium() ) {
			?>

			<div class="notice-info notice dgwt-wcas-notice dgwt-wcas-review-notice">
				<div class="dgwt-wcas-review-notice-logo"></div>
				<?php printf( __( "Hey %s, it's Damian Góra from %s. You have used this free plugin for some time now, and I hope you like it!", 'ajax-search-for-woocommerce' ),
					'<strong>' . $current_user->display_name . '</strong>',
					'<strong>' . DGWT_WCAS_NAME . '</strong>'
				); ?>
				<br/>
				<?php printf( __( "The FiboSearch team have spent countless hours developing it, and it would mean a lot to me if you %ssupport it with a quick review on WordPress.org.%s", 'ajax-search-for-woocommerce' ),
					'<strong><a target="_blank" href="' . self::REVIEW_URL . '">', '</a></strong>'
				); ?>
				<div class="button-container">
					<a href="<?php echo self::REVIEW_URL; ?>" target="_blank" data-link="follow" class="button-secondary dgwt-review-notice-dismiss">
						<span class="dashicons dashicons-star-filled"></span>
						<?php printf( __( "Review %s", 'ajax-search-for-woocommerce' ), DGWT_WCAS_NAME ); ?>
					</a>
					<a href="#" class="button-secondary dgwt-review-notice-dismiss">
						<span class="dashicons dashicons-no-alt"></span>
						<?php _e( "No thanks", 'ajax-search-for-woocommerce' ); ?>
					</a>
				</div>
			</div>
			<?php
		}
	}


	/**
	 * Check instalation date
	 *
	 * @return void
	 */
	public function checkInstallationDate() {

		$date = get_option( self::ACTIVATION_DATE_OPT );
		if ( empty( $date ) ) {
			add_option( self::ACTIVATION_DATE_OPT, time() );
		}

		$notice_closed = get_option( self::HIDE_NOTICE_OPT );

		if ( empty( $notice_closed ) ) {
			$install_date = get_option( self::ACTIVATION_DATE_OPT );

			if ( $this->offset >= $install_date && current_user_can( 'install_plugins' ) ) {
				add_action( 'admin_notices', array( $this, 'displayNotice' ) );
			}
		}
	}


	/**
	 * Hide admin notice
	 *
	 * @return void
	 */
	public function dismissNotice() {
		if ( ! current_user_can( 'administrator' ) ) {
			wp_die( - 1, 403 );
		}

		check_ajax_referer( 'dgwt_wcas_dismiss_feedback_notice' );

		update_option( self::HIDE_NOTICE_OPT, true );

		wp_send_json_success();
	}

	/**
	 * Print JS for close admin notice
	 *
	 * @return void
	 */
	public function printDismissJS() {
		if ( ! $this->allowDisplay() ) {
			return;
		}
		?>
		<script>
			(function ($) {

				$(document).on('click', '.dgwt-review-notice-dismiss', function () {
					var $box = $(this).closest('.dgwt-wcas-review-notice'),
						isLink = $(this).attr('data-link') === 'follow' ? true : false;

					$box.fadeOut(700);

					$.ajax({
						url: ajaxurl,
						data: {
							_wpnonce: '<?php echo wp_create_nonce( 'dgwt_wcas_dismiss_feedback_notice' ); ?>',
							action: '<?php echo self::DISMISS_AJAX_ACTION; ?>',
						}
					}).done(function (data) {

						setTimeout(function () {
							$box.remove();
						}, 700);

					});

					if (!isLink) {
						return false;
					}
				});

			}(jQuery));
		</script>

		<?php
	}

	/**
	 * Load the necessary CSS
	 *
	 * @return void
	 */
	public function loadStyle() {
		if ( $this->allowDisplay() ) {
			wp_enqueue_style( 'dgwt-wcas-admin-style' );
		}
	}
}
