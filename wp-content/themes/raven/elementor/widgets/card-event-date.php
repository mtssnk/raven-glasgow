<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Hello_Child_Widget_Card_Event_Date extends \Elementor\Widget_Base {

	public function get_name(): string {
		return 'raven-card-event-date';
	}

	public function get_title(): string {
		return esc_html__( 'Card Event Date', 'raven' );
	}

	public function get_icon(): string {
		return 'eicon-calendar';
	}

	public function get_categories(): array {
		return [ 'raven' ];
	}

	public function get_keywords(): array {
		return [ 'event', 'date', 'card' ];
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
				'selector' => '{{WRAPPER}} .card-event-date',
			]
		);

		$this->add_control( 'text_color', [
			'label'     => esc_html__( 'Text Colour', 'raven' ),
			'type'      => \Elementor\Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .card-event-date' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_section();
	}

	private function format_date( string $ymd ): string {
		$dt = \DateTime::createFromFormat( 'Ymd', $ymd );
		return $dt ? $dt->format( 'j F' ) : '';
	}

	protected function render(): void {
		if ( ! function_exists( 'get_field' ) ) {
			return;
		}

		$use_custom = get_field( 'event_use_custom_date' );

		if ( $use_custom ) {
			$text = get_field( 'event_custom_date_text' );
			if ( ! $text ) {
				return;
			}
			echo '<p class="card-event-date m-0">' . esc_html( $text ) . '</p>';
			return;
		}

		$date     = get_field( 'event_date' );
		$end_date = get_field( 'event_end_date' );

		if ( ! $date ) {
			return;
		}

		$start       = $this->format_date( $date );
		$end         = $end_date ? $this->format_date( $end_date ) : '';
		$date_string = $end ? "{$start} – {$end}" : $start;

		echo '<p class="card-event-date m-0">' . esc_html( $date_string ) . '</p>';
	}
}
