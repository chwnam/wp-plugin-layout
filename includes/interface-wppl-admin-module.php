<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! interface_exists( 'WPPL_Admin_Module' ) ) {
	interface WPPL_Admin_Module extends WPPL_Module {
	}
}