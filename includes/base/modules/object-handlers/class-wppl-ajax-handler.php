<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! class_exists( 'WPPL_Ajax_Handler' ) ) {
	/**
	 * Class WPPL_Ajax_Handlers
	 */
	class WPPL_Ajax_Handler implements WPPL_Module, WPPL_Object_Handler {
		use WPPL_Hooks_Impl;

		/**
		 * @uses WPPL_Ajax_Handler::register_all()
		 */
		public function init_module() {
			$this->action( 'init', 'register_objects' );
		}

		public function register_objects(): void {
			foreach ( $this->get_objects() as $ajax ) {
				if ( $ajax instanceof WPPL_Ajax ) {
					$ajax->register();
				}
			}
		}

		public function unregister_objects(): void {
			foreach ( $this->get_objects() as $ajax ) {
				if ( $ajax instanceof WPPL_Ajax ) {
					$ajax->unregister();
				}
			}
		}

		/**
		 * @return WPPL_Ajax[]
		 */
		public function get_objects(): array {
			return apply_filters( 'wppl_ajax_objects', wppl_get_objects( 'ajax' ) );
		}
	}
}
