<?php

namespace DgoraWcas\Integrations\Themes\The7;

use DgoraWcas\Abstracts\ThemeIntegration;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class The7 extends ThemeIntegration {
	public function extraFunctions() {
		add_action( 'wp_head', function () {
			?>
			<style>
				.mini-widgets .dgwt-wcas-search-icon {
					width: 17px;
					margin-top: -2px;
				}

				.mini-widgets .dgwt-wcas-layout-icon-open .dgwt-wcas-search-icon-arrow {
					top: calc(100% + 5px);
				}
			</style>
			<?php
		} );

		add_filter( 'presscore_template_manager_located_template', array( $this, 'changeTemplatePath' ), 10, 2 );
	}

	/**
	 * Change template path
	 *
	 * @param string $templateName
	 * @param array $templateNames
	 */
	public function changeTemplatePath( $templateName, $templateNames ) {
		if ( strpos( $templateName, 'searchform.php' ) !== false ) {
			$templateName = DGWT_WCAS_DIR . 'partials/themes/the7-searchform.php';
		}

		return $templateName;
	}
}
