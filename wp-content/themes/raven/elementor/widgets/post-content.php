<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Hello_Child_Widget_Post_Content extends \Elementor\Widget_Base {

	public function get_name(): string {
		return 'theme-post-content';
	}

	public function get_title(): string {
		return esc_html__( 'Post Content', 'raven' );
	}

	public function get_icon(): string {
		return 'eicon-post-content';
	}

	public function get_categories(): array {
		return [ 'theme-elements-single' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return false;
	}

	protected function register_controls(): void {}

	protected function render(): void {
		?>
		<div class="container">
			<div class="max-w-[33em] mx-auto text-lg freak-mce">
				<?php the_content(); ?>
			</div>
		</div>
		<?php
	}
}
