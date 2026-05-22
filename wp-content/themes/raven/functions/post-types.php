<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', function () {
	register_post_type( 'event', [
		'labels' => [
			'name'               => __( 'Events', 'raven' ),
			'singular_name'      => __( 'Event', 'raven' ),
			'add_new'            => __( 'Add New', 'raven' ),
			'add_new_item'       => __( 'Add New Event', 'raven' ),
			'edit_item'          => __( 'Edit Event', 'raven' ),
			'new_item'           => __( 'New Event', 'raven' ),
			'view_item'          => __( 'View Event', 'raven' ),
			'view_items'         => __( 'View Events', 'raven' ),
			'search_items'       => __( 'Search Events', 'raven' ),
			'not_found'          => __( 'No events found', 'raven' ),
			'not_found_in_trash' => __( 'No events found in Trash', 'raven' ),
			'all_items'          => __( 'All Events', 'raven' ),
			'menu_name'          => __( 'Events', 'raven' ),
		],
		'public'            => true,
		'has_archive'       => true,
		'rewrite'           => [ 'slug' => 'events' ],
		'supports'          => [ 'title', 'editor', 'thumbnail', 'excerpt' ],
		'menu_icon'         => 'dashicons-calendar-alt',
		'show_in_rest'      => true,
		'show_in_nav_menus' => true,
	] );
} );

/**
 * Elementor Loop Grid — Query ID: raven-events
 *
 * Filters the query to show only:
 *   - Recurring / custom-date events (no date filtering)
 *   - Events whose start date is today or in the future
 *   - Events whose end date is today or in the future (ongoing)
 *
 * Then reorders results in PHP:
 *   1. Ongoing (started in past, end date >= today)
 *   2. Upcoming (start date >= today), ordered by start date ASC
 *   3. Recurring / custom-date events
 *
 * In the Elementor editor: Loop Grid → Query → Advanced → Query ID → raven-events
 */
add_action( 'elementor/query/raven-events', function ( \WP_Query $query ) {
	$today = date( 'Ymd' );

	$query->set( 'meta_query', [
		'relation' => 'OR',
		[
			'key'     => 'event_use_custom_date',
			'value'   => '1',
			'compare' => '=',
		],
		[
			'key'     => 'event_date',
			'value'   => $today,
			'compare' => '>=',
		],
		[
			'key'     => 'event_end_date',
			'value'   => $today,
			'compare' => '>=',
		],
	] );

	$query->set( 'raven_events_reorder', true );
} );

add_filter( 'posts_results', function ( array $posts, \WP_Query $query ) {
	if ( ! $query->get( 'raven_events_reorder' ) ) {
		return $posts;
	}

	$today    = date( 'Ymd' );
	$ongoing  = [];
	$upcoming = [];
	$recurring = [];

	foreach ( $posts as $post ) {
		if ( get_post_meta( $post->ID, 'event_use_custom_date', true ) ) {
			$recurring[] = $post;
			continue;
		}

		$start = get_post_meta( $post->ID, 'event_date', true );
		$end   = get_post_meta( $post->ID, 'event_end_date', true );

		if ( $start && $start < $today && $end && $end >= $today ) {
			$post->_raven_sort_date = $start;
			$ongoing[]  = $post;
		} else {
			$post->_raven_sort_date = $start ?: '99999999';
			$upcoming[] = $post;
		}
	}

	usort( $upcoming, fn( $a, $b ) => strcmp( $a->_raven_sort_date, $b->_raven_sort_date ) );

	return array_merge( $ongoing, $upcoming, $recurring );
}, 10, 2 );
