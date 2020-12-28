<?php
/**
 * Meta objects
 *
 * NOTE: a non-numeric key is considerd as an alias. You can utilize the alias string.
 * e.g.
 * 'foo' => new WPPL_Meta( 'post', 'prefix_foo', [ ... ] ),
 *
 * And then,
 * $foo_field = wppl()->handler->meta->foo;
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return [
	// 'key' => new WPPL_Meta( 'post', 'meta_key', [] )
];
