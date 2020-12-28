<?php
/**
 * WPPL functions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! function_exists( 'wppl' ) ) {
	/**
	 * Return plugin container.
	 *
	 * @return WPPL_Container
	 */
	function wppl(): WPPL_Container {
		return WPPL_Container::get_instance();
	}
}


if ( ! function_exists( 'wppl_locate_file' ) ) {
	/**
	 * Locate file and return absolute path.
	 *
	 * Search path priority:
	 * - <child_theme>/{slug}/$subdir/$relpath
	 * - <parent_theme>/slug}/$subdir/$relpath
	 * - <plugin>/$subdir/$relpath
	 *
	 * @param string $relpath relative path
	 * @param string $subdir  subdir
	 *
	 * @return false|string
	 */
	function wppl_locate_file( string $relpath, string $subdir ) {
		static $cached = [];

		$relpath    = trim( $relpath, '/\\' );
		$subdir     = trim( $subdir, '/\\' );
		$cache_name = "{$subdir}/{$relpath}";
		$slug       = wppl()->get_plugin_slug();

		if ( ! isset( $cached[ $cache_name ] ) ) {
			$paths = [
				STYLESHEETPATH . "/{$slug}/{$subdir}/{$relpath}",
				TEMPLATEPATH . "/{$slug}/{$subdir}/{$relpath}",
				plugin_dir_path( wppl()->get_main_file() ) . "{$subdir}/$relpath",
			];

			$paths   = apply_filters( 'wppl_locate_file_paths', $paths );
			$located = false;

			foreach ( (array) $paths as $path ) {
				if ( file_exists( $path ) && is_readable( $path ) ) {
					$located = $path;
					break;
				}
			}

			$cached[ $cache_name ] = $located;
		}

		return $cached[ $cache_name ];
	}
}


if ( ! function_exists( 'wppl_render_file' ) ) {
	/**
	 * Include a PHP file, and render into HTML output.
	 *
	 * @param string $file_name
	 * @param array  $context
	 * @param bool   $echo
	 *
	 * @return string
	 */
	function wppl_render_file( string $file_name, array $context = [], bool $echo = true ): string {
		if ( ! $file_name ) {
			return '';
		}

		if ( ! empty( $context ) ) {
			extract( $context, EXTR_SKIP );
		}

		if ( ! $echo ) {
			ob_start();
		}

		/** @noinspection PhpIncludeInspection */
		include $file_name;

		return $echo ? '' : ob_get_clean();
	}
}


if ( ! function_exists( 'wppl_enqueue_ejs' ) ) {
	/**
	 * Enqueue an EJS file.
	 *
	 * @param string $relpath
	 * @param array  $context
	 */
	function wppl_enqueue_ejs( string $relpath, array $context = [] ): void {
		$ejs_enqueue = wppl()->get( 'ejs_enqueue' );

		if ( ! $ejs_enqueue ) {
			$ejs_enqueue = new WPPL_EJS_Queue();
			wppl()->set( 'ejs_enqueue', $ejs_enqueue );
		}

		$ejs_enqueue->enqueue( $relpath, $context );
	}
}


if ( ! function_exists( 'wppl_template' ) ) {
	/**
	 * Do templating.
	 *
	 * @param string $relpath
	 * @param array  $context
	 * @param bool   $echo
	 *
	 * @return string
	 */
	function wppl_template( string $relpath, array $context = [], bool $echo = true ): string {
		return wppl_render_file( wppl_locate_file( $relpath, 'templates' ), $context, $echo );
	}
}


if ( ! function_exists( 'wppl_is_front_request' ) ) {
	/**
	 * @return bool
	 */
	function wppl_is_front_request(): bool {
		return (
			! wp_doing_ajax() &&
			! wp_doing_cron() &&
			! ( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST ) &&
			! ( defined( 'REST_REQUEST' ) && REST_REQUEST ) &&
			! is_admin()
		);
	}
}

if ( ! function_exists( 'wppl_parse_callback' ) ) {
	/**
	 * Parse callback handler.
	 *
	 * 1. Callable - use as-is.
	 * 2. String and not callable, including '@' - parse.
	 *
	 * @sample "foo@bar"       // [wppl()->foo, 'bar']
	 * @sample "admin.foo@bar" // [wppl()->admin->foo, 'bar']
	 *
	 * @param string|callable $maybe_callback
	 *
	 * @return callable|false
	 */
	function wppl_parse_callback( $maybe_callback ) {
		static $cache = [];

		if ( is_callable( $maybe_callback ) ) {
			return $maybe_callback;
		} elseif ( is_string( $maybe_callback ) && false !== strpos( $maybe_callback, '@' ) ) {
			[ $module_part, $method ] = explode( '@', $maybe_callback, 2 );

			if ( isset( $cache[ $module_part ] ) ) {
				$callback = [ $cache[ $module_part ], $method ];
			} else {
				$module = wppl();
				foreach ( explode( '.', $module_part ) as $crumb ) {
					if ( isset( $module->{$crumb} ) ) {
						$module = $module->{$crumb};
					} else {
						$module = false;
						break;
					}
				}
				$cache[ $module_part ] = $module;

				if ( $module && method_exists( $module, $method ) ) {
					$callback = [ $module, $method ];
				} else {
					$callback = false;
				}
			}

			return $callback;
		}

		return false;
	}
}
