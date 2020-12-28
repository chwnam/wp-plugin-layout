<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! class_exists( 'WPPL_Shortcode' ) ) {
	class WPPL_Shortcode implements WPPL_Object {
		/** @var string shortcode */
		public string $shortcode;

		/** @var string|callable $callback */
		public $callback;

		/**
		 * Shortcode content callback
		 *
		 * If the post content has this shortcode, this method is called before theme templating is engaged.
		 * Callback's params
		 * - string $shortcode
		 *
		 * @var false|callable
		 */
		public $content_check;

		public function __construct( string $shortcode, $callback, $content_check = false ) {
			$this->shortcode     = $shortcode;
			$this->callback      = $callback;
			$this->content_check = $content_check;
		}

		public function register() {
			add_shortcode( $this->shortcode, $this->parse_callback() );
		}

		public function unregister() {
			remove_shortcode( $this->shortcode );
		}

		private function parse_callback() {
			return wppl_parse_callback( $this->callback );
		}
	}
}