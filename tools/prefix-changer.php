<?php

if ( 'cli' !== php_sapi_name() ) {
	exit;
}

require_once __DIR__ . '/tools.php';

$sep           = DIRECTORY_SEPARATOR;
$current_file  = __FILE__;
$root_path     = dirname( dirname( $current_file ) );
$root_path_len = strlen( $root_path );
$touch_file    = $root_path . $sep . '.wppl-prefix-changed';

// touch file check.
if ( file_exists( $touch_file ) ) {
	echo color_text( "Prefix alredy has changed. If it is not, remove '.wppl-prefix-changed' file and try again.\n",
	                 'yellow', '', 'bold' );
	exit;
}

// input prefix
do {
	$prefix = readline( 'Enter prefix string: ' );
	$match  = preg_match( '/^[A-Za-z0-9]+$/', $prefix );
	if ( ! $match ) {
		echo color_text( "Error! Invalid prefix. Prefix should contain A-Z, a-z, 0-9 only.\n", 'red' );
	}
	if ( 'wppl' === strtolower( $prefix ) ) {
		echo color_text( "Error! prefix 'wppl' is not allowed. Chooose another prefix.\n", 'red' );
		$match = 0;
	}
} while ( ! $match );

do {
	echo color_text( sprintf( 'Replace all prefixe with \'%s\', and \'%s\'. Are you sure? [y, n] ', $prefix,
	                          strtolower( $prefix ) ), 'red', '', 'bold' );
	$answer = trim( strtolower( readline() ) );
	if ( 'n' == $answer ) {
		exit;
	}
} while ( $answer !== 'y' );

$targets = [
	'/includes/base',
	'/includes/submodules',
	'/wp-plugin-layout.php',
];

foreach ( $targets as $target ) {
	$path = $root_path . $target;
	if ( is_dir( $path ) ) {
		$iterator = new RegexIterator(
			new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $path ) ),
			'/\.(php|js|css|map)$/i',
			RecursiveRegexIterator::MATCH
		);
		foreach ( $iterator as $it ) {
			/** @var SplFileInfo $it */
			file_replacement( $it->getPathname(), $prefix );
		}
	} elseif ( is_file( $path ) ) {
		file_replacement( $path, $prefix );
	}
}

rename( $root_path . $sep . 'wp-plugin-layout.php', $root_path . $sep . strtolower( $prefix ) . '.php' );

// finish.
touch( $touch_file );

echo color_text( "Successfully changed! Don\'t forget to refresh autoload.php!\n", 'light green', '', 'bold' );
