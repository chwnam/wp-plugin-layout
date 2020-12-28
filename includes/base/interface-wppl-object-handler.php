<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! interface_exists( 'WPPL_Object_Handler' ) ) {
	interface WPPL_Object_Handler {
		public function register_objects(): void;

		public function unregister_objects(): void;

		public function get_objects(): array;
	}
}
