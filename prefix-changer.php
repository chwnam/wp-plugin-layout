<?php

if ( 'cli' !== php_sapi_name() ) {
	exit;
}


function pfc_color_text( string $text, string $fg, string $bg = '', string $effect = '' ): string {
	if ( ! $text ) {
		return '';
	}

	switch ( $fg ) {
		case 'red':
			$code = '31';
			break;

		case 'green':
			$code = "32";
			break;

		case 'yellow':
			$code = "33";
			break;

		case 'light green':
			$code = "92";
			break;

		default:
			$code = '0';
			break;
	}

	switch ( $effect ) {
		case 'bold':
			$code .= ';1';
			break;
	}

	return "\33[{$code}m{$text}\033[0m";
}

$sep           = DIRECTORY_SEPARATOR;
$current_file  = __FILE__;
$root_path     = dirname( $current_file );
$root_path_len = strlen( $root_path );
$touch_file    = $root_path . $sep . '.wppl-prefix-changed';

// touch file check.
if ( file_exists( $touch_file ) ) {
	echo pfc_color_text( "Prefix alredy has changed. If it is not, remove '.wppl-prefix-changed' file and try again.\n",
	                     'yellow', '', 'bold' );
	exit;
}

// input prefix
do {
	$prefix = readline( 'Enter prefix string: ' );
	$match  = preg_match( '/^[A-Za-z0-9]+$/', $prefix );
	if ( ! $match ) {
		echo pfc_color_text( "Error! Invalid prefix. Prefix should contain A-Z, a-z, 0-9 only.\n", 'red' );
	}
	if ( 'wppl' === strtolower( $prefix ) ) {
		echo pfc_color_text( "Error! prefix 'wppl' is not allowed. Chooose another prefix.\n", 'red' );
		$match = 0;
	}
} while ( ! $match );

do {
	echo pfc_color_text( sprintf(
		                     'Replace all prefixe with \'%s\', and \'%s\'. Are you sure? [y, n] ', $prefix,
		                     strtolower( $prefix ) ),
	                     'red', '', 'bold' );
	$answer = trim( strtolower( readline() ) );
	if ( 'n' == $answer ) {
		exit;
	}
} while ( $answer !== 'y' );

$iterator = new RegexIterator(
	new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $root_path ) ),
	'/\.(php|js|css|map)$/i',
	RecursiveRegexIterator::MATCH
);

foreach ( $iterator as $it ) {
	/** @var SplFileInfo $it */
	$full_path = $it->getRealPath();
	$dirname   = $it->getPath();
	$basename  = $it->getBasename();
	$rel_path  = substr( $full_path, $root_path_len );

	if ( ! preg_match( ';^/vendor;', $rel_path ) && $full_path !== $current_file ) {
		$content = file_get_contents( $full_path );
		if ( $content ) {
			$content = str_replace( 'wppl', strtolower( $prefix ), $content );
			$content = str_replace( 'WPPL', $prefix, $content );
			file_put_contents( $full_path, $content );
		}
		$basename_fix = str_replace( 'wppl', strtolower( $prefix ), $basename );
		$new_path     = $dirname . $sep . $basename_fix;
		rename( $full_path, $new_path );
	}
}

rename( __DIR__ . '/wp-plugin-layout.php', __DIR__ . strtolower( $prefix ) . '.php' );

// finish.
touch( $touch_file );

echo pfc_color_text( "Successfully changed!\n", 'light green', '', 'bold' );
