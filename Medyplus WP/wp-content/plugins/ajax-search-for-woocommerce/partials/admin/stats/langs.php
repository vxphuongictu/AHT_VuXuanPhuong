<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( $vars['multilingual']['is-multilingual'] ): ?>
	<div class="dgwt-wcas-analytics-langs">
		<h3><?php _e( 'Language', 'ajax-search-for-woocommerce' ); ?></h3>
		<p class="dgwt-wcas-analytics-subtitle"><?php _e( 'Shows stats only for the selected language.', 'ajax-search-for-woocommerce' ); ?></p>
		<select class="js-dgwt-wcas-analytics-lang">
			<?php foreach ( $vars['multilingual']['langs'] as $lang => $label ): ?>
				<option value="<?php echo esc_html( $lang ); ?>" <?php selected( $vars['multilingual']['current-lang'], $lang, true ); ?>><?php echo esc_html( $label ); ?></option>
			<?php endforeach; ?>
		</select>
	</div>
<?php endif; ?>
