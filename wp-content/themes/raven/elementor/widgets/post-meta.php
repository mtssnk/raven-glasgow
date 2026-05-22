<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Hello_Child_Widget_Post_Meta extends \Elementor\Widget_Base {

	public function get_name(): string {
		return 'raven-post-meta';
	}

	public function get_title(): string {
		return esc_html__( 'Post Meta', 'raven' );
	}

	public function get_icon(): string {
		return 'eicon-meta-data';
	}

	public function get_categories(): array {
		return [ 'raven' ];
	}

	public function get_keywords(): array {
		return [ 'event', 'date', 'category', 'meta', 'card' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return false;
	}

	protected function register_controls(): void {

		$this->start_controls_section( 'section_style', [
			'label' => esc_html__( 'Style', 'raven' ),
			'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
		] );

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'typography',
				'selector' => '{{WRAPPER}} .post-meta',
			]
		);

		$this->add_control( 'text_color', [
			'label'     => esc_html__( 'Text Colour', 'raven' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .post-meta' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_section();
	}

	private function format_date( string $ymd ): string {
		$dt = \DateTime::createFromFormat( 'Ymd', $ymd );
		return $dt ? $dt->format( 'j F' ) : '';
	}

	private function render_event_meta( int $post_id ): void {
		if ( ! function_exists( 'get_field' ) ) {
			return;
		}

		if ( get_field( 'event_use_custom_date', $post_id ) ) {
			$text = get_field( 'event_custom_date_text', $post_id );
			if ( $text ) {
				echo '<p class="post-meta m-0">' . esc_html( $text ) . '</p>';
			}
			return;
		}

		$date     = get_field( 'event_date', $post_id );
		$end_date = get_field( 'event_end_date', $post_id );

		if ( ! $date ) {
			return;
		}

		$start       = $this->format_date( $date );
		$end         = $end_date ? $this->format_date( $end_date ) : '';
		$date_string = $end ? "{$start} – {$end}" : $start;

		echo '<p class="post-meta m-0">' . esc_html( $date_string ) . '</p>';
	}

	private function render_post_categories( int $post_id ): void {
		$cats = get_the_category( $post_id );
		if ( empty( $cats ) ) {
			return;
		}

		$cats = array_filter(
			$cats,
			fn( $cat ) => ! in_array( $cat->slug, [ 'uncategorized', 'uncategorised' ], true )
		);

		if ( empty( $cats ) ) {
			return;
		}

		$names = array_map( fn( $cat ) => esc_html( $cat->name ), $cats );
		echo '<p class="post-meta m-0">' . implode( ', ', $names ) . '</p>';
	}

	protected function render(): void {
		$post_id = get_the_ID();

		if ( 'event' === get_post_type( $post_id ) ) {
			$this->render_event_meta( $post_id );
		} else {
			$this->render_post_categories( $post_id );
		}
	}
}
