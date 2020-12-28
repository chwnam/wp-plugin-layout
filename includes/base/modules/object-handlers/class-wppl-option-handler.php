<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! class_exists( 'WPPL_Option_Handler' ) ) {
	class WPPL_Option_Handler implements WPPL_Module, WPPL_Object_Handler {
		use WPPL_Hooks_Impl;

		private array $fields = [];

		public function init_module() {
			$this->action( 'init', 'register_objects' );
		}

		public function register_objects(): void {
			foreach ( $this->get_objects() as $idx => $object ) {
				if ( $object instanceof WPPL_Option ) {
					$object->register();
					$alias = is_int( $idx ) ? $object->get_option_name() : $idx;

					$this->fields[ $alias ] = [ $object->get_option_group(), $object->get_option_name() ];
				}
			}
		}

		public function unregister_objects(): void {
			foreach ( $this->get_objects() as $object ) {
				if ( $object instanceof WPPL_Option ) {
					$object->unregister();
				}
			}
		}

		/**
		 * Get option objects.
		 *
		 * @return WPPL_Option[]
		 */
		public function get_objects(): array {
			return apply_filters( 'wppl_option_objects', wppl_get_objects( 'option' ) );
		}

		/**
		 * Get WPPL_Option instance by alias
		 *
		 * @return ?WPPL_Option
		 */
		public function __get( string $alias ): ?WPPL_Option {
			if ( isset( $this->fields[ $alias ] ) ) {
				return WPPL_OPtion::factory( ...$this->fields[ $alias ] );
			}

			return null;
		}
	}
}
