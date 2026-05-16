<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function hello_child_enqueue_styles() {
	wp_enqueue_style(
		'raven-adobe-fonts',
		'https://use.typekit.net/tiq7mas.css',
		[],
		null
	);

	wp_enqueue_style(
		'hello-elementor-child-main',
		get_stylesheet_directory_uri() . '/assets/dist/main.css',
		[ 'raven-adobe-fonts' ],
		filemtime( get_stylesheet_directory() . '/assets/dist/main.css' )
	);
}
add_action( 'wp_enqueue_scripts', 'hello_child_enqueue_styles' );

add_action( 'wp_enqueue_scripts', function () {
	wp_dequeue_style( 'hello-elementor' );
}, 20 );
