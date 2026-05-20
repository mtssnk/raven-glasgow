<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Hello_Child_Two_Column_Media_Text extends \Elementor\Widget_Base {

	use Hello_Child_Button_Controls;
	use Hello_Child_Padding_Controls;

	public function get_name() {
		return 'hello_child_two_column_media_text';
	}

	public function get_title() {
		return esc_html__( 'Two Column Media and Text', 'raven' );
	}

	public function get_icon() {
		return 'eicon-columns';
	}

	public function get_categories() {
		return [ 'general' ];
	}

	public function get_keywords() {
		return [ 'media', 'text', 'image', 'video', 'two column', 'split' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return false;
	}

	protected function register_controls() {

		// ── Layout ───────────────────────────────────────────────────────────

		$this->start_controls_section( 'section_layout', [
			'label' => esc_html__( 'Options', 'raven' ),
		] );


		$this->add_control( 'id', [
			'label' => esc_html__( 'ID', 'raven' ),
			'type' => \Elementor\Controls_Manager::TEXT,
			'description' => esc_html__( 'Add an optional ID (no spaces) for anchor links, must be unique on the page.', 'raven' ),
		] );

		$this->add_control( 'media_position', [
			'label'   => esc_html__( 'Media Position', 'raven' ),
			'type'    => \Elementor\Controls_Manager::CHOOSE,
			'options' => [
				'left'  => [
					'title' => esc_html__( 'Left', 'raven' ),
					'icon'  => 'eicon-h-align-left',
				],
				'right' => [
					'title' => esc_html__( 'Right', 'raven' ),
					'icon'  => 'eicon-h-align-right',
				],
			],
			'default' => 'left',
			'toggle'  => false,
		] );

		$this->add_control( 'spacing_divider', [
			'type' => \Elementor\Controls_Manager::DIVIDER,
		] );

		$this->register_padding_controls( 'spacing' );

		$this->end_controls_section();

		// ── Media ─────────────────────────────────────────────────────────────

		$this->start_controls_section( 'section_media', [
			'label' => esc_html__( 'Media', 'raven' ),
		] );

		$this->add_control( 'media_type', [
			'label'   => esc_html__( 'Type', 'raven' ),
			'type'    => \Elementor\Controls_Manager::SELECT,
			'options' => [
				'image' => esc_html__( 'Image', 'raven' ),
				'video' => esc_html__( 'Video', 'raven' ),
			],
			'default' => 'image',
		] );
		
		$this->add_control( 'media_aspect_ratio', [
			'label'   => esc_html__( 'Aspect Ratio', 'raven' ),
			'type'    => \Elementor\Controls_Manager::SELECT,
			'options' => [
				'auto' => esc_html__( 'Auto', 'raven' ),
				'square' => esc_html__( 'Square', 'raven' ),
				'video' => esc_html__( 'Video', 'raven' ),
				'4-3' => esc_html__( '4:3', 'raven' ),
				'4-5' => esc_html__( '4:5', 'raven' ),
			],
			'default' => 'auto',
		] );

		$this->add_control( 'image', [
			'label'     => esc_html__( 'Image', 'raven' ),
			'type'      => \Elementor\Controls_Manager::MEDIA,
			'default'   => [ 'url' => \Elementor\Utils::get_placeholder_image_src() ],
			'condition' => [ 'media_type' => 'image' ],
		] );

		$this->add_control( 'video_url', [
			'label'       => esc_html__( 'Video URL', 'raven' ),
			'description' => esc_html__( 'Paste a YouTube or Vimeo URL.', 'raven' ),
			'type'        => \Elementor\Controls_Manager::URL,
			'placeholder' => 'https://vimeo.com/... or https://youtube.com/...',
			'condition'   => [ 'media_type' => 'video' ],
		] );

		$this->end_controls_section();

		// ── Text ──────────────────────────────────────────────────────────────

		$this->start_controls_section( 'section_text', [
			'label' => esc_html__( 'Text', 'raven' ),
		] );

		$this->add_control( 'text_valign', [
			'label'   => esc_html__( 'Vertical Alignment', 'raven' ),
			'type'    => \Elementor\Controls_Manager::CHOOSE,
			'options' => [
				'self-start'  => [
					'title' => esc_html__( 'Top', 'raven' ),
					'icon'  => 'eicon-v-align-top',
				],
				'self-center' => [
					'title' => esc_html__( 'Centre', 'raven' ),
					'icon'  => 'eicon-v-align-middle',
				],
			],
			'default' => 'self-start',
			'toggle'  => false,
		] );

		$this->add_control( 'heading', [
			'label'   => esc_html__( 'Heading', 'raven' ),
			'type'    => \Elementor\Controls_Manager::TEXT,
			'default' => esc_html__( 'Your Heading Here', 'raven' ),
		] );

		$this->add_control( 'heading_tag', [
			'label'   => esc_html__( 'HTML Tag', 'raven' ),
			'type'    => \Elementor\Controls_Manager::SELECT,
			'options' => [
				'h1' => 'H1', 'h2' => 'H2', 'h3' => 'H3',
				'h4' => 'H4', 'h5' => 'H5', 'h6' => 'H6',
			],
			'default' => 'h2',
		] );

		$this->add_control( 'body', [
			'label'   => esc_html__( 'Body Copy', 'raven' ),
			'type'    => \Elementor\Controls_Manager::WYSIWYG,
			'default' => esc_html__( 'Add your body copy here.', 'raven' ),
		] );

		$this->add_control( 'btn_divider', [
			'type'      => \Elementor\Controls_Manager::DIVIDER,
		] );

		$this->register_button_controls( 'btn' );

		$this->end_controls_section();
	}

	private function get_aspect_ratio_class( string $ratio ): string {
		return [
			'square' => 'aspect-square',
			'video'  => 'aspect-video',
			'4-3'    => 'aspect-[4/3]',
			'4-5'    => 'aspect-[4/5]',
		][ $ratio ] ?? '';
	}

	private function get_video_embed_url( string $url ): string {
		// YouTube: youtube.com/watch?v=ID or youtu.be/ID
		if ( preg_match( '/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]{11})/', $url, $m ) ) {
			return 'https://www.youtube.com/embed/' . $m[1];
		}
		// Vimeo: vimeo.com/ID or vimeo.com/video/ID
		if ( preg_match( '/vimeo\.com\/(?:video\/)?(\d+)/', $url, $m ) ) {
			return 'https://player.vimeo.com/video/' . $m[1];
		}
		return '';
	}

	private function render_media( array $settings ): void {
		$ratio_class = $this->get_aspect_ratio_class( $settings['media_aspect_ratio'] ?? 'auto' );

		if ( 'image' === $settings['media_type'] ) {
			$id = $settings['image']['id'] ?? 0;
			if ( ! $id ) {
				return;
			}
			$classes = $ratio_class
				? "w-full object-cover {$ratio_class}"
				: 'w-full h-auto';
			echo wp_get_attachment_image( $id, 'raven-lg', false, [ 'class' => $classes ] );
			return;
		}

		$embed_url = $this->get_video_embed_url( $settings['video_url']['url'] ?? '' );
		if ( $embed_url ) {
			// Video always needs a ratio — fall back to 16:9 when set to auto.
			$video_ratio = $ratio_class ?: 'aspect-video';
			printf(
				'<div class="w-full overflow-hidden %s">
					<iframe src="%s" class="w-full h-full" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
				</div>',
				esc_attr( $video_ratio ),
				esc_url( $embed_url )
			);
		}
	}

	protected function render(): void {
		$settings       = $this->get_settings_for_display();
		$media_reversed = 'right' === $settings['media_position'];
		$media_order    = $media_reversed ? 'md:order-last' : '';
		$text_order     = $media_reversed ? 'md:order-first' : '';
		$text_valign    = $settings['text_valign'] ?? 'self-start';
		$tag            = \Elementor\Utils::validate_html_tag( $settings['heading_tag'] );
		$padding        = $this->get_padding_classes( $settings, 'spacing' );
		?>
		<div class="section-two-column-media-text bg-night <?php echo esc_attr( $padding ); ?>" id="<?php echo esc_attr( $settings['id'] ?? '' ); ?>">
			<div class="grid grid-cols-12 container">

				<div class="col-span-12 md:col-span-6 <?php echo esc_attr( $media_order ); ?>">
					<?php $this->render_media( $settings ); ?>
				</div>

				<div class="col-span-12 md:col-span-6 <?php echo esc_attr( $text_order ); ?> <?php echo esc_attr( $text_valign ); ?>">
					<div class="space-y-5xl max-w-[29em] mx-auto">
						<<?php echo $tag; ?> class="text-heading-xl"><?php echo raven_format_heading( $settings['heading'] ); ?></<?php echo $tag; ?>>

						<div class="text-body-lg text-cloud"><?php echo wp_kses_post( $settings['body'] ); ?></div>

						<?php echo $this->render_button( $settings, 'btn' ); ?>
					</div>
				</div>

			</div>
		</div>
		<?php
	}
}
