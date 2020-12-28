<?php
/**
 * Option objects
 *
 * NOTE: a non-numeric key is considerd as an alias. You can utilize the alias string.
 * e.g.
 *   'foo' => new WPPL_Option( 'foo_group', 'prefix_foo', [ ... ] );
 *
 * then,
 *   $foo_field = wppl()->handler->option->foo;
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return [
	// new WPPL_Option( 'optin_group', 'option_name', [] ),
];
