<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! class_exists( 'WPPL_Script_Handler' ) ) {
	class WPPL_Script_Handler implements WPPL_Module, WPPL_Object_Handler {
		use WPPL_Hooks_Impl;

		private string $asset_url;

		private string $asset_path;

		private string $asset_ver;

		private bool $is_debug;

		public function init_module() {
			$this->asset_url  = plugin_dir_url( wppl()->get_main_file() ) . 'assets/js/';
			$this->asset_path = plugin_dir_path( wppl()->get_main_file() ) . 'assets/js/';
			$this->asset_ver  = wppl()->get_version();
			$this->is_debug   = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG;

			$this->action( 'init', 'register_objects' );
		}

		public function register_objects(): void {
			foreach ( $this->get_objects() as $object ) {
				if ( $object instanceof WPPL_Script ) {
					$object->register();
				}
			}
		}

		public function unregister_objects(): void {
			foreach ( $this->get_objects() as $object ) {
				if ( $object instanceof WPPL_Script ) {
					$object->unregister();
				}
			}
		}

		/**
		 * @return WPPL_Script[]
		 */
		public function get_objects(): array {
			return array_merge(
				$this->get_global_scripts(),
				is_admin() ? $this->get_admin_scripts() : $this->get_front_scripts(),
			);
		}

		public function url_helper( string $relpath ): string {
			$relpath = trim( $relpath, '/\\' );

			if ( $this->is_debug ) {
				$relpath = preg_replace( '/\.min\.js$/', '.js', $relpath );
			}

			return "{$this->asset_url}{$relpath}";
		}

		private function get_global_scripts(): array {
			return apply_filters( 'wppl_global_scripts', wppl_get_objects( 'global-script', $this->get_context() ) );
		}

		private function get_admin_scripts(): array {
			return apply_filters( 'get_admin_scripts', wppl_get_objects( 'admin-script', $this->get_context() ) );
		}

		private function get_front_scripts(): array {
			return apply_filters( 'get_front_scripts', wppl_get_objects( 'front-script', $this->get_context() ) );
		}

		private function get_context(): array {
			return [
				'asset_url'  => $this->asset_url,
				'asset_path' => $this->asset_path,
				'asset_ver'  => $this->asset_ver,
				'url_helper' => [ $this, 'url_helper' ],
			];
		}
	}
}
