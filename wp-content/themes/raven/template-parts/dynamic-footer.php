<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$cta_global   = (bool) get_option( 'raven_footer_cta_enabled', '0' );
$cta_override = get_post_meta( get_the_ID(), '_raven_footer_cta', true ) ?: 'inherit';

if ( 'show' === $cta_override ) {
	$show_cta = true;
} elseif ( 'hide' === $cta_override ) {
	$show_cta = false;
} else {
	$show_cta = $cta_global;
}

$cta_heading      = get_option( 'raven_footer_cta_heading', '' );
$cta_text         = get_option( 'raven_footer_cta_text', '' );
$cta_button_label = get_option( 'raven_footer_cta_button_label', '' );
$cta_button_url   = get_option( 'raven_footer_cta_button_url', '' );
?>
<footer class="site-footer bg-night border-t border-birch/10" id="site-footer" role="contentinfo">

	<?php if ( $show_cta && ( $cta_heading || $cta_text || $cta_button_label ) ) : ?>
	<div class="site-footer__cta border-b border-birch/10">
		<div class="container py-xl flex flex-col gap-lg sm:flex-row sm:items-center sm:justify-between">

			<div>
				<?php if ( $cta_heading ) : ?>
				<h2 class="text-heading-sm text-birch"><?php echo esc_html( $cta_heading ); ?></h2>
				<?php endif; ?>
				<?php if ( $cta_text ) : ?>
				<p class="text-body-lg text-birch m-0">
					<?php echo wp_kses_post( $cta_text ); ?>
				</p>
				<?php endif; ?>
			</div>

			<?php if ( $cta_button_label && $cta_button_url ) : ?>
			<a href="<?php echo esc_url( $cta_button_url ); ?>" class="btn btn-primary btn-md shrink-0">
				<?php echo esc_html( $cta_button_label ); ?>
			</a>
			<?php endif; ?>

		</div>
	</div>
	<?php endif; ?>

	<div class="container py-lg">
		<p class="text-body-xs text-pearl m-0">
			&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?>
			<?php echo esc_html( get_bloginfo( 'name' ) ); ?>
		</p>
	</div>

</footer>
