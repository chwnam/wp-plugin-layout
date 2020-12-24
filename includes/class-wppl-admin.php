<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! class_exists( 'WPPL_Admin' ) ) {
	/**
	 * Class WPPL_Admin
	 */
	class WPPL_Admin implements WPPL_Module {
		use WPPL_Submodule_Impl;

		public function init_module() {
			$this->init_submodules(
				[
					// Define admin submodules here.
				]
			);
		}
	}
}
