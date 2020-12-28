<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! trait_exists( 'WPPL_Hooks_Impl' ) ) {
	trait WPPL_Hooks_Impl {
		/**
		 * A bit more concise, method-chaining-availble add_action alternative.
		 *
		 * @param string          $tag           Action name.
		 * @param string|callable $callback      Function for callback, or instance method name.
		 * @param int             $accepted_args Number of accepted argument.
		 *
		 * @return mixed
		 */
		public function action( string $tag, $callback, int $accepted_args = 1 ) {
			add_action( $tag, $this->__parse_callback( $callback ), WPPL_PRIORITY, $accepted_args );

			return $this;
		}

		/**
		 * Method-chaining-available add_filter alternative.
		 *
		 * @param string          $tag           Filter name.
		 * @param string|callable $callback      Function for callback, or instance method name.
		 * @param int             $accepted_args Number of accepted argument.
		 *
		 * @return mixed
		 */
		public function filter( string $tag, $callback, int $accepted_args = 1 ) {
			add_filter( $tag, $this->__parse_callback( $callback ), WPPL_PRIORITY, $accepted_args );

			return $this;
		}

		/**
		 * Method-chaining-available add_shortcode alternative.
		 *
		 * @param string          $shortcode
		 * @param string|callable $callback
		 *
		 * @return mixed
		 */
		public function shortcode( string $shortcode, $callback ) {
			add_shortcode( $shortcode, $this->__parse_callback( $callback ) );

			return $this;
		}

		/**
		 * Register each module's activation callback handlers.
		 *
		 * @param string|callable $callback
		 *
		 * @return mixed
		 */
		public function activation_handler( $callback ) {
			add_action( 'WPPL_activation', $this->__parse_callback( $callback ), WPPL_PRIORITY );

			return $this;
		}

		/**
		 *  Register each module's deactivation callback handlers.
		 *
		 * @param string|callable $callback
		 *
		 * @return mixed
		 */
		public function deactivation_handler( $callback ) {
			add_action( 'WPPL_deactivation', $this->__parse_callback( $callback ), WPPL_PRIORITY );

			return $this;
		}

		/**
		 * Interpret input as callback method, if possible.
		 *
		 * @param $callback
		 *
		 * @return callable|array
		 */
		private function __parse_callback( $callback ) {
			if ( is_callable( $callback ) ) {
				return $callback;
			} elseif ( is_string( $callback ) && method_exists( $this, $callback ) ) {
				return [ $this, $callback ];
			} else {
				return $callback;
			}
		}
	}
}
