<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPPL_Settings' ) ) {
	/**
	 * Class WPPL_Settings
	 *
	 */
	class WPPL_Settings implements WPPL_Module {
		use WPPL_Hooks_Impl;

		/**
		 * @uses WPPL_Settings::add_setting_link()
		 */
		public function init_module() {
			$path = wppl()->get_main_file();
			$base = wp_basename( $path );
			$dir  = wp_basename( dirname( $path ) );
			$this->filter( "plugin_action_links_{$dir}/{$base}", 'add_setting_link' );
		}

		/**
		 * Add settings URL action.
		 *
		 * @callback
		 * @filter plugin_action_links_{$plugin_file}
		 *
		 * @param array<string, string> $actions Action links.
		 *
		 * @return array
		 */
		public function add_setting_link( array $actions ): array {
			return array_merge(
				[
					'settings' => '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=wppl' ) . '" '
					              . 'aria-label="' . esc_attr__( 'View WPPL settings', 'wppl' ) . '">'
					              . esc_html__( 'Settings', 'wppl' ) . '</a>',
				],
				$actions
			);
		}
	}
}
