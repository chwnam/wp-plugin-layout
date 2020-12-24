<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Class WPPL_Block
 *
 * @property-read string         $editor_script
 * @property-read callable|false $render_callback
 */
class WPPL_Block implements WPPL_Object {
	public $block_type;

	public $args;

	public function __construct( string $block_type, array $args = [] ) {
		$this->block_type = $block_type;
		$this->args       = $args;
	}

	public function __get( $name ) {
		return $this->args[ $name ] ?? null;
	}

	public function register() {
		register_block_type( $this->block_type, $this->args );
	}

	public function unregister() {
		unregister_block_type( $this->block_type );
	}
}
