<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Tailwind safelist — dynamic class names used in render_button():
// btn btn-primary btn-light btn-dark btn-lg btn-md btn-sm
// text-subtitle-lg text-subtitle-md text-subtitle-sm

trait Hello_Child_Button_Controls {

	/**
	 * Register button controls into the currently open Elementor section.
	 * $prefix allows a single widget to have multiple independent buttons.
	 */
	protected function register_button_controls( string $prefix = 'btn' ): void {

		$this->add_control( "{$prefix}_text", [
			'label'       => esc_html__( 'Button Label', 'raven' ),
			'type'        => \Elementor\Controls_Manager::TEXT,
			'placeholder' => esc_html__( 'e.g. Book Today', 'raven' ),
		] );

		$this->add_control( "{$prefix}_url", [
			'label'   => esc_html__( 'URL / Anchor', 'raven' ),
			'type'    => \Elementor\Controls_Manager::URL,
			'description' => esc_html__( 'Add a link for the button. Can be a URL or an anchor (e.g. #section-id).', 'raven' ),
			'dynamic' => [ 'active' => true ],
		] );

		$this->add_control( "{$prefix}_variant", [
			'label'   => esc_html__( 'Colour', 'raven' ),
			'type'    => \Elementor\Controls_Manager::SELECT,
			'options' => [
				'primary' => esc_html__( 'Primary (red)', 'raven' ),
				'light'   => esc_html__( 'Light (cream)', 'raven' ),
				'dark'    => esc_html__( 'Dark', 'raven' ),
			],
			'default' => 'primary',
		] );

		$this->add_control( "{$prefix}_size", [
			'label'   => esc_html__( 'Size', 'raven' ),
			'type'    => \Elementor\Controls_Manager::SELECT,
			'options' => [
				'lg' => esc_html__( 'Large', 'raven' ),
				'md' => esc_html__( 'Medium', 'raven' ),
				'sm' => esc_html__( 'Small', 'raven' ),
			],
			'default' => 'md',
		] );

		$this->add_control( "{$prefix}_icon", [
			'label'   => esc_html__( 'Icon', 'raven' ),
			'type'    => \Elementor\Controls_Manager::SELECT,
			'options' => raven_icon_options(),
			'default' => '',
		] );
	}

	/**
	 * Render a button. Returns an empty string when no label is set,
	 * so the button is simply absent rather than needing a show/hide toggle.
	 */
	protected function render_button( array $settings, string $prefix = 'btn' ): string {
		$text = trim( $settings[ "{$prefix}_text" ] ?? '' );
		if ( '' === $text ) {
			return '';
		}

		$url        = $settings[ "{$prefix}_url" ] ?? [];
		$variant    = $settings[ "{$prefix}_variant" ] ?? 'primary';
		$size       = $settings[ "{$prefix}_size" ] ?? 'lg';
		$icon       = $settings[ "{$prefix}_icon" ] ?? '';

		$type_classes = [ 'lg' => 'text-subtitle-lg', 'md' => 'text-subtitle-md', 'sm' => 'text-subtitle-sm' ];
		$type_class   = $type_classes[ $size ] ?? 'text-subtitle-lg';

		$href = ! empty( $url['url'] ) ? esc_url( $url['url'] ) : '#';

		$attrs = '';
		if ( ! empty( $url['is_external'] ) ) {
			$attrs .= ' target="_blank"';
		}
		$rel = array_filter( [
			! empty( $url['nofollow'] )    ? 'nofollow'   : '',
			! empty( $url['is_external'] ) ? 'noopener'   : '',
			! empty( $url['is_external'] ) ? 'noreferrer' : '',
		] );
		if ( $rel ) {
			$attrs .= ' rel="' . implode( ' ', $rel ) . '"';
		}

		$svg = $icon ? raven_get_icon( $icon ) : '';

		return sprintf(
			'<a href="%s" class="btn btn-%s btn-%s %s"%s>%s%s</a>',
			$href,
			esc_attr( $variant ),
			esc_attr( $size ),
			esc_attr( $type_class ),
			$attrs,
			esc_html( $text ),
			$svg
		);
	}
}
