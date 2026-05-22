<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Tailwind safelist — dynamic class names used in render():
// grid-cols-1 md:grid-cols-2 lg:grid-cols-3

class Hello_Child_News_List extends \Elementor\Widget_Base {

	use Hello_Child_Padding_Controls;

	public function get_name(): string {
		return 'raven-news-list';
	}

	public function get_title(): string {
		return esc_html__( 'News List', 'raven' );
	}

	public function get_icon(): string {
		return 'eicon-posts-grid';
	}

	public function get_categories(): array {
		return [ 'raven' ];
	}

	public function get_keywords(): array {
		return [ 'news', 'posts', 'events', 'list', 'cards', 'grid' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return false;
	}

	private function get_post_options(): array {
		return wp_list_pluck(
			get_posts( [
				'numberposts' => 200,
				'post_status' => 'publish',
				'post_type'   => [ 'post', 'event' ],
				'orderby'     => 'title',
				'order'       => 'ASC',
			] ),
			'post_title',
			'ID'
		);
	}

	protected function register_controls(): void {

		$this->start_controls_section( 'section_options', [
			'label' => esc_html__( 'Options', 'raven' ),
		] );

		$this->add_control( 'id', [
			'label'       => esc_html__( 'ID', 'raven' ),
			'type'        => \Elementor\Controls_Manager::TEXT,
			'description' => esc_html__( 'Optional anchor ID, must be unique on the page.', 'raven' ),
		] );

		$this->add_control( 'source', [
			'label'   => esc_html__( 'Source', 'raven' ),
			'type'    => \Elementor\Controls_Manager::SELECT,
			'options' => [
				'all'      => esc_html__( 'All Posts', 'raven' ),
				'category' => esc_html__( 'Specific Category', 'raven' ),
				'events'   => esc_html__( 'Events', 'raven' ),
				'picked'   => esc_html__( 'Hand-picked', 'raven' ),
			],
			'default' => 'all',
		] );

		$categories = [];
		foreach ( get_categories( [ 'hide_empty' => false ] ) as $cat ) {
			$categories[ $cat->term_id ] = $cat->name;
		}

		$this->add_control( 'category_id', [
			'label'     => esc_html__( 'Category', 'raven' ),
			'type'      => \Elementor\Controls_Manager::SELECT,
			'options'   => $categories,
			'condition' => [ 'source' => 'category' ],
		] );

		$this->add_control( 'post_count', [
			'label'     => esc_html__( 'How many posts', 'raven' ),
			'type'      => \Elementor\Controls_Manager::NUMBER,
			'min'       => 1,
			'max'       => 9,
			'default'   => 3,
			'condition' => [ 'source' => [ 'all', 'category', 'events' ] ],
		] );

		$post_options = $this->get_post_options();

		$this->add_control( 'post_ids', [
			'label'       => esc_html__( 'Posts', 'raven' ),
			'description' => esc_html__( 'Maximum 9 posts.', 'raven' ),
			'type'        => \Elementor\Controls_Manager::SELECT2,
			'multiple'    => true,
			'options'     => $post_options,
			'condition'   => [ 'source' => 'picked' ],
		] );

		$this->add_control( 'sticky_divider', [
			'type' => \Elementor\Controls_Manager::DIVIDER,
		] );

		$this->add_control( 'sticky_post', [
			'label'       => esc_html__( 'Sticky Post', 'raven' ),
			'description' => esc_html__( 'Always shown first. Will not appear twice.', 'raven' ),
			'type'        => \Elementor\Controls_Manager::SELECT,
			'options'     => [ '' => esc_html__( 'None', 'raven' ) ] + $post_options,
		] );

		$this->add_control( 'btn_divider', [
			'type' => \Elementor\Controls_Manager::DIVIDER,
		] );

		$this->add_control( 'btn_label', [
			'label'       => esc_html__( 'Button Label', 'raven' ),
			'type'        => \Elementor\Controls_Manager::TEXT,
			'description' => esc_html__( 'Leave empty to hide the button.', 'raven' ),
			'placeholder' => esc_html__( 'e.g. See all events', 'raven' ),
		] );

		$this->add_control( 'btn_url', [
			'label'       => esc_html__( 'Button URL', 'raven' ),
			'type'        => \Elementor\Controls_Manager::URL,
			'description' => esc_html__( 'Defaults to the posts archive if left empty.', 'raven' ),
			'condition'   => [ 'btn_label!' => '' ],
		] );

		$this->add_control( 'spacing_divider', [
			'type' => \Elementor\Controls_Manager::DIVIDER,
		] );

		$this->register_padding_controls( 'spacing' );

		$this->end_controls_section();
	}

	private function render_card( \WP_Post $post ): void {
		$id         = $post->ID;
		$thumb_id   = get_post_thumbnail_id( $id );
		$date_label = raven_get_event_date_label( $id );

		$image = $thumb_id
			? wp_get_attachment_image( $thumb_id, 'raven-md', false, [
				'class'   => 'w-full h-full object-cover',
				'loading' => 'lazy',
			] )
			: '';
		?>
		<a href="<?php echo esc_url( get_permalink( $id ) ); ?>" class="flex flex-col shadow-[0px_4px_14.1px_rgba(0,0,0,0.15)] rounded-sm overflow-hidden">
			<div class="aspect-[4/3] overflow-hidden bg-ship">
				<?php echo $image; ?>
			</div>
			<div class="bg-white text-night px-5 py-[17px] flex flex-col grow space-y-1.5">
				<?php if ( $date_label ) : ?>
				<span class="text-subtitle-sm"><?php echo $date_label; ?></span>
				<?php endif; ?>
				<div class="-mt-[4px] flex items-center gap-[11px] text-heading-xs">
					<span><?php echo esc_html( get_the_title( $id ) ); ?></span>
					<span class="shrink-0 w-[1em] h-[1em] inline-flex items-center justify-center">
						<?php echo raven_get_icon( 'arrow-right' ); ?>
					</span>
				</div>
			</div>
		</a>
		<?php
	}

	protected function render(): void {
		$settings  = $this->get_settings_for_display();
		$source    = $settings['source'] ?? 'all';
		$count     = min( (int) ( $settings['post_count'] ?? 3 ), 9 );
		$sticky_id = (int) ( $settings['sticky_post'] ?? 0 );
		$padding   = $this->get_padding_classes( $settings, 'spacing' );
		$id_attr   = ! empty( $settings['id'] )
			? ' id="' . esc_attr( $settings['id'] ) . '"'
			: '';
		$btn_label = trim( $settings['btn_label'] ?? '' );

		// ── Build post list ──────────────────────────────────────────────────

		$posts = [];

		if ( 'picked' === $source ) {
			$picked_ids = array_values( array_filter( array_map( 'intval', (array) ( $settings['post_ids'] ?? [] ) ) ) );
			if ( $sticky_id ) {
				$picked_ids = array_values( array_diff( $picked_ids, [ $sticky_id ] ) );
			}
			$picked_ids = array_slice( $picked_ids, 0, 9 );
			if ( $picked_ids ) {
				$posts = get_posts( [
					'post__in'    => $picked_ids,
					'orderby'     => 'post__in',
					'numberposts' => count( $picked_ids ),
					'post_status' => 'publish',
					'post_type'   => 'any',
				] );
			}
		} elseif ( 'events' === $source ) {
			$regular_count = $sticky_id ? max( $count - 1, 0 ) : $count;
			if ( $regular_count > 0 ) {
				// Three queries to preserve ordering: ongoing → upcoming → recurring.
				$today = date( 'Ymd' );
				$base  = [
					'post_type'    => 'event',
					'post_status'  => 'publish',
					'numberposts'  => $regular_count,
					'post__not_in' => $sticky_id ? [ $sticky_id ] : [],
				];

				// 1. Ongoing: started in the past, end date is today or future.
				$ongoing = get_posts( array_merge( $base, [
					'meta_query' => [
						'relation' => 'AND',
						[ 'key' => 'event_date',     'value' => $today, 'compare' => '<'  ],
						[ 'key' => 'event_end_date', 'value' => $today, 'compare' => '>=' ],
					],
				] ) );

				$n_upcoming = $regular_count - count( $ongoing );

				// 2. Upcoming: start date is today or future.
				$upcoming = $n_upcoming > 0 ? get_posts( array_merge( $base, [
					'numberposts' => $n_upcoming,
					'meta_key'    => 'event_date',
					'orderby'     => 'meta_value',
					'order'       => 'ASC',
					'meta_query'  => [
						[ 'key' => 'event_date', 'value' => $today, 'compare' => '>=' ],
					],
				] ) ) : [];

				$n_recurring = $n_upcoming - count( $upcoming );

				// 3. Recurring / custom-date: no event_date set.
				$recurring = $n_recurring > 0 ? get_posts( array_merge( $base, [
					'numberposts' => $n_recurring,
					'meta_query'  => [
						'relation' => 'OR',
						[ 'key' => 'event_date', 'compare' => 'NOT EXISTS' ],
						[ 'key' => 'event_date', 'value'   => '',     'compare' => '=' ],
					],
				] ) ) : [];

				$posts = array_merge( $ongoing, $upcoming, $recurring );
			}
		} else {
			$regular_count = $sticky_id ? max( $count - 1, 0 ) : $count;
			if ( $regular_count > 0 ) {
				$args = [
					'post_type'    => 'post',
					'post_status'  => 'publish',
					'numberposts'  => $regular_count,
					'post__not_in' => $sticky_id ? [ $sticky_id ] : [],
				];
				if ( 'category' === $source && ! empty( $settings['category_id'] ) ) {
					$args['cat'] = (int) $settings['category_id'];
				}
				$posts = get_posts( $args );
			}
		}

		// Prepend sticky post
		if ( $sticky_id ) {
			$sticky_post = get_post( $sticky_id );
			if ( $sticky_post && 'publish' === $sticky_post->post_status ) {
				array_unshift( $posts, $sticky_post );
			}
		}

		if ( ! $posts ) {
			return;
		}

		// ── Button URL ───────────────────────────────────────────────────────

		$btn_href   = '';
		$btn_target = '';
		$btn_rel    = '';

		if ( $btn_label ) {
			$btn_url_data = $settings['btn_url'] ?? [];
			if ( ! empty( $btn_url_data['url'] ) ) {
				$btn_href = esc_url( $btn_url_data['url'] );
				if ( ! empty( $btn_url_data['is_external'] ) ) {
					$btn_target = ' target="_blank"';
					$btn_rel    = ' rel="noopener noreferrer"';
				}
			} else {
				$page_for_posts = get_option( 'page_for_posts' );
				$btn_href = $page_for_posts
					? esc_url( get_permalink( $page_for_posts ) )
					: esc_url( home_url( '/' ) );
			}
		}
		?>
		<div class="news-list container <?php echo esc_attr( $padding ); ?>"<?php echo $id_attr; ?>>

			<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-[var(--gutter)]">
				<?php foreach ( $posts as $post ) : ?>
					<?php $this->render_card( $post ); ?>
				<?php endforeach; ?>
			</div>

			<?php if ( $btn_label && $btn_href ) : ?>
			<div class="mt-5xl flex justify-center">
				<a href="<?php echo $btn_href; ?>" class="btn btn-primary btn-md"<?php echo $btn_target . $btn_rel; ?>>
					<span class="shrink-0 w-[1em] h-[1em] inline-flex items-center justify-center [&_svg]:w-5 [&_svg]:h-5"><?php echo raven_get_icon( 'plus' ); ?></span>
					<?php echo esc_html( $btn_label ); ?>
				</a>
			</div>
			<?php endif; ?>

		</div>
		<?php
	}
}
