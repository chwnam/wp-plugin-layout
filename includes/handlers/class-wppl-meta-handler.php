<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! class_exists( 'WPPL_Meta_Handler' ) ) {
	/**
	 * Class WPPL_Meta_Handler
	 */
	class WPPL_Meta_Handler implements WPPL_Module, WPPL_Object_Handler {
		use WPPL_Hooks_Impl;

		private array $fields = [];

		public function init_module() {
			$this->action( 'init', 'register_objects' );
		}

		public function register_objects(): void {
			foreach ( $this->get_objects() as $idx => $object ) {
				if ( $object instanceof WPPL_Meta ) {
					$object->register();
					$alias = is_int( $idx ) ? $object->get_key() : $idx;

					$this->fields[ $alias ] = [
						$object->get_object_type(),
						$object->get_key(),
						$object->object_subtype,
					];
				}
			}
		}

		public function unregister_objects(): void {
			foreach ( $this->get_objects() as $idx => $object ) {
				if ( $object instanceof WPPL_Meta ) {
					$object->unregister();
					$alias = is_int( $idx ) ? $object->get_key() : $idx;
					unset( $this->fields[ $alias ] );
				}
			}
		}

		/**
		 * Get meta objects.
		 *
		 * @return WPPL_Meta[]
		 */
		public function get_objects(): array {
			$objects = [
				// NOTE: a non-numeric key is considerd as an alias. You can utilize the alias string.
				//
				// e.g.
				// 'foo' => new WPPL_Meta( 'post', 'prefix_foo', [ ... ] ),
				//
				// And then,
				// $foo_field = wppl()->handler->meta->foo;
			];

			return apply_filters( 'wppl_meta_objects', $objects );
		}

		/**
		 * @param string $alias
		 *
		 * @return ?WPPL_Meta
		 */
		public function __get( string $alias ): ?WPPL_Meta {
			if ( isset ( $this->fields[ $alias ] ) ) {
				return WPPL_Meta::factory( ...$this->fields[ $alias ] );
			}

			return null;
		}
	}
}
