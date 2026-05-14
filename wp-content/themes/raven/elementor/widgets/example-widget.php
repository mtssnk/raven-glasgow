<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Hello_Child_Example_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'hello_child_example';
	}

	public function get_title() {
		return esc_html__( 'Example Widget', 'raven' );
	}

	public function get_icon() {
		return 'eicon-code';
	}

	public function get_categories() {
		return [ 'general' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Content', 'raven' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'title',
			[
				'label'   => esc_html__( 'Title', 'raven' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Hello World', 'raven' ),
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="hello-child-example">
			<h2><?php echo esc_html( $settings['title'] ); ?></h2>
		</div>
		<?php
	}
}
