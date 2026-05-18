<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Escape a heading string and wrap any {{word}} tokens in a coloured span.
 *
 * Usage in CMS: "this is a {{great}} heading"
 * Output:       "this is a <span class="text-fire">great</span> heading"
 */
function raven_format_heading( string $text ): string {
	return preg_replace(
		'/\{\{(.+?)\}\}/',
		'<span class="text-fire">$1</span>',
		esc_html( $text )
	);
}
