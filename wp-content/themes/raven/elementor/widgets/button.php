<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Tailwind safelist — dynamic class names used in render():
// btn btn-primary btn-light btn-dark btn-lg btn-md btn-sm
// text-subtitle-lg text-subtitle-md text-subtitle-sm

class Hello_Child_Widget_Button extends \Elementor\Widget_Button {

	public function has_widget_inner_wrapper(): bool {
		return false;
	}

	protected function register_controls(): void {
		parent::register_controls();

		// Remove the Type control (Default/Info/Success etc.) — replaced by Variant.
		$this->remove_control( 'button_type' );

		$this->start_injection( [
			'of' => 'text',
			'at' => 'before',
		] );

		$this->add_control( 'raven_variant', [
			'label'   => esc_html__( 'Variant', 'raven' ),
			'type'    => \Elementor\Controls_Manager::SELECT,
			'options' => [
				'primary' => esc_html__( 'Primary (red)', 'raven' ),
				'light'   => esc_html__( 'Light (cream)', 'raven' ),
				'dark'    => esc_html__( 'Dark', 'raven' ),
			],
			'default' => 'primary',
		] );

		$this->add_control( 'raven_size', [
			'label'   => esc_html__( 'Size', 'raven' ),
			'type'    => \Elementor\Controls_Manager::SELECT,
			'options' => [
				'lg' => esc_html__( 'Large', 'raven' ),
				'md' => esc_html__( 'Medium', 'raven' ),
				'sm' => esc_html__( 'Small', 'raven' ),
			],
			'default' => 'md',
		] );

		$this->end_injection();
	}

	protected function render(): void {
		$settings = $this->get_settings_for_display();
		$text     = trim( $settings['text'] ?? '' );

		if ( '' === $text ) {
			return;
		}

		$variant = $settings['raven_variant'] ?? 'primary';
		$size    = $settings['raven_size'] ?? 'md';

		$type_classes = [
			'lg' => 'text-subtitle-lg',
			'md' => 'text-subtitle-md',
			'sm' => 'text-subtitle-sm',
		];
		$type_class = $type_classes[ $size ] ?? 'text-subtitle-md';

		$this->add_render_attribute( 'button', 'class', [ 'btn', "btn-{$variant}", "btn-{$size}", $type_class ] );
		$this->add_render_attribute( 'button', 'role', 'button' );

		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_link_attributes( 'button', $settings['link'] );
		}

		if ( ! empty( $settings['button_css_id'] ) ) {
			$this->add_render_attribute( 'button', 'id', $settings['button_css_id'] );
		}

		$icon_html  = '';
		$icon_align = $settings['icon_align'] ?? 'left';

		if ( ! empty( $settings['selected_icon']['value'] ) ) {
			ob_start();
			\Elementor\Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] );
			$icon_html = ob_get_clean();
		}
		?>
		<a <?php $this->print_render_attribute_string( 'button' ); ?>>
			<?php if ( $icon_html && 'left' === $icon_align ) : ?>
			<span class="inline-flex"><?php echo $icon_html; // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
			<?php endif; ?>
			<?php echo esc_html( $text ); ?>
			<?php if ( $icon_html && 'right' === $icon_align ) : ?>
			<span class="inline-flex"><?php echo $icon_html; // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
			<?php endif; ?>
		</a>
		<?php
	}
}
