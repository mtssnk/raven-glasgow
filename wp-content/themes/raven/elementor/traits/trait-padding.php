<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Tailwind safelist — dynamic class names used in get_padding_classes():
// pt-0 pb-0
// pt-md pb-md pt-lg pb-lg pt-2xl pb-2xl pt-3xl pb-3xl pt-4xl pb-4xl
// pt-5xl pb-5xl pt-6xl pb-6xl pt-7xl pb-7xl pt-8xl pb-8xl
// pt-9xl pb-9xl pt-10xl pb-10xl pt-11xl pb-11xl pt-12xl pb-12xl

trait Hello_Child_Padding_Controls {

	protected function register_padding_controls( string $prefix = 'spacing', string $default_top = '10xl', string $default_bottom = '10xl' ): void {
		$options = [
			'none' => esc_html__( 'None', 'raven' ),
			'md'   => esc_html__( 'MD  (13–15px)', 'raven' ),
			'lg'   => esc_html__( 'LG  (18–20px)', 'raven' ),
			'2xl'  => esc_html__( '2XL (22–25px)', 'raven' ),
			'3xl'  => esc_html__( '3XL (27–30px)', 'raven' ),
			'4xl'  => esc_html__( '4XL (31–35px)', 'raven' ),
			'5xl'  => esc_html__( '5XL (36–40px)', 'raven' ),
			'6xl'  => esc_html__( '6XL (45–50px)', 'raven' ),
			'7xl'  => esc_html__( '7XL (54–60px)', 'raven' ),
			'8xl'  => esc_html__( '8XL (63–70px)', 'raven' ),
			'9xl'  => esc_html__( '9XL (72–80px)', 'raven' ),
			'10xl' => esc_html__( '10XL (90–100px)', 'raven' ),
			'11xl' => esc_html__( '11XL (108–120px)', 'raven' ),
			'12xl' => esc_html__( '12XL (135–150px)', 'raven' ),
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
		$top    = $settings[ "{$prefix}_top" ] ?? '10xl';
		$bottom = $settings[ "{$prefix}_bottom" ] ?? '10xl';

		$pt = 'none' === $top    ? 'pt-0' : "pt-{$top}";
		$pb = 'none' === $bottom ? 'pb-0' : "pb-{$bottom}";

		return "{$pt} {$pb}";
	}
}
