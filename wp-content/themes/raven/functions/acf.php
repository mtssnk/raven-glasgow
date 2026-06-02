<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function raven_get_event_date_label( int $post_id ): string {
	if ( 'event' !== get_post_type( $post_id ) ) {
		$cats = get_the_category( $post_id );
		if ( empty( $cats ) || in_array( $cats[0]->slug, [ 'uncategorized', 'uncategorised' ], true ) ) {
			return 'News';
		}
		return $cats[0]->name;
	}

	if ( function_exists( 'get_field' ) ) {
		if ( get_field( 'event_use_custom_date', $post_id ) ) {
			$custom = get_field( 'event_custom_date_text', $post_id );
			if ( $custom ) {
				return $custom;
			}
		}
		$acf_date = get_field( 'event_date', $post_id );
		if ( $acf_date ) {
			$dt = \DateTime::createFromFormat( 'Ymd', $acf_date );
			if ( $dt ) {
				return $dt->format( 'D j M' );
			}
		}
	}

	return '';
}

add_filter( 'get_the_date', function ( $the_date, $format, $post ) {
	if ( is_singular() ) {
		return $the_date;
	}
	if ( ! $post instanceof WP_Post || 'event' !== get_post_type( $post ) ) {
		return $the_date;
	}
	$label = raven_get_event_date_label( $post->ID );
	return $label ?: $the_date;
}, 10, 3 );

add_filter( 'post_thumbnail_id', function ( $thumbnail_id, $post ) {
	if ( ! function_exists( 'get_field' ) ) {
		return $thumbnail_id;
	}

	$post_id       = is_object( $post ) ? $post->ID : (int) $post;
	$listing_image = get_field( 'listing_image', $post_id );
	$listing_id    = ! empty( $listing_image['id'] ) ? (int) $listing_image['id'] : 0;

	if ( is_singular( [ 'post', 'event' ] ) ) {
		// On single pages: featured image takes priority; listing image is the fallback.
		return $thumbnail_id ?: $listing_id;
	}

	// Everywhere else (archives, pages with Loop Grid): listing image takes priority.
	return $listing_id ?: $thumbnail_id;
}, 10, 2 );

add_action( 'acf/init', function () {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group( [
		'key'    => 'group_event_details',
		'title'  => 'Event Details',
		'fields' => [

			// Toggle: structured dates vs freeform text
			[
				'key'           => 'field_event_use_custom_date',
				'label'         => 'Recurring / custom date',
				'name'          => 'event_use_custom_date',
				'type'          => 'true_false',
				'instructions'  => 'Enable for recurring events (e.g. every Monday). Replaces the date fields with a single text field.',
				'ui'            => 1,
				'default_value' => 0,
			],

			// Structured date fields (shown when toggle is off)
			[
				'key'               => 'field_event_date',
				'label'             => 'Date',
				'name'              => 'event_date',
				'type'              => 'date_picker',
				'display_format'    => 'd/m/Y',
				'return_format'     => 'Ymd',
				'first_day'         => 1,
				'required'          => 1,
				'conditional_logic' => [
					[
						[
							'field'    => 'field_event_use_custom_date',
							'operator' => '==',
							'value'    => '0',
						],
					],
				],
			],

			[
				'key'               => 'field_event_time',
				'label'             => 'Time',
				'name'              => 'event_time',
				'type'              => 'text',
				'instructions'      => 'Optional. e.g. 7pm or 7:00 – 10:00pm',
				'placeholder'       => 'e.g. 7pm',
				'required'          => 0,
				'conditional_logic' => [
					[
						[
							'field'    => 'field_event_use_custom_date',
							'operator' => '==',
							'value'    => '0',
						],
					],
				],
			],

			[
				'key'               => 'field_event_end_date',
				'label'             => 'End Date',
				'name'              => 'event_end_date',
				'type'              => 'date_picker',
				'instructions'      => 'Optional.',
				'display_format'    => 'd/m/Y',
				'return_format'     => 'Ymd',
				'first_day'         => 1,
				'required'          => 0,
				'conditional_logic' => [
					[
						[
							'field'    => 'field_event_use_custom_date',
							'operator' => '==',
							'value'    => '0',
						],
					],
				],
			],

			// Freeform text (shown when toggle is on)
			[
				'key'               => 'field_event_custom_date_text',
				'label'             => 'Custom Date Text',
				'name'              => 'event_custom_date_text',
				'type'              => 'text',
				'instructions'      => 'e.g. Monday – Thursday or Every Sunday from 12pm',
				'placeholder'       => 'e.g. Monday – Thursday',
				'required'          => 0,
				'conditional_logic' => [
					[
						[
							'field'    => 'field_event_use_custom_date',
							'operator' => '==',
							'value'    => '1',
						],
					],
				],
			],

		],
		'location' => [
			[
				[
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'event',
				],
			],
		],
		'menu_order'            => 0,
		'position'              => 'normal',
		'style'                 => 'default',
		'label_placement'       => 'top',
		'instruction_placement' => 'label',
		'active'                => true,
	] );

	acf_add_local_field_group( [
		'key'    => 'group_listing_image',
		'title'  => 'Listing Image',
		'fields' => [
			[
				'key'           => 'field_listing_image',
				'label'         => 'Listing Image',
				'name'          => 'listing_image',
				'type'          => 'image',
				'instructions'  => 'Image without text to be shown on the listing page.',
				'required'      => 1,
				'return_format' => 'array',
				'preview_size'  => 'medium',
				'library'       => 'all',
			],
		],
		'location' => [
			[
				[
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'post',
				],
			],
			[
				[
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'event',
				],
			],
		],
		'menu_order'            => 10,
		'position'              => 'normal',
		'style'                 => 'default',
		'label_placement'       => 'top',
		'instruction_placement' => 'label',
		'active'                => true,
	] );
} );
