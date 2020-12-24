<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! class_exists( 'WPPL_Ajax' ) ) {
	/**
	 * Class WPPL_Ajax
	 *
	 * AJAX Handler object
	 */
	class WPPL_Ajax implements WPPL_Object {
		/**
		 * Action name.
		 *
		 * @var string
		 */
		public string $action;

		/**
		 * Callback.
		 *
		 * Module callback: {module}@{method_name}
		 * Admin module callback: admin.{module}@{method_name}
		 *
		 * @var callable|string
		 */
		public $callback;

		/**
		 * 'nopriv' action support.
		 *
		 * @var bool|string
		 */
		public $allow_nopriv;

		/**
		 * Register handler for WC-AJAX (WooCommerce), not for WordPress. When it is true, allow_nopriv is ignored.
		 *
		 * @var bool
		 */
		public bool $is_wc_ajax;

		/**
		 * WPPL_Ajax constructor.
		 *
		 * @param string          $action       Action name.
		 * @param callable|string $callback     Follow {module}@{method_name}, admin.{module}@{method_name} notation if it is string.
		 * @param bool|string     $allow_nopriv Add additional action for non-logged in users. Enter 'only_nopriv' for noprive action only.
		 * @param bool            $is_wc_ajax   Is this for wc_ajax?
		 */
		public function __construct( string $action, $callback, $allow_nopriv = false, $is_wc_ajax = false ) {
			$this->action       = $action;
			$this->callback     = $callback;
			$this->allow_nopriv = $allow_nopriv;
			$this->is_wc_ajax   = $is_wc_ajax;
		}

		public function register() {
			$this->parse_callback();
			if ( $this->action && is_callable( $this->callback ) ) {
				if ( $this->is_wc_ajax ) {
					add_action( "wc_ajax_{$this->action}", $this->callback, WPPL_PRIORITY );
				} else {
					if ( 'only_nopriv' !== $this->allow_nopriv ) {
						add_action( "wp_ajax_{$this->action}", $this->callback, WPPL_PRIORITY );
					}
					if ( true === $this->allow_nopriv || 'only_nopriv' === $this->allow_nopriv ) {
						add_action( "wp_ajax_nopriv_{$this->action}", $this->callback, WPPL_PRIORITY );
					}
				}
			}
		}

		public function unregister() {
			$this->parse_callback();
			if ( $this->action && is_callable( $this->callback ) ) {
				if ( $this->is_wc_ajax ) {
					remove_action( "wc_ajax_{$this->action}", $this->callback, WPPL_PRIORITY );
				} else {
					remove_action( "wp_ajax_{$this->action}", $this->callback, WPPL_PRIORITY );
					if ( true === $this->allow_nopriv || 'only_nopriv' === $this->allow_nopriv ) {
						remove_action( "wp_ajax_nopriv_{$this->action}", $this->callback, WPPL_PRIORITY );
					}
				}
			}
		}

		/**
		 * Parse callback handler.
		 *
		 * 1. Callable - use as-is.
		 * 2. String and not callable, including '@' - parse.
		 *
		 * @sample "foo@bar"       // [wppl()->foo, 'bar']
		 * @sample "admin.foo@bar" // [wppl()->admin->foo, 'bar']
		 */
		private function parse_callback() {
			$this->callback = wppl_parse_callback( $this->callback );
		}
	}
}
