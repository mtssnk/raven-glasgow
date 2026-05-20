<?php get_header(); ?>

<section class="pt-10xl pb-10xl">
	<div class="container">

		<div class="grid grid-cols-12">
			<div class="col-span-12">
				<h1 class="text-heading-xl">
					<?php
					if ( is_category() ) {
						single_cat_title();
					} elseif ( is_tag() ) {
						single_tag_title();
					} elseif ( is_author() ) {
						the_author();
					} elseif ( is_date() ) {
						echo get_the_date( 'F Y' );
					} else {
						esc_html_e( 'Archive', 'raven' );
					}
					?>
				</h1>
			</div>
		</div>

		<?php if ( have_posts() ) : ?>

		<div class="grid grid-cols-12 mt-10xl">
			<?php while ( have_posts() ) : the_post(); ?>

			<article class="col-span-12 md:col-span-6 xl:col-span-4">
				<?php if ( has_post_thumbnail() ) : ?>
				<a href="<?php the_permalink(); ?>">
					<img src="<?php echo esc_url( get_the_post_thumbnail_url( null, 'large' ) ); ?>" alt="<?php echo esc_attr( get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ) ); ?>" class="w-full h-auto">
				</a>
				<?php endif; ?>

				<div class="space-y-md mt-5xl">
					<p class="text-subtitle-sm text-cloud"><?php echo get_the_date(); ?></p>
					<h2 class="text-heading-sm">
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					</h2>
					<div class="text-body-md text-cloud"><?php the_excerpt(); ?></div>
				</div>
			</article>

			<?php endwhile; ?>
		</div>

		<div class="mt-10xl">
			<?php the_posts_pagination(); ?>
		</div>

		<?php else : ?>

		<div class="grid grid-cols-12 mt-10xl">
			<p class="col-span-12 text-body-lg text-cloud"><?php esc_html_e( 'No posts found.', 'raven' ); ?></p>
		</div>

		<?php endif; ?>

	</div>
</section>

<?php get_footer(); ?>
