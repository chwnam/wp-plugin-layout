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
	$match  = preg_match( '/^[A-Za-z0-9_]+$/', $prefix );
	if ( ! $match ) {
		echo color_text( "Error! Invalid prefix. Prefix should contain A-Z, a-z, 0-9, and underscore only.\n", 'red' );
	}
	if ( false !== strpos( strtolower( $prefix ), 'wppl' ) ) {
		echo color_text( "Error! string 'wppl' is included. Chooose another prefix.\n", 'red' );
		$match = 0;
	}
} while ( ! $match );


$prefix    = trim( $prefix, '_' );
$lowercase = strtolower( $prefix );

do {
	echo color_text( sprintf( 'Replace all prefixe with \'%s\', and \'%s\'. Are you sure? [y, n] ', $prefix,
	                          $lowercase ), 'red', '', 'bold' );
	$answer = trim( strtolower( readline() ) );
	if ( 'n' == $answer ) {
		exit;
	}
} while ( $answer !== 'y' );

$targets = [
	'/assets',
	'/includes/base',
	'/includes/floor',
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

rename( $root_path . $sep . 'wp-plugin-layout.php', $root_path . $sep . $lowercase . '.php' );

// finish.
touch( $touch_file );

echo color_text( "Successfully changed! Don\'t forget to refresh autoload.php!\n", 'light green', '', 'bold' );
