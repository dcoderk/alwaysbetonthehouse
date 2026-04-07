<?php
/**
 * Theme setup.
 *
 * @package PropertyListings
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function property_listings_setup() {
	load_theme_textdomain( 'property-listings', get_template_directory() . '/languages' );

	add_editor_style( 'assets/css/editor.css' );

	register_block_pattern_category(
		'property-listings',
		array(
			'label' => __( 'Property Listings', 'property-listings' ),
		)
	);
}
add_action( 'after_setup_theme', 'property_listings_setup' );

function property_listings_enqueue_assets() {
	$theme = wp_get_theme();

	wp_enqueue_style(
		'property-listings-fonts',
		'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Cormorant+Garamond:wght@500;600;700&display=swap',
		array(),
		null
	);

	wp_enqueue_style(
		'property-listings-main',
		get_theme_file_uri( '/assets/css/main.css' ),
		array( 'property-listings-fonts' ),
		$theme->get( 'Version' )
	);

	wp_enqueue_script(
		'property-listings-main',
		get_theme_file_uri( '/assets/js/main.js' ),
		array(),
		$theme->get( 'Version' ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'property_listings_enqueue_assets' );
