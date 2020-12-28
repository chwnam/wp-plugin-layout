<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! interface_exists( 'WPPL_Container_Interface' ) ) {
	interface WPPL_Container_Interface {
		public static function get_instance();

		public function get_main_file(): string;

		public function get_version(): string;

		public function get_plugin_name(): string;

		public function get_plugin_slug(): string;

		public function get( string $name, $default = null );

		public function set( string $name, $value );
	}
}
