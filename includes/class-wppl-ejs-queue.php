<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! class_exists( 'WPPL_EJS_Queue' ) ) {
	/**
	 * Class WPPL_EJS_Queue
	 */
	class WPPL_EJS_Queue {
		private array $queue = [];

		public function __construct() {
			if ( ! has_action( 'wp_print_footer_scripts', [ $this, 'do_template' ] ) ) {
				add_action( 'wp_print_footer_scripts', [ $this, 'do_template' ] );
			}
		}

		public function enqueue( string $relpath, array $context = [] ): void {
			$this->queue[ $relpath ] = $context;
		}

		public function do_template() {
			foreach ( $this->queue as $relpath => $context ) {
				$tmpl_id = 'tmpl-' . pathinfo( wp_basename( $relpath ), PATHINFO_FILENAME );
				$content = wppl_render_file( wppl_locate_file( $relpath, 'templates' ), $context, false );

				echo "\n<script type='text/html' id='" . esc_attr( $tmpl_id ) . "'>\n";
				echo trim( preg_replace( '/\s+/', ' ', $content ) );
				echo "\n</script>\n";
			}
		}
	}
}
