<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'after_setup_theme', function () {
	add_image_size( 'raven-sm',  640 );
	add_image_size( 'raven-md', 1024 );
	add_image_size( 'raven-lg', 1440 );

	register_nav_menus( [
		'primary' => esc_html__( 'Primary Navigation', 'raven' ),
	] );
} );

function hello_child_register_scripts() {
	wp_register_script(
		'raven-homepage-hero',
		get_stylesheet_directory_uri() . '/assets/js/homepage-hero.js',
		[],
		filemtime( get_stylesheet_directory() . '/assets/js/homepage-hero.js' ),
		[ 'strategy' => 'defer' ]
	);

	wp_register_script(
		'raven-fanzo-fixtures',
		get_stylesheet_directory_uri() . '/assets/js/fanzo-fixtures.js',
		[],
		filemtime( get_stylesheet_directory() . '/assets/js/fanzo-fixtures.js' ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'hello_child_register_scripts' );

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

add_action('admin_init', function () {
    // Redirect any user trying to access comments page
    global $pagenow;
    
    if ($pagenow === 'edit-comments.php') {
        wp_redirect(admin_url());
        exit;
    }

    // Remove comments metabox from dashboard
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');

    // Disable support for comments and trackbacks in post types
    foreach (get_post_types() as $post_type) {
        if (post_type_supports($post_type, 'comments')) {
            remove_post_type_support($post_type, 'comments');
            remove_post_type_support($post_type, 'trackbacks');
        }
    }
});

// Resttrict block formats in TinyMCE to only allow paragraphs and headings (h3-h6)
add_filter( 'tiny_mce_before_init', function ( $settings ) {
	$settings['block_formats'] = 'Paragraph=p;Heading 3=h3;Heading 4=h4;Heading 5=h5;Heading 6=h6;Preformatted=pre';
	return $settings;
} );

// Close comments on the front-end
add_filter('comments_open', '__return_false', 20, 2);
add_filter('pings_open', '__return_false', 20, 2);

// Hide existing comments
add_filter('comments_array', '__return_empty_array', 10, 2);

// Remove comments page in menu
add_action('admin_menu', function () {
    remove_menu_page('edit-comments.php');
});

// Remove comments links from admin bar
add_action('init', function () {
    if (is_admin_bar_showing()) {
        remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
    }
});

// add_filter( 'nav_menu_link_attributes', function ( $atts, $item, $args, $depth ) {
//     if ( in_array( 'is-button', (array) $item->classes, true ) ) {
//         $atts['class'] = trim( ( $atts['class'] ?? '' ) . ' btn btn-primary btn-sm text-subtitle-sm' );
//     }
//     return $atts;
// }, 10, 4 );

// Disable Gutenberg on the back end.
add_filter( 'use_block_editor_for_post', '__return_false' );

// Disable Gutenberg for widgets.
add_filter( 'use_widgets_block_editor', '__return_false' );

add_action( 'wp_enqueue_scripts', function() {
    // Remove CSS on the front end.
    wp_dequeue_style( 'wp-block-library' );

    // Remove Gutenberg theme.
    wp_dequeue_style( 'wp-block-library-theme' );

    // Remove inline global CSS on the front end.
    wp_dequeue_style( 'global-styles' );

    // Remove classic-themes CSS for backwards compatibility for button blocks.
    wp_dequeue_style( 'classic-theme-styles' );
}, 20 );