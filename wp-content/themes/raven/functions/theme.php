<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'after_setup_theme', function () {
	add_image_size( 'raven-sm',  640 );
	add_image_size( 'raven-md', 1024 );
	add_image_size( 'raven-lg', 1440 );
} );

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