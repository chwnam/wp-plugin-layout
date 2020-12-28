<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! class_exists( 'WPPL_Cron_Handler' ) ) {
	/**
	 * Class Anime_Cron_Handler
	 */
	class WPPL_Cron_Handler implements WPPL_Module, WPPL_Object_Handler {
		use WPPL_Hooks_Impl;

		public function init_module() {
			/**
			 * @uses WPPL_Cron_Handler::activation_setup()
			 * @uses WPPL_Cron_Handler::deactivation_cleanup()
			 * @uses WPPL_Cron_Handler::add_()
			 */
			$this
				->action( 'anime_activation', 'activation_setup' )
				->action( 'anime_deactivation', 'deactivation_cleanup' );
		}

		public function register_objects(): void {
			foreach ( $this->get_objects() as $object ) {
				if ( $object instanceof WPPL_Cron ) {
					$object->register();
				}
			}
		}

		public function unregister_objects(): void {
			foreach ( $this->get_objects() as $object ) {
				if ( $object instanceof WPPL_Cron ) {
					$object->unregister();
				}
			}
		}

		/**
		 * @return WPPL_Cron[]
		 */
		public function get_objects(): array {
			return apply_filters( 'wppl_cron_objects', wppl_get_objects( 'cron' ) );
		}

		/**
		 * Activation callback.
		 */
		public function activation_setup() {
			$this->register_objects();
		}

		/**
		 * Deactivation callback.
		 */
		public function deactivation_cleanup() {
			$this->unregister_objects();
		}
	}
}
