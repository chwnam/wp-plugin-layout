<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! trait_exists( 'WPPL_Submodule_Impl' ) ) {
	trait WPPL_Submodule_Impl {
		/** @var WPPL_Module[] */
		private array $modules = [];

		public function __get( string $name ): ?WPPL_Module {
			return $this->modules[ $name ] ?? null;
		}

		public function __set( string $name, $value ) {
			throw new BadMethodCallException( __( 'WPPL module does not allow submodule from outside.', 'wppl' ) );
		}

		public function __isset( string $name ): bool {
			return isset( $this->modules[ $name ] );
		}

		public function __unset( string $name ): void {
			throw new BadMethodCallException( __( 'WPPL module does not allow submodule removal.', 'wppl' ) );
		}

		public function init_submodules( array $submodules ): void {
			$this->modules = $submodules;

			foreach ( $this->modules as $module ) {
				if ( $module instanceof WPPL_Module ) {
					$module->init_module();
				}
			}
		}
	}
}
