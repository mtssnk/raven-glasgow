<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

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
					'value'    => 'post',
				],
				[
					'param'    => 'post_category',
					'operator' => '==',
					'value'    => (string) ( get_term_by( 'slug', 'events', 'category' )->term_id ?? 0 ),
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
} );
