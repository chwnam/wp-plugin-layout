<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! class_exists( 'WPPL_Handlers' ) ) {
	/**
	 * Class WPPL_Handlers
	 *
	 * @property-read WPPL_Ajax_Handler      $ajax
	 * @property-read WPPL_Block_Handler     $block
	 * @property-read WPPL_Meta_Handler      $meta
	 * @property-read WPPL_Cron_Handler      $cron
	 * @property-read WPPL_Script_Handler    $script
	 * @property-read WPPL_Shortcode_Handler $shortcode
	 * @property-read WPPL_Style_Handler     $style
	 */
	class WPPL_Handlers implements WPPL_Module {
		use WPPL_Submodule_Impl;

		public function init_module() {
			$this->init_submodules(
				[
					'ajax'      => new WPPL_Ajax_Handler(),
					'block'     => new WPPL_Block_Handler(),
					'cron'      => new WPPL_Cron_Handler(),
					'meta'      => new WPPL_Meta_Handler(),
					'option'    => new WPPL_Option_Handler(),
					'script'    => new WPPL_Script_Handler(),
					'shortcode' => new WPPL_Shortcode_Handler(),
					'style'     => new WPPL_Style_Handler(),
				]
			);
		}
	}
}