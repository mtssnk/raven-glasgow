<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ── Settings page ─────────────────────────────────────────────────────────────

add_action( 'admin_menu', function () {
	add_options_page(
		'Site Settings',
		'Site Settings',
		'manage_options',
		'raven-site-settings',
		'raven_site_settings_page'
	);
} );

add_action( 'admin_init', function () {
	register_setting( 'raven_site_options', 'raven_site_phone', [ 'sanitize_callback' => 'sanitize_text_field' ] );
	register_setting( 'raven_site_options', 'raven_site_email', [ 'sanitize_callback' => 'sanitize_email' ] );
	register_setting( 'raven_site_options', 'raven_site_facebook', [ 'sanitize_callback' => 'esc_url_raw' ] );
	register_setting( 'raven_site_options', 'raven_site_instagram', [ 'sanitize_callback' => 'esc_url_raw' ] );
	register_setting( 'raven_site_options', 'raven_site_tripadvisor', [ 'sanitize_callback' => 'esc_url_raw' ] );
	register_setting( 'raven_site_options', 'raven_site_x', [ 'sanitize_callback' => 'esc_url_raw' ] );

	add_settings_section( 'raven_site_contact_section', 'Contact', null, 'raven-site-settings' );
	add_settings_section( 'raven_site_social_section', 'Social Links', null, 'raven-site-settings' );

	add_settings_field( 'raven_site_phone', 'Phone number', function () {
		$val = get_option( 'raven_site_phone', '' );
		echo '<input type="text" name="raven_site_phone" value="' . esc_attr( $val ) . '" class="regular-text">';
	}, 'raven-site-settings', 'raven_site_contact_section' );

	add_settings_field( 'raven_site_email', 'Email address', function () {
		$val = get_option( 'raven_site_email', '' );
		echo '<input type="email" name="raven_site_email" value="' . esc_attr( $val ) . '" class="regular-text">';
	}, 'raven-site-settings', 'raven_site_contact_section' );

	$socials = [ 'facebook' => 'Facebook', 'instagram' => 'Instagram', 'tripadvisor' => 'Tripadvisor', 'x' => 'X' ];
	foreach ( $socials as $key => $label ) {
		add_settings_field( "raven_site_{$key}", $label, function () use ( $key ) {
			$val = get_option( "raven_site_{$key}", '' );
			echo '<input type="url" name="raven_site_' . esc_attr( $key ) . '" value="' . esc_attr( $val ) . '" class="regular-text" placeholder="https://">';
		}, 'raven-site-settings', 'raven_site_social_section' );
	}
} );

function raven_site_settings_page() {
	?>
	<div class="wrap">
		<h1>Site Settings</h1>
		<form method="post" action="options.php">
			<?php
			settings_fields( 'raven_site_options' );
			do_settings_sections( 'raven-site-settings' );
			submit_button();
			?>
		</form>
	</div>
	<?php
}
