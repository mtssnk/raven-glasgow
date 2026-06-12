<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Tailwind safelist — class names rendered by fanzo-fixtures.js (not scanned from PHP):
// fanzo-fixture-group fanzo-fixture-row
// bg-ship rounded-t-[7px] px-4 py-4
// text-heading-2xs text-birch
// flex items-center justify-between gap-4 gap-2 gap-1.5 md:gap-6 px-4 py-3
// border-b-2 border-l-2 border-r-2 border-ship
// text-subtitle-xl shrink-0 text-left
// hidden sm:block w-10 h-10 lg:w-19 lg:h-19 object-contain
// w-6 h-6 w-5 h-5
// justify-center flex-1 lg:min-w-[240px]
// text-body-lg font-bold text-white font-light text-cloud
// text-center
// btn btn-primary btn-sm text-subtitle-sm
// text-cloud py-6 text-center space-y-6

class Hello_Child_Widget_Fanzo_Fixtures extends \Elementor\Widget_Base {

	public function get_name(): string {
		return 'raven-fanzo-fixtures';
	}

	public function get_title(): string {
		return esc_html__( 'Fanzo Fixtures', 'raven' );
	}

	public function get_icon(): string {
		return 'eicon-table';
	}

	public function get_categories(): array {
		return [ 'raven' ];
	}

	public function get_keywords(): array {
		return [ 'fixtures', 'sports', 'fanzo', 'events', 'schedule' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return false;
	}

	public function get_script_depends(): array {
		return [ 'raven-fanzo-fixtures' ];
	}

	protected function register_controls(): void {

		$this->start_controls_section( 'section_content', [
			'label' => esc_html__( 'Options', 'raven' ),
		] );

		$this->add_control( 'venue_id', [
			'label'       => esc_html__( 'Venue ID', 'raven' ),
			'type'        => \Elementor\Controls_Manager::TEXT,
			'default'     => '257220',
			'description' => esc_html__( 'Your Fanzo venue ID.', 'raven' ),
		] );

		$this->add_control( 'cache_duration', [
			'label'       => esc_html__( 'Cache Duration (minutes)', 'raven' ),
			'type'        => \Elementor\Controls_Manager::NUMBER,
			'min'         => 5,
			'max'         => 1440,
			'default'     => 60,
			'description' => esc_html__( 'How long to cache the API response.', 'raven' ),
		] );

		$this->add_control( 'book_label', [
			'label'   => esc_html__( 'Booking Button Label', 'raven' ),
			'type'    => \Elementor\Controls_Manager::TEXT,
			'default' => esc_html__( 'Book', 'raven' ),
		] );

		$this->add_control( 'book_fallback_url', [
			'label'       => esc_html__( 'Fallback Booking URL', 'raven' ),
			'type'        => \Elementor\Controls_Manager::URL,
			'default'     => [ 'url' => '/book-now' ],
			'description' => esc_html__( 'Used when a fixture has no booking link from the API.', 'raven' ),
		] );

		$this->add_control( 'load_more_label', [
			'label'   => esc_html__( 'Load More Label', 'raven' ),
			'type'    => \Elementor\Controls_Manager::TEXT,
			'default' => esc_html__( 'Load More', 'raven' ),
		] );

		$this->add_control( 'per_page', [
			'label'       => esc_html__( 'Fixtures Per Page', 'raven' ),
			'type'        => \Elementor\Controls_Manager::NUMBER,
			'min'         => 0,
			'max'         => 100,
			'default'     => 10,
			'description' => esc_html__( 'Number of fixtures to show before "Load More". Set to 0 to show all.', 'raven' ),
		] );

		$this->add_control( 'no_fixtures_label', [
			'label'   => esc_html__( 'No Fixtures Text', 'raven' ),
			'type'    => \Elementor\Controls_Manager::TEXT,
			'default' => esc_html__( 'No fixtures found.', 'raven' ),
		] );

		$this->end_controls_section();
	}

	// ── API fetch ─────────────────────────────────────────────────────────────

	private function get_fixtures( string $venue_id, int $cache_minutes ): array {
		$cache_key = 'raven_fanzo_' . md5( $venue_id );
		$cached    = get_transient( $cache_key );

		if ( false !== $cached ) {
			return $cached;
		}

		$url      = 'https://www-service.fanzo.com/venues/' . rawurlencode( $venue_id ) . '/fixture/json';
		$response = wp_remote_get( $url, [ 'timeout' => 10 ] );

		if ( is_wp_error( $response ) || 200 !== (int) wp_remote_retrieve_response_code( $response ) ) {
			return [];
		}

		$data     = json_decode( wp_remote_retrieve_body( $response ), true );
		$fixtures = is_array( $data['result'] ?? null ) ? $data['result'] : [];

		set_transient( $cache_key, $fixtures, $cache_minutes * MINUTE_IN_SECONDS );

		return $fixtures;
	}

	// ── Render ────────────────────────────────────────────────────────────────

	protected function render(): void {
		$settings        = $this->get_settings_for_display();
		$venue_id        = trim( $settings['venue_id'] ?? '257220' );
		$cache_minutes   = max( 5, (int) ( $settings['cache_duration'] ?? 60 ) );
		$book_label      = $settings['book_label']        ?? 'Book';
		$load_more_label = $settings['load_more_label']  ?? 'Load More';
		$no_fixtures     = $settings['no_fixtures_label'] ?? 'No fixtures found.';
		$widget_id       = $this->get_id();

		$fixtures = $this->get_fixtures( $venue_id, $cache_minutes );
		?>
		<div class="fanzo-fixtures js-fanzo-fixtures"
		     data-widget-id="<?php echo esc_attr( $widget_id ); ?>">

			<form class="js-fanzo-filter-wrap md:flex flex-wrap items-center gap-4 mb-6" aria-label="<?php esc_attr_e( 'Filter fixtures', 'raven' ); ?>">
				<div class="js-fanzo-date-wrap flex flex-col md:flex-row md:items-center gap-3">
					<label class="text-subtitle-sm">Filter</label>
					<select class="js-fanzo-filter">
						<option value=""><?php esc_html_e( 'All Sports & Events', 'raven' ); ?></option>
					</select>
				</div>
				<div class="js-fanzo-sport-wrap flex items-center mt-3 md:mt-0">					
					<select class="js-fanzo-date-filter">
						<option value="" disabled selected><?php esc_html_e( 'Select a date', 'raven' ); ?></option>
						<option value=""><?php esc_html_e( 'All dates', 'raven' ); ?></option>
					</select>
				</div>
			</form>

			<div class="js-fanzo-list space-y-6"></div>

			<div class="js-fanzo-load-more-wrap mt-8 flex justify-center" hidden>
				<button class="js-fanzo-load-more btn btn-primary btn-md text-subtitle-md">
					<?php echo esc_html( $load_more_label ); ?>
				</button>
			</div>

		</div>

		<script>
		( window.ravenFanzoData = window.ravenFanzoData || {} )[<?php echo wp_json_encode( $widget_id ); ?>] = <?php echo wp_json_encode(
			[
				'fixtures'        => $fixtures,
				'perPage'         => (int) ( $settings['per_page'] ?? 10 ),
				'bookLabel'       => $book_label,
				'bookFallbackUrl' => $settings['book_fallback_url']['url'] ?? '/book-now',
				'noFixturesText'  => $no_fixtures,
			],
			JSON_HEX_TAG | JSON_HEX_AMP
		); ?>;
		</script>
		<?php
	}
}
