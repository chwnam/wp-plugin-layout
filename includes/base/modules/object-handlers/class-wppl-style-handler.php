<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! class_exists( 'WPPL_Style_Handler' ) ) {
	class WPPL_Style_Handler implements WPPL_Module, WPPL_Object_Handler {
		use WPPL_Hooks_Impl;

		private string $asset_url;

		private string $asset_path;

		private string $asset_ver;

		private bool $is_debug;

		public function init_module() {
			$this->asset_url  = plugin_dir_url( wppl()->get_main_file() ) . 'assets/css/';
			$this->asset_path = plugin_dir_path( wppl()->get_main_file() ) . 'asset/css/';
			$this->asset_ver  = wppl()->get_version();
			$this->is_debug   = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG;

			$this->action( 'init', 'register_objects' );
		}

		public function register_objects(): void {
			foreach ( $this->get_objects() as $object ) {
				if ( $object instanceof WPPL_Style ) {
					$object->register();
				}
			}
		}

		public function unregister_objects(): void {
			foreach ( $this->get_objects() as $object ) {
				if ( $object instanceof WPPL_Style ) {
					$object->unregister();
				}
			}
		}

		/**
		 * @return WPPL_Style[]
		 */
		public function get_objects(): array {
			return array_merge(
				$this->get_global_styles(),
				is_admin() ? $this->get_admin_styles() : $this->get_front_styles(),
			);
		}

		public function url_helper( string $relpath ): string {
			$relpath = trim( $relpath, '/\\' );

			if ( $this->is_debug ) {
				$relpath = preg_replace( '/\.min\.css$/', '.css', $relpath );
			}

			return "{$this->asset_url}{$relpath}";
		}

		private function get_global_styles(): array {
			return apply_filters( 'get_global_styles', wppl_get_objects( 'global-style', $this->get_context() ) );
		}

		private function get_admin_styles(): array {
			return apply_filters( 'get_admin_styles', wppl_get_objects( 'admin-style', $this->get_context() ) );
		}

		private function get_front_styles(): array {
			return apply_filters( 'get_front_styles', wppl_get_objects( 'front-style', $this->get_context() ) );
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
