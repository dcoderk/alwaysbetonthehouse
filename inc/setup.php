<?php
/**
 * Theme setup.
 *
 * @package ClientStarter
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function clientstarter_setup() {
	load_theme_textdomain( 'clientstarter', get_template_directory() . '/languages' );

	add_editor_style( 'assets/css/editor.css' );

	register_block_pattern_category(
		'clientstarter',
		array(
			'label' => __( 'ClientStarter', 'clientstarter' ),
		)
	);
}
add_action( 'after_setup_theme', 'clientstarter_setup' );

function clientstarter_enqueue_assets() {
	$theme = wp_get_theme();

	wp_enqueue_style(
		'clientstarter-main',
		get_theme_file_uri( '/assets/css/main.css' ),
		array(),
		$theme->get( 'Version' )
	);
}
add_action( 'wp_enqueue_scripts', 'clientstarter_enqueue_assets' );
