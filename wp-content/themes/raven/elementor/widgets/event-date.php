<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Hello_Child_Widget_Event_Date extends \Elementor\Widget_Base {

	public function get_name(): string {
		return 'raven-event-date';
	}

	public function get_title(): string {
		return esc_html__( 'Event Date', 'raven' );
	}

	public function get_icon(): string {
		return 'eicon-calendar';
	}

	public function get_categories(): array {
		return [ 'raven' ];
	}

	public function get_keywords(): array {
		return [ 'event', 'date', 'time' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return false;
	}

	protected function register_controls(): void {}

	private function format_date( string $ymd ): string {
		$dt = \DateTime::createFromFormat( 'Ymd', $ymd );
		return $dt ? $dt->format( 'j F Y' ) : '';
	}

	
	protected function render(): void {
		
		if ( ! function_exists( 'get_field' ) ) {
			return;
		}

		$event_classes = "text-center container text-heading-xs -mb-[1em] pt-lg";

		$use_custom = get_field( 'event_use_custom_date' );

		if ( $use_custom ) {
			$text = get_field( 'event_custom_date_text' );
			if ( ! $text ) {
				return;
			}
			?>
			<p class="event-date <?php echo esc_attr( $event_classes ); ?>"><?php echo esc_html( $text ); ?></p>
			<?php
			return;
		}

		$date     = get_field( 'event_date' );
		$time     = get_field( 'event_time' );
		$end_date = get_field( 'event_end_date' );

		if ( ! $date ) {
			return;
		}

		$start = $this->format_date( $date );
		$end   = $end_date ? $this->format_date( $end_date ) : '';

		$date_string = $end ? "{$start} – {$end}" : $start;
		if ( $time ) {
			$date_string .= ', ' . $time;
		}
		?>
		<p class="event-date <?php echo esc_attr( $event_classes ); ?>"><?php echo esc_html( $date_string ); ?></p>
		<?php
	}
}
