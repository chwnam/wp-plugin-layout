<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! class_exists( 'WPPL_Container' ) ) {
	/**
	 * Class WPPL_Container
	 *
	 * @property-read WPPL_Admin    $admin
	 * @property-read WPPL_Handlers $handler
	 * @property-read WPPL_Settings $setting
	 */
	final class WPPL_Container implements WPPL_Container_Interface, WPPL_Module {
		use WPPL_Submodule_Impl;
		use WPPL_Hooks_Impl;

		private static ?WPPL_Container $instance = null;

		private array $storage;

		/**
		 * Get the instance.
		 *
		 * @return WPPL_Container
		 */
		public static function get_instance(): WPPL_Container {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Get plugin main file.
		 *
		 * @return string
		 */
		public function get_main_file(): string {
			return WPPL_MAIN;
		}

		/**
		 * Get plugin version.
		 *
		 * @return string
		 */
		public function get_version(): string {
			return WPPL_VERSION;
		}

		/**
		 * Get the plugin's name
		 */
		public function get_plugin_name(): string {
			return WPPL_NAME;
		}

		/**
		 * Get plugin's slug
		 */
		public function get_plugin_slug(): string {
			return WPPL_SLUG;
		}

		/**
		 * Get stored value by name.
		 *
		 * @param string     $name
		 * @param mixed|null $default
		 *
		 * @return mixed|null
		 */
		public function get( string $name, $default = null ) {
			return $this->storage[ $name ] ?? $default;
		}

		/**
		 * Store any value.
		 *
		 * @param string $name
		 * @param mixed  $value
		 *
		 * @return $this
		 */
		public function set( string $name, $value ): WPPL_Container {
			$this->storage[ $name ] = $value;
			return $this;
		}

		/**
		 * Private constructer. The instance must be fetched by get_instance() method.
		 */
		private function __construct() {
			$this->action( 'plugins_loaded', 'init_module' );
			register_activation_hook( $this->get_main_file(), [ $this, 'activation' ] );
			register_deactivation_hook( $this->get_main_file(), [ $this, 'deactivation' ] );
		}

		/**
		 * Initialize current module.
		 */
		public function init_module() {
			// WooCommerce dependency check.
			if ( ( defined( 'WPPL_WOOCOMMERCE_REQUIRED' ) && WPPL_WOOCOMMERCE_REQUIRED ) &&
			     ! defined( 'WP_UNINSTALL_PLUGIN' ) && ! class_exists( 'WooCommerce', false )
			) {
				$this->admin_notice_no_woocommerce();
				return;
			}
			$this->load_textdomain();
			$this->load_submodules();
		}

		/**
		 * Representing plugin's activation callback.
		 */
		public function activation() {
			// Modules should be loaded at first.
			$this->load_submodules();
			do_action( 'wppl_activation' );
		}

		/**
		 * Representing plugin's deactivation callback.
		 */
		public function deactivation() {
			// Modules are already loaded since it is now deactivatiing.
			do_action( 'wppl_deactivation' );
		}

		/**
		 * Prevent serialization and unserializaion.
		 *
		 * @throws BadMethodCallException
		 */
		public function __wakeup() {
			throw new BadMethodCallException(
				sprintf(
				// translators: plugin name and method name.
					__( '%s does not support method %s invocation.', 'wppl' ),
					$this->get_plugin_name(),
					__METHOD__
				)
			);
		}

		/**
		 * Prevent serialization and unserializaion.
		 *
		 * @throws BadMethodCallException
		 */
		public function __sleep() {
			throw new BadMethodCallException(
				sprintf(
				// translators: plugin name and method name.
					__( '%s does not support method %s invocation.', 'wppl' ),
					$this->get_plugin_name(),
					__METHOD__
				)
			);
		}

		/**
		 * Prevent object cloning.
		 *
		 * @throws BadMethodCallException
		 */
		public function __clone() {
			throw new BadMethodCallException(
				sprintf(
				// translators: plugin name and method name.
					__( '%s does not support method %s invocation.', 'wppl' ),
					$this->get_plugin_name(),
					__METHOD__
				)
			);
		}

		/**
		 * Display admin notice when WooCommerce is unavailable.
		 */
		private function admin_notice_no_woocommerce() {
			$this->action( 'admin_notices', function () {
				echo '<div class="notice notice-error"><p>';
				printf(
				/* translators: Woocommerce plugis URL. It is strctly fixed. */
					__( '%s plugin requires Woocommrece. Install and activate <a href="%s">WooCommerce plugin</a>.',
					    'wppl' ),
					$this->get_plugin_name(),
					esc_url( 'https://wordpress.org/plugins/woocommerce/' )
				);
				echo '</p></div>';
			} );
		}

		/**
		 * Load textdomain.
		 */
		private function load_textdomain() {
			load_plugin_textdomain( 'wppl', false, wp_basename( dirname( $this->get_main_file() ) ) . '/languages' );
		}

		/**
		 * Load all submodules.
		 */
		private function load_submodules() {
			$this->init_submodules(
				[
					// Define modules
					'admin'   => new WPPL_Admin(),
					'handler' => new WPPL_Handlers(),
					'setting' => new WPPL_Settings(),
				]
			);

			// Trigger all submodule initialization.
			do_action( 'wppl_module_loaded' );
		}
	}
}
