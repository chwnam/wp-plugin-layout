<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! interface_exists( 'WPPL_Object' ) ) {
	interface WPPL_Object {
		public function register();

		public function unregister();
	}
}
