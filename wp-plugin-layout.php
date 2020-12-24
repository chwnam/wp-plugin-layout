<?php
/**
 * Plugin Name:       WP Plugin Layout
 * Plugin URI:        https://github.com/chwnam/wp-plugin-layout
 * Version:           1.0.0-alpha.2
 * Description:       Boilerplate code for WordPress plugin development.
 * Author:            Changwoo Nam
 * Author URI:        https://blog.changwoo.pe.kr/
 * Text Domain:       wppl
 * Domain Path:       languages/
 * Network:           false
 * Requires at least:
 * Requires PHP:      7.4
 * License:           GPLv2 or later
 * License URI:       https://github.com/chwnam/wp-plugin-layout/blob/main/LICENSE
 */

require_once __DIR__ . '/vendor/autoload.php';

define( 'WPPL_MAIN', __FILE__ );
define( 'WPPL_VERSION', '1.0.0-alpha.2' );
define( 'WPPL_PRIORITY', '215' );
define( 'WPPL_NAME', 'WP Plugin Layout' );
define( 'WPPL_SLUG', 'wppl' );

// define( 'WPPL_WOOCOMMERCE_REQUIRED', true );

wppl();
