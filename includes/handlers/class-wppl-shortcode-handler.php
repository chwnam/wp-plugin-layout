<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! class_exists( 'WPPL_Shortcode_Handler' ) ) {
	class WPPL_Shortcode_Handler implements WPPL_Module, WPPL_Object_Handler {
		use WPPL_Hooks_Impl;

		/**
		 * Shortcodes to check if the post content has it.
		 *
		 * @var array<string,false|string|callable>
		 */
		private array $content_checks = [];

		/**
		 * Module initialization.
		 *
		 * @uses WPPL_Shortcode_Handler::add_shortcodes()
		 * @uses WPPL_Shortcode_Handler::check_shortcode_in_content()
		 */
		public function init_module() {
			$this
				->action( 'init', 'register_objects' )
				->action( 'wp', 'check_shortcode_in_content' );
		}

		public function register_objects(): void {
			foreach ( $this->get_objects() as $object ) {
				if ( $object instanceof WPPL_Shortcode ) {
					$object->register();
					if ( $object->content_check ) {
						$this->content_checks[] = [ $object->shortcode, $object->content_check ];
					}
				}
			}
		}

		public function unregister_objects(): void {
			foreach ( $this->get_objects() as $object ) {
				if ( $object instanceof WPPL_Shortcode ) {
					$object->unregister();
					if ( $object->content_check ) {
						$pos = array_search(
							[ $object->shortcode, $object->content_check ],
							$this->content_checks,
							true
						);
						if ( false !== $pos ) {
							array_splice( $this->content_checks, $pos, 1 );
						}
					}
				}
			}
		}

		public function check_shortcode_in_content() {
			global $post;

			if ( is_singular() && is_a( $post, 'WP_Post' ) && $this->content_checks ) {
				foreach ( $this->content_checks as [$shortcode, $content_check] ) {
					if ( has_shortcode( $post->post_content, $shortcode ) ) {
						$callback = wppl_parse_callback( $content_check );
						if ( $callback ) {
							call_user_func( $callback, $shortcode );
						}
					}
				}
			}
		}

		/**
		 * Return array of WPPL_Shortcode
		 *
		 * @return WPPL_Shortcode[]
		 */
		public function get_objects(): array {
			$objects = [
			];

			return apply_filters( 'wppl_shortcode_objects', $objects );
		}
	}
}
