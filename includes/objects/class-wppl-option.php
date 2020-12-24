<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! class_exists( 'WPPL_Option' ) ) {
	/**
	 * Class WPPL_Option
	 *
	 * @property-read string    $type
	 * @property-read string    $description
	 * @property-read ?callable $sanitize_callback
	 * @property-read bool      $show_in_rest
	 * @property-read mixed     $default
	 * @property-read bool      $autoload
	 */
	class WPPL_Option implements WPPL_Object {
		private static array $objects = [];

		private string $option_group;

		private string $option_name;

		private array $args;

		public static function factory( string $option_group, string $option_name ): ?WPPL_Option {
			global $wp_registered_setting;

			if ( isset( $wp_registered_setting[ $option_name ] ) ) {
				$args = &$wp_registered_setting[ $option_name ];

				if ( ! isset ( static::$objects[ $option_name ] ) ) {
					static::$objects[ $option_name ] = new WPPL_Option( $option_group, $option_name, $args );
				}

				return static::$objects[ $option_name ];
			}

			return null;
		}

		/**
		 * WPPL_Option constructor.
		 *
		 * @param string $option_group
		 * @param string $option_name
		 * @param array  $args
		 */
		public function __construct( string $option_group, string $option_name, array $args = [] ) {
			$this->option_group = $option_group;
			$this->option_name  = $option_name;
			$this->args         = $args;
		}

		/**
		 * @see register_setting()
		 */
		public function register() {
			if ( $this->option_group && $this->option_name ) {
				register_setting( $this->option_group, $this->option_name, $this->args );
			}
		}

		public function unregister() {
			if ( $this->option_group && $this->option_name ) {
				unregister_setting( $this->option_group, $this->option_name );
			}
		}

		/**
		 * Get each register_setting() argument by name.
		 *
		 * @param string $name
		 *
		 * @return mixed
		 *
		 * @see register_setting()
		 */
		public function __get( string $name ) {
			switch ( $name ) {
				case 'type':
					return $this->args['type'] ?? '';

				case 'description':
					return $this->args['description'] ?? '';

				case 'sanitize_callback':
					return $this->args['sanitize_callback'] ?? null;

				case 'show_in_rest':
					return $this->args['show_in_rest'] ?? false;

				case 'default':
					return $this->args['default'] ?? false;

				case 'autoload':
					return $this->args['autoload'] ?? true;

				default:
					return $this->args[ $name ] ?? null;
			}
		}

		/**
		 * Get option group.
		 *
		 * @return string
		 */
		public function get_option_group(): string {
			return $this->option_group;
		}

		/**
		 * Get option name.
		 *
		 * @return string
		 */
		public function get_option_name(): string {
			return $this->option_name;
		}

		/**
		 * @return mixed
		 */
		public function get_value() {
			return get_option( $this->option_name, $this->default );
		}

		/**
		 * @param mixed $value
		 *
		 * @return bool
		 */
		public function update( $value ): bool {
			return update_option( $this->option_name, $value, $this->autoload );
		}

		public function update_from_request(): bool {
			if ( isset( $_REQUEST[ $this->option_name ] ) && is_callable( $this->sanitize_callback ) ) {
				return $this->update( $_REQUEST[ $this->option_name ] );
			} else {
				return false;
			}
		}

		/**
		 * @return bool
		 */
		public function delete(): bool {
			return delete_option( $this->option_name );
		}
	}
}
