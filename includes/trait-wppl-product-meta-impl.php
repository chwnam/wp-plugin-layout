<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! trait_exists( 'WPPL_Product_Meta_Impl' ) ) {
	trait WPPL_Product_Meta_Impl {
		private array $fields = [];

		public function __get( string $name ) {
			if ( ! empty( $this->fields[ $name ] ) ) {
				return WPPL_Meta::factory( $this->fields[ $name ], 'post', 'product' );
			} else {
				throw new BadMethodCallException(
					sprintf(
					// translators: module name, and class name.
						__( 'Field [%s] does not exist in %s.', 'wppl' ), $name, get_called_class() )
				);
			}
		}

		/**
		 * Too often meta key is too long and is hard to remember.
		 * To access meta field using '->' operator, keep meta keys in private array.
		 *
		 * @param string $name     Field name. An alias.
		 * @param string $meta_key Real meta key string.
		 */
		protected function add_to_fields( string $name, string $meta_key ): void {
			$this->fields[ $name ] = $meta_key;
		}
	}
}
