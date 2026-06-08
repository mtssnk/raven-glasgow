<?php
/**
 * Template Name: Legal
 * Template Post Type: page
 */

get_header();
?>

<main class="container py-10xl">
	<div class="max-w-[800px] mx-auto freak-mce">

		<?php while ( have_posts() ) : the_post(); ?>

		<div class="freak-mce text-birch">
			<?php the_content(); ?>
		</div>

		<?php endwhile; ?>

	</div>
</main>

<?php get_footer(); ?>
