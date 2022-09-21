<?php

namespace DgoraWcas\Integrations\Plugins;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PluginsCompatibility {

	public function __construct() {
		$this->loadCompatibilities();
	}

	/**
	 * Load class with compatibilities logic for current theme
	 *
	 * @return void
	 */
	private function loadCompatibilities() {

		$directories = glob( DGWT_WCAS_DIR . 'includes/Integrations/Plugins/*', GLOB_ONLYDIR );

		$directories = apply_filters('dgwt/wcas/plugins_compatibility/directories', $directories);

		if ( ! empty( $directories ) && is_array( $directories ) ) {
			foreach ( $directories as $dir ) {
				$name     = str_replace( DGWT_WCAS_DIR . 'includes/Integrations/Plugins/', '', $dir );
				$filename = $name . '.php';

				$file  = $dir . '/' . $filename;
				$class = '\\DgoraWcas\\Integrations\\Plugins\\' . $name . "\\" . $name;

				if ( file_exists( $file ) && class_exists( $class ) ) {
					$tmp = new $class;
					$tmp->init();
				}
			}
		}

	}
}
