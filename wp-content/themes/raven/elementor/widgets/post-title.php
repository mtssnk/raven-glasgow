<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Hello_Child_Widget_Post_Title extends \ElementorPro\Modules\ThemeBuilder\Widgets\Post_Title {

	protected function register_controls(): void {
		parent::register_controls();

		// Remove Style tab controls to prevent Elementor generating typography
		// CSS that would override the theme's heading utilities.
		foreach ( [
			'section_title_style',
			'title_color',
			'title_typography',
			'title_text_shadow',
			'title_blend_mode',
		] as $control ) {
			$this->remove_control( $control );
		}
	}

	public function render(): void {
		if ( ! $this->should_show_page_title() ) {
			return;
		}

		$settings = $this->get_settings_for_display();

		if ( '' === $settings['title'] ) {
			return;
		}

		$tag = \Elementor\Utils::validate_html_tag( $settings['header_size'] );
		?>
		<<?php echo $tag; ?> class="py-lg container"><?php echo raven_format_heading( $settings['title'] ); ?></<?php echo $tag; ?>>
		<?php
	}
}
