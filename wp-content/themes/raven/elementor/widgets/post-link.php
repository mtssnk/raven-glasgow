<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Tailwind safelist — dynamic class names used in render():
// btn btn-primary btn-light btn-dark btn-lg btn-md btn-sm
// text-subtitle-lg text-subtitle-md text-subtitle-sm

class Hello_Child_Widget_Post_Link extends \Elementor\Widget_Base {

	public function get_name(): string {
		return 'raven-post-link';
	}

	public function get_title(): string {
		return esc_html__( 'Post Link', 'raven' );
	}

	public function get_icon(): string {
		return 'eicon-button';
	}

	public function get_categories(): array {
		return [ 'raven' ];
	}

	public function get_keywords(): array {
		return [ 'link', 'button', 'post', 'acf', 'url' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return false;
	}

	protected function register_controls(): void {

		$this->start_controls_section( 'section_content', [
			'label' => esc_html__( 'Content', 'raven' ),
		] );

		$this->add_control( 'notice', [
			'type'            => \Elementor\Controls_Manager::RAW_HTML,
			'raw'             => esc_html__( 'Change the button link and text in the WordPress post editor.', 'raven' ),
			'content_classes' => 'elementor-descriptor',
		] );

		$this->end_controls_section();
	}

	protected function render(): void {
		if ( ! function_exists( 'get_field' ) ) {
			return;
		}

		$link = get_field( 'link' );

		if ( empty( $link['url'] ) ) {
			return;
		}

		$text   = ( $link['title'] ?? '' ) ?: esc_html__( 'Find out more', 'raven' );
		$target = ! empty( $link['target'] ) ? ' target="' . esc_attr( $link['target'] ) . '"' : '';
		$rel    = '_blank' === ( $link['target'] ?? '' ) ? ' rel="noopener noreferrer"' : '';
		?>
		<a href="<?php echo esc_url( $link['url'] ); ?>"<?php echo $target . $rel; ?> class="btn btn-primary btn-md text-subtitle-md" role="button">
			<?php echo esc_html( $text ); ?>
		</a>
		<?php
	}
}
