<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Tailwind safelist — dynamic class names used in render() and render_card():
// md:grid-cols-[5fr_7fr] md:grid-cols-[7fr_5fr]
// text-heading-xs text-heading-sm
// [&_svg]:w-5 [&_svg]:h-5 [&_svg]:w-7 [&_svg]:h-7

class Hello_Child_Masonry_Cards extends \Elementor\Widget_Base {

	use Hello_Child_Padding_Controls;

	public function get_name(): string {
		return 'hello_child_masonry_cards';
	}

	public function get_title(): string {
		return esc_html__( 'Masonry Cards', 'raven' );
	}

	public function get_icon(): string {
		return 'eicon-gallery-masonry';
	}

	public function get_categories(): array {
		return [ 'raven' ];
	}

	public function get_keywords(): array {
		return [ 'masonry', 'cards', 'grid', 'gallery' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return false;
	}

	protected function register_controls(): void {

		// ── Options ───────────────────────────────────────────────────────────

		$this->start_controls_section( 'section_options', [
			'label' => esc_html__( 'Options', 'raven' ),
		] );

		$this->add_control( 'id', [
			'label'       => esc_html__( 'ID', 'raven' ),
			'type'        => \Elementor\Controls_Manager::TEXT,
			'description' => esc_html__( 'Optional anchor ID, must be unique on the page.', 'raven' ),
		] );

		$this->add_control( 'options_divider', [
			'type' => \Elementor\Controls_Manager::DIVIDER,
		] );

		$this->register_padding_controls( 'spacing' );

		$this->end_controls_section();

		// ── Cards ─────────────────────────────────────────────────────────────

		$this->start_controls_section( 'section_cards', [
			'label' => esc_html__( 'Cards', 'raven' ),
		] );

		$repeater = new \Elementor\Repeater();

		$repeater->add_control( 'image', [
			'label'   => esc_html__( 'Image', 'raven' ),
			'type'    => \Elementor\Controls_Manager::MEDIA,
			'default' => [ 'url' => \Elementor\Utils::get_placeholder_image_src() ],
		] );

		$repeater->add_control( 'heading', [
			'label'       => esc_html__( 'Heading', 'raven' ),
			'type'        => \Elementor\Controls_Manager::TEXT,
			'placeholder' => esc_html__( 'e.g. Food & Drink', 'raven' ),
		] );

		$repeater->add_control( 'url', [
			'label'   => esc_html__( 'URL / Anchor', 'raven' ),
			'type'    => \Elementor\Controls_Manager::URL,
			'dynamic' => [ 'active' => true ],
		] );

		$this->add_control( 'cards', [
			'label'       => esc_html__( 'Cards', 'raven' ),
			'type'        => \Elementor\Controls_Manager::REPEATER,
			'fields'      => $repeater->get_controls(),
			'title_field' => '{{{ heading }}}',
			'default'     => [
				[ 'heading' => 'Card One' ],
				[ 'heading' => 'Card Two' ],
				[ 'heading' => 'Card Three' ],
			],
		] );

		$this->end_controls_section();
	}

	private function render_card( array $card, string $size ): string {
		$image_id = $card['image']['id'] ?? 0;
		$heading  = trim( $card['heading'] ?? '' );
		$url_data = $card['url'] ?? [];
		$href     = ! empty( $url_data['url'] ) ? esc_url( $url_data['url'] ) : '#';

		$attrs = '';
		if ( ! empty( $url_data['is_external'] ) ) {
			$attrs .= ' target="_blank"';
		}
		$rel = array_filter( [
			! empty( $url_data['nofollow'] )    ? 'nofollow'            : '',
			! empty( $url_data['is_external'] ) ? 'noopener noreferrer' : '',
		] );
		if ( $rel ) {
			$attrs .= ' rel="' . esc_attr( implode( ' ', $rel ) ) . '"';
		}

		$is_large = 'large' === $size;

		$card_classes  = $is_large
			? 'block relative overflow-hidden aspect-[659/628] object-cover'
			: 'block relative overflow-hidden aspect-video md:aspect-auto md:flex-1';
		$heading_class = $is_large ? 'text-heading-sm' : 'text-heading-xs';
		$icon_classes  = $is_large ? '[&_svg]:w-7 [&_svg]:h-7' : '[&_svg]:w-5 [&_svg]:h-5';

		$image = $image_id
			? wp_get_attachment_image( $image_id, 'raven-lg', false, [
				'class'   => 'absolute inset-0 w-full !h-full object-cover',
				'loading' => 'lazy',
			] )
			: '';

		$label = $heading ? sprintf(
			'<div class="absolute bottom-0 left-0 flex items-end gap-2 px-3 pb-[0.2em] pt-[0.1em] bg-white text-night %1$s">
				<span class="inline-flex">%2$s</span>
				<span class="shrink-0 w-[1em] h-[1em] inline-flex items-center justify-center %3$s">%4$s</span>
			</div>',
			esc_attr( $heading_class ),       // %1$s heading utility class
			esc_html( $heading ),             // %2$s card heading text
			esc_attr( $icon_classes ),        // %3$s icon size classes
			raven_get_icon( 'arrow-right' )   // %4$s arrow SVG
		) : '';

		return sprintf(
			'<a href="%1$s" class="%2$s"%3$s>%4$s%5$s</a>',
			$href,                      // %1$s href
			esc_attr( $card_classes ),  // %2$s card classes
			$attrs,                     // %3$s target/rel attributes
			$image,                     // %4$s image HTML
			$label                      // %5$s label HTML
		);
	}

	protected function render(): void {
		$settings = $this->get_settings_for_display();
		$cards    = $settings['cards'] ?? [];
		$padding  = $this->get_padding_classes( $settings, 'spacing' );
		$id_attr  = ! empty( $settings['id'] )
			? ' id="' . esc_attr( $settings['id'] ) . '"'
			: '';

		$chunks = array_chunk( $cards, 3 );
		?>
		<div class="masonry-cards container space-y-5xl md:space-y-8xl <?php echo esc_attr( $padding ); ?>"<?php echo $id_attr; ?>>
			<?php foreach ( $chunks as $i => $chunk ) :
				$reverse     = ( $i % 2 === 1 );
				$large_card  = $reverse ? ( $chunk[0] ?? null ) : ( $chunk[2] ?? null );
				$small_cards = $reverse ? array_slice( $chunk, 1, 2 ) : array_slice( $chunk, 0, 2 );
				$has_large   = ! empty( $large_card );
				$has_smalls  = count( $small_cards ) > 0;

				if ( ! $has_large && ! $has_smalls ) {
					continue;
				}

				$grid_cols = '';
				if ( $has_large && $has_smalls ) {
					$grid_cols = $reverse ? 'md:grid-cols-[7fr_5fr]' : 'md:grid-cols-[5fr_7fr]';
				}
			?>
			<div class="space-y-5xl md:space-y-0 md:grid gap-8xl <?php echo esc_attr( $grid_cols ); ?>">

				<?php if ( ! $reverse && $has_smalls ) : ?>
				<div class="space-y-5xl md:space-y-0 md:flex flex-col gap-8xl">
					<?php foreach ( $small_cards as $card ) : ?>
					<?php echo $this->render_card( $card, 'small' ); ?>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>

				<?php if ( $has_large ) : ?>
				<?php echo $this->render_card( $large_card, 'large' ); ?>
				<?php endif; ?>

				<?php if ( $reverse && $has_smalls ) : ?>
				<div class="space-y-5xl md:space-y-0 md:flex flex-col gap-8xl">
					<?php foreach ( $small_cards as $card ) : ?>
					<?php echo $this->render_card( $card, 'small' ); ?>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>

			</div>
			<?php endforeach; ?>
		</div>
		<?php
	}
}
