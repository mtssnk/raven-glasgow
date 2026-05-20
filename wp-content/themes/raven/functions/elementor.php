<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once get_stylesheet_directory() . '/elementor/includes/icons.php';
require_once get_stylesheet_directory() . '/elementor/includes/helpers.php';
require_once get_stylesheet_directory() . '/elementor/traits/trait-button.php';
require_once get_stylesheet_directory() . '/elementor/traits/trait-padding.php';

/**
 * Render a button outside of an Elementor widget context (e.g. template parts).
 * Always use this instead of hand-coding btn class strings directly in HTML.
 */
function raven_render_button( string $label, string $url, string $variant = 'primary', string $size = 'md', string $icon = '' ): string {
	$btn = new class {
		use Hello_Child_Button_Controls;
		public function render( array $settings, string $prefix ): string {
			return $this->render_button( $settings, $prefix );
		}
	};
	return $btn->render(
		[
			'btn_text'    => $label,
			'btn_url'     => [ 'url' => $url ],
			'btn_variant' => $variant,
			'btn_size'    => $size,
			'btn_icon'    => $icon,
		],
		'btn'
	);
}

function hello_child_register_elementor_widgets( $widgets_manager ) {
	$widgets_dir = get_stylesheet_directory() . '/elementor/widgets/';

	// Swap built-in Elementor widgets with custom versions.
	// Each override file must contain a class that extends the original widget
	// and overrides render(). After requiring the file, unregister the original
	// by its name (the string returned by the original widget's get_name()) and
	// register the replacement class.
		// Core widget overrides (Elementor core registers at priority 5).
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
		[ $widgets_dir . 'event-date.php',            'Hello_Child_Widget_Event_Date'     ],
		[ $widgets_dir . 'raven-heading.php',         'Hello_Child_Widget_Raven_Heading'  ],
		[ $widgets_dir . 'news-list.php',             'Hello_Child_News_List'             ],
		[ $widgets_dir . 'homepage-hero.php',         'Hello_Child_Widget_Homepage_Hero'  ],
	];

	foreach ( $custom_widgets as [ $file, $class ] ) {
		if ( file_exists( $file ) ) {
			require_once $file;
			$widgets_manager->register( new $class() );
		}
	}
}
add_action( 'elementor/widgets/register', 'hello_child_register_elementor_widgets' );

// Elementor Pro registers its theme-builder widgets at priority 11, so Pro widget
// overrides must run after that.
add_action( 'elementor/widgets/register', function ( $widgets_manager ) {
	$widgets_dir = get_stylesheet_directory() . '/elementor/widgets/';

	$pro_overrides = [
		'theme-post-title'   => [ $widgets_dir . 'post-title.php',   'Hello_Child_Widget_Post_Title'   ],
		'theme-post-content' => [ $widgets_dir . 'post-content.php', 'Hello_Child_Widget_Post_Content' ],
	];

	foreach ( $pro_overrides as $widget_name => [ $file, $class ] ) {
		if ( file_exists( $file ) ) {
			require_once $file;
			$widgets_manager->unregister( $widget_name );
			$widgets_manager->register( new $class() );
		}
	}
}, 20 );

// Disable element cache in non-production environments.
if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
	add_filter( 'pre_option_elementor_element_cache_ttl', function() {
		return 'disable';
	} );
}

// Add custom category for Raven widgets.
add_action( 'elementor/elements/categories_registered', function ( $elements_manager ) {
    $elements_manager->add_category( 'raven', [
        'title' => 'Raven',
        'icon'  => 'fa fa-crow',
    ] );
} );