<?php

if ( 'cli' !== php_sapi_name() ) {
	exit;
}


if ( ! function_exists( 'color_text' ) ) {
	function color_text( string $text, string $fg, string $bg = '', string $effect = '' ): string {
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
}


if ( ! function_exists( 'file_replacement' ) ) {
	function file_replacement( $path, $prefix ): bool {
		if ( ! file_exists( $path ) || ! is_file( $path ) || ! is_readable( $path ) || ! is_writeable( $path ) ) {
			return false;
		}

		$content = file_get_contents( $path );
		$prefix  = trim( $prefix );

		if ( ! $content ) {
			return false;
		}

		$uppercase = strtoupper( $prefix );
		$lowercase = strtolower( $prefix );

		// Replace constants.
		$search = [
			'WPPL_MAIN',
			'WPPL_VERSION',
			'WPPL_PRIORITY',
			'WPPL_NAME',
			'WPPL_SLUG',
			'WPPL_WOOCOMMERCE_REQUIRED',
		];

		$replace = [
			"{$uppercase}_MAIN",
			"{$uppercase}_VERSION",
			"{$uppercase}_PRIORITY",
			"{$uppercase}_NAME",
			"{$uppercase}_SLUG",
			"{$uppercase}_WOOCOMMERCE_REQUIRED",
		];

		$content = str_replace( $search, $replace, $content );

		// Replace any strings.
		$content = str_replace( [ 'wppl', 'WPPL' ], [ $lowercase, $prefix ], $content );

		file_put_contents( $path, $content );

		// File name fix.
		$sep      = DIRECTORY_SEPARATOR;
		$dirname  = dirname( $path );
		$basename = str_replace( 'wppl', $lowercase, pathinfo( $path, PATHINFO_BASENAME ) );
		$renamed  = "{$dirname}{$sep}{$basename}";

		rename( $path, $renamed );

		return true;
	}
}
