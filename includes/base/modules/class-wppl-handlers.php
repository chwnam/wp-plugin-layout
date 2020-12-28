<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! class_exists( 'WPPL_Handlers' ) ) {
	class WPPL_Handlers implements WPPL_Module {
		use WPPL_Submodule_Impl;

		public function init_module() {
			$this->init_submodules( wppl_get_submodules( 'handler' ) );
		}
	}
}
