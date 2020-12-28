<?php
/**
 * Module root submodules
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return [
	'admin'   => new WPPL_Admin(),
	'handler' => new WPPL_Handlers(),
	'setting' => new WPPL_Settings(),
];
