<?php
/**
 * Module handler's submodules
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return [
	'ajax'      => new WPPL_Ajax_Handler(),
	'block'     => new WPPL_Block_Handler(),
	'cron'      => new WPPL_Cron_Handler(),
	'meta'      => new WPPL_Meta_Handler(),
	'option'    => new WPPL_Option_Handler(),
	'script'    => new WPPL_Script_Handler(),
	'shortcode' => new WPPL_Shortcode_Handler(),
	'style'     => new WPPL_Style_Handler(),
];
