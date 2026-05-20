<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ── Settings page ─────────────────────────────────────────────────────────────

add_action( 'admin_menu', function () {
	add_options_page(
		'Footer Settings',
		'Footer',
		'manage_options',
		'raven-footer',
		'raven_footer_settings_page'
	);
} );

add_action( 'admin_init', function () {
	register_setting( 'raven_footer_options', 'raven_footer_cta_enabled', [ 'sanitize_callback' => 'absint' ] );
	register_setting( 'raven_footer_options', 'raven_footer_cta_heading', [ 'sanitize_callback' => 'sanitize_text_field' ] );
	register_setting( 'raven_footer_options', 'raven_footer_cta_text', [ 'sanitize_callback' => 'sanitize_textarea_field' ] );
	register_setting( 'raven_footer_options', 'raven_footer_cta_button_label', [ 'sanitize_callback' => 'sanitize_text_field' ] );
	register_setting( 'raven_footer_options', 'raven_footer_cta_button_url', [ 'sanitize_callback' => 'esc_url_raw' ] );

	add_settings_section( 'raven_footer_cta_section', 'Call to Action', null, 'raven-footer' );

	add_settings_field( 'raven_footer_cta_enabled', 'Enable CTA by default', function () {
		$val = get_option( 'raven_footer_cta_enabled', '0' );
		echo '<input type="checkbox" name="raven_footer_cta_enabled" value="1"' . checked( 1, $val, false ) . '>';
	}, 'raven-footer', 'raven_footer_cta_section' );

	add_settings_field( 'raven_footer_cta_heading', 'CTA heading', function () {
		$val = get_option( 'raven_footer_cta_heading', '' );
		echo '<input type="text" name="raven_footer_cta_heading" value="' . esc_attr( $val ) . '" class="regular-text">';
	}, 'raven-footer', 'raven_footer_cta_section' );

	add_settings_field( 'raven_footer_cta_text', 'CTA text', function () {
		$val = get_option( 'raven_footer_cta_text', '' );
		echo '<textarea name="raven_footer_cta_text" rows="3" cols="50" class="large-text">' . esc_textarea( $val ) . '</textarea>';
	}, 'raven-footer', 'raven_footer_cta_section' );

	add_settings_field( 'raven_footer_cta_button_label', 'Button label', function () {
		$val = get_option( 'raven_footer_cta_button_label', '' );
		echo '<input type="text" name="raven_footer_cta_button_label" value="' . esc_attr( $val ) . '" class="regular-text">';
	}, 'raven-footer', 'raven_footer_cta_section' );

	add_settings_field( 'raven_footer_cta_button_url', 'Button URL', function () {
		$val = get_option( 'raven_footer_cta_button_url', '' );
		echo '<input type="text" name="raven_footer_cta_button_url" value="' . esc_attr( $val ) . '" class="regular-text">';
	}, 'raven-footer', 'raven_footer_cta_section' );
} );

function raven_footer_settings_page() {
	?>
	<div class="wrap">
		<h1>Footer Settings</h1>
		<form method="post" action="options.php">
			<?php
			settings_fields( 'raven_footer_options' );
			do_settings_sections( 'raven-footer' );
			submit_button();
			?>
		</form>
	</div>
	<?php
}

// ── Per-page meta box ─────────────────────────────────────────────────────────

add_action( 'add_meta_boxes', function () {
	add_meta_box(
		'raven_footer_cta',
		'Footer CTA',
		'raven_footer_meta_box_render',
		[ 'page', 'post' ],
		'side',
		'default'
	);
} );

function raven_footer_meta_box_render( $post ) {
	wp_nonce_field( 'raven_footer_cta_save', 'raven_footer_cta_nonce' );
	$value = get_post_meta( $post->ID, '_raven_footer_cta', true ) ?: 'inherit';
	?>
	<select name="raven_footer_cta" style="width:100%">
		<option value="inherit" <?php selected( $value, 'inherit' ); ?>>Inherit from global setting</option>
		<option value="show"    <?php selected( $value, 'show' ); ?>>Always show</option>
		<option value="hide"    <?php selected( $value, 'hide' ); ?>>Always hide</option>
	</select>
	<?php
}

add_action( 'save_post', function ( $post_id ) {
	if (
		! isset( $_POST['raven_footer_cta_nonce'] ) ||
		! wp_verify_nonce( $_POST['raven_footer_cta_nonce'], 'raven_footer_cta_save' ) ||
		( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ||
		! current_user_can( 'edit_post', $post_id )
	) {
		return;
	}

	$value = isset( $_POST['raven_footer_cta'] ) ? sanitize_key( $_POST['raven_footer_cta'] ) : 'inherit';
	$value = in_array( $value, [ 'inherit', 'show', 'hide' ], true ) ? $value : 'inherit';
	update_post_meta( $post_id, '_raven_footer_cta', $value );
} );
