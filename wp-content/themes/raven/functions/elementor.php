<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once get_stylesheet_directory() . '/elementor/includes/icons.php';
require_once get_stylesheet_directory() . '/elementor/traits/trait-button.php';
require_once get_stylesheet_directory() . '/elementor/traits/trait-padding.php';

function hello_child_register_elementor_widgets( $widgets_manager ) {
	$widgets_dir = get_stylesheet_directory() . '/elementor/widgets/';

	// Swap built-in Elementor widgets with custom versions.
	// Each override file must contain a class that extends the original widget
	// and overrides render(). After requiring the file, unregister the original
	// by its name (the string returned by the original widget's get_name()) and
	// register the replacement class.
	$overrides = [
		'heading' => [ $widgets_dir . 'heading.php', 'Hello_Child_Widget_Heading' ],
	];

	foreach ( $overrides as $widget_name => [ $file, $class ] ) {
		if ( file_exists( $file ) ) {
			require_once $file;
			$widgets_manager->unregister( $widget_name );
			$widgets_manager->register( new $class() );
		}
	}

	// Custom new widgets: [ file, class ]
	$custom_widgets = [
		[ $widgets_dir . 'two-column-media-text.php', 'Hello_Child_Two_Column_Media_Text' ],
		[ $widgets_dir . 'masonry-cards.php',         'Hello_Child_Masonry_Cards'         ],
	];

	foreach ( $custom_widgets as [ $file, $class ] ) {
		if ( file_exists( $file ) ) {
			require_once $file;
			$widgets_manager->register( new $class() );
		}
	}
}
add_action( 'elementor/widgets/register', 'hello_child_register_elementor_widgets' );

// Disable element cache in non-production environments.
if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
	add_filter( 'pre_option_elementor_element_cache_ttl', function() {
		return 'disable';
	} );
}
