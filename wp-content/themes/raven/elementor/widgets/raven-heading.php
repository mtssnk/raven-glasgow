<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Hello_Child_Widget_Raven_Heading extends \Elementor\Widget_Base {

	use Hello_Child_Padding_Controls;

	public function get_name(): string {
		return 'raven-heading';
	}

	public function get_title(): string {
		return esc_html__( 'Raven Heading', 'raven' );
	}

	public function get_icon(): string {
		return 'eicon-heading';
	}

	public function get_categories(): array {
		return [ 'general' ];
	}

	public function get_keywords(): array {
		return [ 'heading', 'raven', 'title' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return false;
	}

	protected function register_controls(): void {
		$this->start_controls_section( 'section_content', [
			'label' => esc_html__( 'Content', 'raven' ),
		] );

		$this->add_control( 'text', [
			'label'       => esc_html__( 'Text', 'raven' ),
			'type'        => \Elementor\Controls_Manager::TEXT,
			'description' => esc_html__( 'Maximum 25 characters.', 'raven' ),
		] );

		$this->add_control( 'spacing_divider', [
			'type' => \Elementor\Controls_Manager::DIVIDER,
		] );

		$this->register_padding_controls( 'spacing', 'none', 'none' );

		$this->end_controls_section();
	}

	protected function render(): void {
		$settings = $this->get_settings_for_display();
		$text     = mb_substr( trim( $settings['text'] ?? '' ), 0, 25 );

		if ( ! $text ) {
			return;
		}

		$padding = $this->get_padding_classes( $settings, 'spacing' );
		?>
		<header class="container text-ship text-center min-h-[136px] flex items-center w-full <?php echo esc_attr( $padding ); ?>">
			<div class="relative w-full">
				<div role="decorative" aria-hidden="true" class="text-ship w-[272px] h-[136px] absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 z-10">
					<?php echo raven_get_icon( 'raven' ); ?>
				</div>
				<hr class="border-t-2 w-[20%] absolute left-[16%] top-[-5px]" />
				<hr class="border-t-2 w-[20%] absolute right-[16%] top-[-5px]" />
				<h2 class="relative z-20 -top-[0.8em] text-subtitle-sm text-smoke"><?php echo raven_format_heading( $text ); ?></h2>
			</div>
		</header>
		<?php
	}
}
