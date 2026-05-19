<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Hello_Child_Widget_Homepage_Hero extends \Elementor\Widget_Base {

	use Hello_Child_Button_Controls;

	public function get_name(): string {
		return 'homepage-hero';
	}

	public function get_title(): string {
		return esc_html__( 'Homepage Hero', 'raven' );
	}

	public function get_icon(): string {
		return 'eicon-banner';
	}

	public function get_categories(): array {
		return [ 'general' ];
	}

	public function get_keywords(): array {
		return [ 'homepage', 'hero', 'raven', 'banner', 'slideshow' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return false;
	}

	public function get_script_depends(): array {
		return [ 'raven-homepage-hero' ];
	}

	// ── Controls ─────────────────────────────────────────────────────────────

	protected function register_controls(): void {

		// ── Background ───────────────────────────────────────────────────────

		$this->start_controls_section( 'section_background', [
			'label' => esc_html__( 'Background', 'raven' ),
			'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'background_type', [
			'label'   => esc_html__( 'Type', 'raven' ),
			'type'    => \Elementor\Controls_Manager::SELECT,
			'options' => [
				'image'     => esc_html__( 'Image', 'raven' ),
				'video'     => esc_html__( 'Video', 'raven' ),
				'slideshow' => esc_html__( 'Slideshow', 'raven' ),
			],
			'default' => 'image',
		] );

		// Image

		$this->add_control( 'bg_image', [
			'label'     => esc_html__( 'Image', 'raven' ),
			'type'      => \Elementor\Controls_Manager::MEDIA,
			'condition' => [ 'background_type' => 'image' ],
		] );

		// Video

		$this->add_control( 'video_source', [
			'label'     => esc_html__( 'Source', 'raven' ),
			'type'      => \Elementor\Controls_Manager::SELECT,
			'options'   => [
				'url'  => esc_html__( 'URL (YouTube / Vimeo)', 'raven' ),
				'file' => esc_html__( 'Self-hosted', 'raven' ),
			],
			'default'   => 'url',
			'condition' => [ 'background_type' => 'video' ],
		] );

		$this->add_control( 'video_url', [
			'label'       => esc_html__( 'Video URL', 'raven' ),
			'type'        => \Elementor\Controls_Manager::TEXT,
			'description' => esc_html__( 'Paste a YouTube or Vimeo page URL.', 'raven' ),
			'condition'   => [ 'background_type' => 'video', 'video_source' => 'url' ],
		] );

		$this->add_control( 'video_file', [
			'label'       => esc_html__( 'Video File', 'raven' ),
			'type'        => \Elementor\Controls_Manager::MEDIA,
			'media_types' => [ 'video' ],
			'description' => esc_html__( 'MP4 recommended. Keep under 20MB for performance.', 'raven' ),
			'condition'   => [ 'background_type' => 'video', 'video_source' => 'file' ],
		] );

		// Slideshow

		$slides_repeater = new \Elementor\Repeater();

		$slides_repeater->add_control( 'slide_label', [
			'label'       => esc_html__( 'Label (optional)', 'raven' ),
			'type'        => \Elementor\Controls_Manager::TEXT,
			'placeholder' => esc_html__( 'e.g. Slide 1', 'raven' ),
		] );

		$slides_repeater->add_control( 'slide_image', [
			'label' => esc_html__( 'Image', 'raven' ),
			'type'  => \Elementor\Controls_Manager::MEDIA,
		] );

		$this->add_control( 'slides', [
			'label'       => esc_html__( 'Slides', 'raven' ),
			'type'        => \Elementor\Controls_Manager::REPEATER,
			'fields'      => $slides_repeater->get_controls(),
			'title_field' => '{{{ slide_label || "Slide" }}}',
			'condition'   => [ 'background_type' => 'slideshow' ],
		] );

		$this->add_control( 'slideshow_duration', [
			'label'     => esc_html__( 'Duration (ms)', 'raven' ),
			'type'      => \Elementor\Controls_Manager::NUMBER,
			'min'       => 1000,
			'max'       => 15000,
			'step'      => 500,
			'default'   => 5000,
			'condition' => [ 'background_type' => 'slideshow' ],
		] );

		$this->add_control( 'slideshow_loop', [
			'label'     => esc_html__( 'Loop', 'raven' ),
			'type'      => \Elementor\Controls_Manager::SWITCHER,
			'default'   => 'yes',
			'condition' => [ 'background_type' => 'slideshow' ],
		] );

		$this->add_control( 'slideshow_ken_burns', [
			'label'     => esc_html__( 'Ken Burns Effect', 'raven' ),
			'type'      => \Elementor\Controls_Manager::SWITCHER,
			'default'   => 'yes',
			'condition' => [ 'background_type' => 'slideshow' ],
		] );

		$this->end_controls_section();

		// ── Navigation ───────────────────────────────────────────────────────

		$this->start_controls_section( 'section_nav', [
			'label' => esc_html__( 'Navigation', 'raven' ),
			'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
		] );

		$nav_repeater = new \Elementor\Repeater();

		$nav_repeater->add_control( 'nav_label', [
			'label'   => esc_html__( 'Label', 'raven' ),
			'type'    => \Elementor\Controls_Manager::TEXT,
			'default' => esc_html__( 'Menu Item', 'raven' ),
		] );

		$nav_repeater->add_control( 'nav_url', [
			'label' => esc_html__( 'URL', 'raven' ),
			'type'  => \Elementor\Controls_Manager::URL,
		] );

		$this->add_control( 'nav_links', [
			'label'       => esc_html__( 'Links', 'raven' ),
			'type'        => \Elementor\Controls_Manager::REPEATER,
			'fields'      => $nav_repeater->get_controls(),
			'title_field' => '{{{ nav_label }}}',
		] );

		$this->end_controls_section();

		// ── Content ──────────────────────────────────────────────────────────

		$this->start_controls_section( 'section_content', [
			'label' => esc_html__( 'Content', 'raven' ),
			'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'heading', [
			'label'       => esc_html__( 'Heading', 'raven' ),
			'type'        => \Elementor\Controls_Manager::TEXTAREA,
			'description' => esc_html__( 'Wrap words in {{double braces}} for fire colour.', 'raven' ),
			'rows'        => 3,
		] );

		$this->add_control( 'heading_tag', [
			'label'   => esc_html__( 'HTML Tag', 'raven' ),
			'type'    => \Elementor\Controls_Manager::SELECT,
			'options' => [
				'h1' => 'H1',
				'h2' => 'H2',
				'h3' => 'H3',
			],
			'default' => 'h1',
		] );

		$this->add_control( 'btn1_divider', [
			'type' => \Elementor\Controls_Manager::DIVIDER,
		] );

		$this->register_button_controls( 'btn1' );

		$this->add_control( 'btn2_divider', [
			'type' => \Elementor\Controls_Manager::DIVIDER,
		] );

		$this->register_button_controls( 'btn2' );

		$this->end_controls_section();

		// ── Style: Overlays ──────────────────────────────────────────────────

		$this->start_controls_section( 'section_overlays', [
			'label' => esc_html__( 'Overlays', 'raven' ),
			'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'overlay_left_heading', [
			'label' => esc_html__( 'Left Overlay', 'raven' ),
			'type'  => \Elementor\Controls_Manager::HEADING,
		] );

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name'     => 'overlay_left',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .hero-overlay-left',
			]
		);

		$this->add_control( 'overlay_bottom_divider', [
			'type' => \Elementor\Controls_Manager::DIVIDER,
		] );

		$this->add_control( 'overlay_bottom_heading', [
			'label' => esc_html__( 'Bottom Overlay', 'raven' ),
			'type'  => \Elementor\Controls_Manager::HEADING,
		] );

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name'     => 'overlay_bottom',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .hero-overlay-bottom',
			]
		);

		$this->end_controls_section();
	}

	// ── Helpers ──────────────────────────────────────────────────────────────

	private function parse_video_url( string $url ): string {
		if ( preg_match( '/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $url, $m ) ) {
			$id = $m[1];
			return "https://www.youtube.com/embed/{$id}?autoplay=1&mute=1&loop=1&playlist={$id}&controls=0&modestbranding=1&playsinline=1";
		}
		if ( preg_match( '/vimeo\.com\/(\d+)/', $url, $m ) ) {
			$id = $m[1];
			return "https://player.vimeo.com/video/{$id}?autoplay=1&muted=1&loop=1&background=1&autopause=0";
		}
		return '';
	}

	// ── Render ───────────────────────────────────────────────────────────────

	protected function render(): void {
		$settings = $this->get_settings_for_display();

		$bg_type      = $settings['background_type'] ?? 'image';
		$heading_text = trim( $settings['heading'] ?? '' );
		$heading_tag  = \Elementor\Utils::validate_html_tag( $settings['heading_tag'] ?? 'h1' );
		$nav_links    = (array) ( $settings['nav_links'] ?? [] );
		$slides       = (array) ( $settings['slides'] ?? [] );


		$current_path = strtok( $_SERVER['REQUEST_URI'] ?? '/', '?' );
		?>
		<div class="hero relative overflow-hidden min-h-dvh w-full flex items-stretch">

			<?php // ── Background ─────────────────────────────────────────── ?>

			<div class="absolute h-full w-[65%] top-0 right-0 z-0 overflow-hidden">

				<?php if ( 'image' === $bg_type ) :
					$img_id = (int) ( $settings['bg_image']['id'] ?? 0 );
					if ( $img_id ) :
						echo wp_get_attachment_image( $img_id, 'raven-lg', false, [
							'class' => 'absolute inset-0 w-full h-full object-cover',
						] );
					endif;

				elseif ( 'video' === $bg_type ) :
					$video_source = $settings['video_source'] ?? 'url';

					if ( 'file' === $video_source ) :
						$file_url = $settings['video_file']['url'] ?? '';
						if ( $file_url ) : ?>
							<video class="absolute inset-0 w-full h-full object-cover" autoplay muted playsinline loop>
								<source src="<?php echo esc_url( $file_url ); ?>" type="video/mp4">
							</video>
						<?php endif;

					else :
						$video_url = trim( $settings['video_url'] ?? '' );
						$embed_src = $video_url ? $this->parse_video_url( $video_url ) : '';
						if ( $embed_src ) : ?>
							<div class="absolute inset-0 overflow-hidden pointer-events-none">
								<iframe
									class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-screen h-[56.25vw] min-h-full min-w-[177.78vh]"
									src="<?php echo esc_url( $embed_src ); ?>"
									frameborder="0"
									allow="autoplay; encrypted-media"
									title="<?php esc_attr_e( 'Background video', 'raven' ); ?>"
								></iframe>
							</div>
						<?php endif;
					endif;

				elseif ( 'slideshow' === $bg_type && $slides ) :
					$duration  = (int) ( $settings['slideshow_duration'] ?? 5000 );
					$loop      = 'yes' !== ( $settings['slideshow_loop'] ?? 'yes' ) ? 'false' : 'true';
					$ken_burns = 'yes' === ( $settings['slideshow_ken_burns'] ?? 'yes' ) ? 'true' : 'false';
					?>
					<div
						class="hero-slideshow absolute inset-0"
						data-duration="<?php echo esc_attr( $duration ); ?>"
						data-loop="<?php echo esc_attr( $loop ); ?>"
						data-ken-burns="<?php echo esc_attr( $ken_burns ); ?>"
					>
						<?php foreach ( $slides as $slide ) :
							$slide_img_id = (int) ( $slide['slide_image']['id'] ?? 0 );
							if ( ! $slide_img_id ) continue;
							?>
							<div class="hero-slide">
								<?php echo wp_get_attachment_image( $slide_img_id, 'raven-lg', false, [
									'class' => 'w-full h-full object-cover',
								] ); ?>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<?php // ── Overlays ───────────────────────────────────────────── ?>
	
				<div class="hero-overlay-left absolute w-full h-full z-1"></div>
				<div class="hero-overlay-bottom absolute w-full h-full z-1"></div>
			</div>


			<?php // ── Foreground ─────────────────────────────────────────── ?>

			<div class="container relative z-2 py-xl flex items-stretch flex-col">

				<div class="grid gap-0 h-full">

					<?php // ── Nav panel ───────────────────────────────────── ?>
					<?php if ( $nav_links ) : ?>
						<div class="col-span-4 hero-nav">
							<div class="flex items-stretch js-homepage-hero-nav opacity-0">
								<figure class="js-homepage-hero-logo-wrapper [&_svg]:h-full [&_svg]:w-auto [&_svg]:shrink-0 [&_svg]:aspect-39/244">
									<?php echo raven_get_icon( 'logo-vertical' ); ?>
								</figure>
								<nav class="flex flex-col gap-md pl-[10%]">
								<?php foreach ( $nav_links as $link ) :
									$label     = esc_html( $link['nav_label'] ?? '' );
									$href      = $link['nav_url']['url'] ?? '';
									$link_path = parse_url( $href, PHP_URL_PATH );
									$is_active = $link_path && rtrim( $link_path, '/' ) === rtrim( $current_path, '/' );
									$external  = ! empty( $link['nav_url']['is_external'] );

									if ( ! $label || ! $href ) continue;
									?>
									<a
										href="<?php echo esc_url( $href ); ?>"
										class="hero-nav__link text-subtitle-md<?php echo $is_active ? ' text-fire font-bold' : ' font-normal'; ?>"
										<?php echo $external ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>
									><?php echo $label; ?></a>
								<?php endforeach; ?>
								</nav>
							</div>
						</div>
					<?php endif; ?>

					<?php // ── Heading + CTAs ──────────────────────────────── ?>

					<div class="col-span-8 px-xl">
						<div class="max-w-[800px] h-full mx-auto flex flex-col justify-between">
							<div class="pb-2xl">
								<?php if ( $heading_text ) : ?>
									<<?php echo $heading_tag; ?> class="hero-heading text-heading-2xl max-w-[5em]">
										<?php echo raven_format_heading( $heading_text ); ?>
									</<?php echo $heading_tag; ?>>
								<?php endif; ?>
							</div>

							<div>
								<hr class="border-t-2 border-birch/25" />
								<div class="flex flex-row gap-2xl justify-between pt-2xl">								
									<?php
									$btn1 = $this->render_button( $settings, 'btn1' );
									$btn2 = $this->render_button( $settings, 'btn2' );
									if ( $btn1 || $btn2 ) :
									?>
									<div class="flex flex-col flex-wrap gap-lg">
										<?php echo $btn1; echo $btn2; ?>
									</div>
									<?php endif; ?>
									<?php // ── Address ─────────────────────────────────────────── ?>
									<div class="flex items-center shrink-0	">
										<a target="_blank" rel="noopener noreferrer" href="https://maps.app.goo.gl/crP6djxx2x9er6Z76" class="hero-address text-subtitle-xl text-right flex flex-col leading-[1.3]">
											<span class="font-bold">81-85</span> 
											<span class="font-normal">Renfield Street</span> 
											<span class="font-normal">Glasgow</span> 
											<span class="font-bold">G2 1HH<span>
										</a>
									</div>								
								</div>
							</div>
						</div>
						
					</div>

				</div>

			</div>

		</div>
		<?php
	}
}
