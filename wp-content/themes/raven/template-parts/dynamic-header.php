<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$nav = wp_nav_menu( [
	'theme_location' => 'primary',
	'container'      => false,
	'menu_class'     => 'text-subtitle-sm flex items-center flex-row gap-lg',
	'fallback_cb'    => false,
	'echo'           => false,
] );

if ( ! $nav ) {
	return;
}
?>
<header class="js-freak-header freak-header fixed w-full top-0 left-0 z-50 bg-night" id="site-header">
	<div class="container flex justify-between items-center py-lg">
		
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="header-logo w-[169px] block" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
			<svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" viewBox="0 0 169 33"><path class="glasgow" style="fill: var(--color-fire)" d="M51.9 28.4h3.6v1c0 2.3-1.3 3.6-3.7 3.6-2.3 0-3.8-1.5-3.8-4.2 0-2.4 1.3-4.2 3.8-4.2 2.1 0 2.9.8 3.5 2.3l-2.1.8c-.3-.7-.6-1.1-1.4-1.1-1.1 0-1.4.9-1.4 2.2 0 1.6.5 2.3 1.6 2.3 1 0 1.3-.4 1.4-1h-1.5zm9.6-3.7h-2.3v8.2h6v-2h-3.7zm12.2 0 3 8.2h-2.5l-.4-1.4h-2.5l-.5 1.4h-2.5l3-8.2zm-.5 4.9-.2-.5c-.3-1.1-.4-1.5-.5-2-.1.6-.2 1-.5 2l-.2.5zm10.3-1.9c-1.1-.2-1.4-.4-1.4-.7s.2-.5.9-.5c.8 0 1.6.3 2.3.8l1.1-1.8c-.9-.6-1.9-.9-3.3-.9-2 0-3.3 1-3.3 2.6s1 2.2 2.9 2.6c1.1.3 1.4.4 1.4.7 0 .4-.3.5-1.1.5s-1.8-.4-2.4-.8l-1 1.8c.9.6 2.1 1 3.5 1 2 0 3.4-.8 3.4-2.6 0-1.6-1-2.2-3-2.7m10.2 2.4h1.5c-.1.6-.4 1-1.4 1-1.1 0-1.6-.7-1.6-2.3 0-1.3.4-2.2 1.4-2.2.8 0 1.1.4 1.4 1.1l2.1-.8c-.6-1.5-1.4-2.3-3.5-2.3-2.5 0-3.8 1.8-3.8 4.2 0 2.7 1.5 4.2 3.8 4.2 2.4 0 3.7-1.3 3.7-3.6v-1h-3.6zm14.7-1.3c0 2.7-1.6 4.2-3.8 4.2s-3.8-1.5-3.8-4.2 1.6-4.2 3.8-4.2c2.2-.1 3.8 1.4 3.8 4.2m-2.4 0c0-1.5-.5-2.3-1.5-2.3s-1.4.8-1.4 2.2c0 1.5.5 2.3 1.5 2.3.9 0 1.4-.7 1.4-2.2m13.1-4.1-.2 1.4c-.2 1.2-.4 2.6-.5 3.6-.1-1-.4-2.4-.6-3.5l-.3-1.4h-2.1l-.3 1.4c-.2 1.2-.5 2.6-.6 3.5-.1-1-.3-2.4-.5-3.6l-.2-1.4h-2.4l2 8.2h2.1l.4-1.8c.3-1.3.5-2.8.6-3.4 0 .6.3 2.1.6 3.4l.4 1.8h2.1l2-8.2z" /><path style="fill: var(--color-birch)" class="raven" d="M0 0h15.6v5.4h-4.3V17h-7V5.4H0zm30 5.6h-2.8V0h-7v17h7v-5.8H30V17h7V0h-7zm18.6 5.2h5.7V6.2h-5.7V4.7h8V0H41.9v17h14.8v-4.7h-8v-1.5zm37.3-.2 3.6 6.3h-7.4l-2.4-5.3h-.1V17h-6.7V0h8.7c3.9 0 7.1 1.2 7.1 5.7.1 2.7-1.3 4-2.8 4.9m-3.7-4.7c0-1-.7-1.5-1.9-1.5h-.7v3h.7c1.3 0 1.9-.5 1.9-1.5m23-5.9 5.4 17H104l-.5-2h-4.2l-.5 2h-6.6l5.4-17zm-2.8 10.6c-.4-1.9-.8-3.8-.9-5-.1 1.2-.5 3.1-1 5zM121 4.1c-.6 2.5-1 4.6-1.1 6.3-.1-1.7-.5-3.9-1.1-6.3l-1-4.1H111l5.4 17h7l5.4-17H122zm18.7 6.7h5.7V6.2h-5.7V4.7h8V0H133v17h14.8v-4.7h-8zM162.5 0v5c0 .5.1 1.5.1 2-.4-1.2-1.4-3.1-2.1-4.2L158.8 0h-6.4v17h6.5v-5.5c0-.5-.1-1.4-.1-2 .4 1.2 1.3 2.9 2.1 4.2l1.9 3.2h6.2V0z"/></svg>
		</a>
		<nav class="site-nav" aria-label="<?php esc_attr_e( 'Primary navigation', 'raven' ); ?>">
			<?php echo $nav; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</nav>

	</div>
</header>
