<?php
/**
 * Loader for the ThemeIsle\GutenbergMenuIcons
 *
 * @package     ThemeIsle\GutenbergMenuIcons
 * @copyright   Copyright (c) 2020, Hardeep Asrani
 * @license     http://opensource.org/licenses/gpl-3.0.php GNU Public License
 * @since       1.0.0
 */

define( 'THEMEISLE_GUTENBERG_MENU_ICONS_VERSION', '1.0.4' );
define( 'THEMEISLE_GUTENBERG_MENU_ICONS_DEV', false );

if ( function_exists( 'add_action' ) ) {
	add_action(
		'plugins_loaded',
		function () {
			// call this only if Gutenberg is active.
			if ( function_exists( 'register_block_type' ) ) {
				require_once dirname( __FILE__ ) . '/class-gutenberg-menu-icons.php';
			}
		}
	);
}
