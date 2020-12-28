<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! class_exists( 'WPPL_Block_Handler' ) ) {
	class WPPL_Block_Handler implements WPPL_Module, WPPL_Object_Handler {
		use WPPL_Hooks_Impl;

		/**
		 * @uses WPPL_Block_Handler::register_objects()
		 */
		public function init_module() {
			if ( function_exists( 'register_block_type' ) ) {
				$this->action( 'enqueue_block_editor_assets', 'register_objects' );
			}
		}

		public function register_objects(): void {
			foreach ( $this->get_objects() as $object ) {
				if ( $object instanceof WPPL_Block ) {
					$object->register();
				}
			}
		}

		public function unregister_objects(): void {
			foreach ( $this->get_objects() as $object ) {
				if ( $object instanceof WPPL_Block ) {
					$object->unregister();
				}
			}
		}

		/**
		 * @return WPPL_Block[]
		 */
		public function get_objects(): array {
			return apply_filters( 'wppl_block_objects', wppl_get_objects( 'block' ) );
		}
	}
}
