<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function raven_get_icons(): array {
	return [
		'arrow-down' => '<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="none" viewBox="0 0 26 26"><path stroke="currentColor" stroke-miterlimit="10" stroke-width="2" d="M13 3v19M8.06 16.38l4.97 6.78L18 16.38"/></svg>',
		'arrow-right' => '<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="none" viewBox="0 0 26 26"><path stroke="currentColor" stroke-miterlimit="10" stroke-width="2" d="M1 12.97h22M17.38 17.94l6.78-4.97L17.38 8"/></svg>',
		'plus' => '<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="none" viewBox="0 0 26 26"><path stroke="currentColor" stroke-miterlimit="10" stroke-width="2" d="M13 3v19M3.495 12.495h19"/></svg>',
		'calendar' => '<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="none" viewBox="0 0 26 26"><path stroke="currentColor" stroke-width="2" d="M5 4h16a2 2 0 0 1 2 2v4H3V6a2 2 0 0 1 2-2ZM23 10v14H3V10zM8 1v6M18 1v6"/></svg>',
	];
}

function raven_get_icon( string $name ): string {
	return raven_get_icons()[ $name ] ?? '';
}

function raven_icon_options(): array {
	$options = [ '' => __( 'None', 'raven' ) ];
	foreach ( raven_get_icons() as $name => $svg ) {
		$options[ $name ] = ucwords( str_replace( '-', ' ', $name ) );
	}
	return $options;
}
