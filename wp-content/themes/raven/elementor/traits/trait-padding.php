<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Tailwind safelist — dynamic class names used in get_padding_classes():
// pt-0 pb-0
// pt-md pb-md pt-lg pb-lg pt-2lg pb-2lg pt-3lg pb-3lg pt-4lg pb-4lg
// pt-xl pb-xl pt-2xl pb-2xl pt-3xl pb-3xl pt-4xl pb-4xl
// pt-5xl pb-5xl pt-6xl pb-6xl pt-7xl pb-7xl pt-8xl pb-8xl

trait Hello_Child_Padding_Controls {

	protected function register_padding_controls( string $prefix = 'spacing', string $default_top = '6xl', string $default_bottom = '6xl' ): void {
		$options = [
			'none' => esc_html__( 'None', 'raven' ),
			'md'   => esc_html__( 'MD  (13–15px)', 'raven' ),
			'lg'   => esc_html__( 'LG  (18–20px)', 'raven' ),
			'2lg'  => esc_html__( '2LG (22–25px)', 'raven' ),
			'3lg'  => esc_html__( '3LG (27–30px)', 'raven' ),
			'4lg'  => esc_html__( '4LG (31–35px)', 'raven' ),
			'xl'   => esc_html__( 'XL  (36–40px)', 'raven' ),
			'2xl'  => esc_html__( '2XL (45–50px)', 'raven' ),
			'3xl'  => esc_html__( '3XL (54–60px)', 'raven' ),
			'4xl'  => esc_html__( '4XL (63–70px)', 'raven' ),
			'5xl'  => esc_html__( '5XL (72–80px)', 'raven' ),
			'6xl'  => esc_html__( '6XL (90–100px)', 'raven' ),
			'7xl'  => esc_html__( '7XL (108–120px)', 'raven' ),
			'8xl'  => esc_html__( '8XL (135–150px)', 'raven' ),
		];

		$this->add_control( "{$prefix}_top", [
			'label'   => esc_html__( 'Padding Top', 'raven' ),
			'type'    => \Elementor\Controls_Manager::SELECT,
			'options' => $options,
			'default' => $default_top,
		] );

		$this->add_control( "{$prefix}_bottom", [
			'label'   => esc_html__( 'Padding Bottom', 'raven' ),
			'type'    => \Elementor\Controls_Manager::SELECT,
			'options' => $options,
			'default' => $default_bottom,
		] );
	}

	protected function get_padding_classes( array $settings, string $prefix = 'spacing' ): string {
		$top    = $settings[ "{$prefix}_top" ] ?? '6xl';
		$bottom = $settings[ "{$prefix}_bottom" ] ?? '6xl';

		$pt = 'none' === $top    ? 'pt-0' : "pt-{$top}";
		$pb = 'none' === $bottom ? 'pb-0' : "pb-{$bottom}";

		return "{$pt} {$pb}";
	}
}
