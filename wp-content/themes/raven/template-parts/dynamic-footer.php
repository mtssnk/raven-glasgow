<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$cta_global   = (bool) get_option( 'raven_footer_cta_enabled', '0' );
$cta_override = get_post_meta( get_queried_object_id(), '_raven_footer_cta', true ) ?: 'inherit';

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

$site_phone   = get_option( 'raven_site_phone', '' );
$site_email   = get_option( 'raven_site_email', '' );
$site_socials = array_filter( [
	'facebook'    => get_option( 'raven_site_facebook', '' ),
	'instagram'   => get_option( 'raven_site_instagram', '' ),
	'tripadvisor' => get_option( 'raven_site_tripadvisor', '' ),
	'x'           => get_option( 'raven_site_x', '' ),
] );

?>
<footer class="freak-footer bg-night py-10xl" id="site-footer" role="contentinfo">

	<?php if ( $show_cta && ( $cta_heading || $cta_text || $cta_button_label ) ) : ?>
	<div class="container">		
		<div class="max-w-[660px] mx-auto text-center">
			<div class="flex flex-col gap-2xl pb-4xl">
				<?php if ( $cta_heading ) : ?>
				<p class="text-heading-sm text-birch"><?php echo esc_html( $cta_heading ); ?></p>
				<?php endif; ?>
				<?php if ( $cta_text ) : ?>
				<p class="text-body-md text-pearl text-balance">
					<?php echo wp_kses_post( $cta_text ); ?>
				</p>
				<?php endif; ?>
				<?php if ( $cta_button_label && $cta_button_url ) : ?>
				<div class="grow-0"><?php echo raven_render_button( $cta_button_label, $cta_button_url ); ?></div>
				<?php endif; ?>
			</div>
			<hr class="border-b-2 border-pearl/25 w-[20%] w-full max-w-[300px] mx-auto" />
		</div>
	</div>
	<?php endif; ?>

	<div class="container flex flex-col items-center gap-3xl py-3xl">
		<figure class="w-[176px] h-[88px]"><?php echo raven_get_icon( 'logo-ravenmark' ); ?></figure>
		<?php if ( $site_socials ) : ?>
		<div class="flex items-center justify-center gap-[24px]">
			<?php foreach ( $site_socials as $key => $url ) : ?>
			<?php $svg = raven_get_icon( $key ); if ( ! $svg ) continue; ?>
			<a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer" class="[&>svg]:w-[24px] [&>svg]:h-[24px]" aria-label="<?php echo esc_attr( ucfirst( $key ) ); ?>">
				<?php echo $svg; // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</a>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>
	</div>

	<div class="container flex flex-row justify-center gap-3xl py-3xl text-center">
		<div class="w-1/2 max-w-[300px]">
			<p class="text-subtitle-xs pb-lg border-b-2 border-pearl/25">get in contact</p>
			<ul class="text-body-sm pt-lg space-y-xs">
				<?php if ( $site_phone ) : ?>
				<li><a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $site_phone ) ); ?>">
					<?php echo esc_html( $site_phone ); ?>
				</a></li>
				<?php endif; ?>				
				<?php if ( $site_email ) : ?>
				<li><a href="mailto:<?php echo esc_attr( $site_email ); ?>">
					<?php echo esc_html( $site_email ); ?>
				</a></li>
				<a target="_blank" rel="noopener noreferrer" href="https://maps.app.goo.gl/crP6djxx2x9er6Z76"><li>81-85 Renfield St, Glasgow, G2 1LP</li></a>
				<?php endif; ?>				
			</ul>
		</div>
		<div class="w-1/2 max-w-[300px]">
			<p class="text-subtitle-xs pb-lg border-b-2 border-pearl/25">partnered with</p>
			<figure class="text-cloud pt-lg w-[126px] h-[73px] mx-auto">
				<?php echo raven_get_icon( 'logo-best-bar-none' ); ?>		
			</figure>
		</div>
	</div>

	<div class="container py-3xl mt-3xl border-t-2 border-pearl/25 flex flex-row items-center justify-between gap-3xl text-body-sm [a]:text-pearl">
		<div>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> The Signature Group</div>
		<div class="flex gap-2xl">
			<a href="https://freakwebdesign.co.uk/" target="_blank" rel="noopener noreferrer" title="Edinburgh agency, specialising in bespoke website design, impactful branding, print, & innovative graphic solutions.">website by <span class="font-bold">Freak</span></a>
			<a href="/privacy">Privacy policy</a>
			<a href="/cookie-policy">Cookie policy</a>
		</div>
	</div>

</footer>
