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

	add_theme_support( 'title-tag' );

	add_editor_style( 'assets/css/editor.css' );

	register_block_pattern_category(
		'property-listings',
		array(
			'label' => __( 'Property Listings', 'property-listings' ),
		)
	);
}
add_action( 'after_setup_theme', 'property_listings_setup' );

function property_listings_get_header_title() {
	return 'ALWAYS BET ON THE HOUSE';
}

function property_listings_render_header_title() {
	$title = property_listings_get_header_title();

	if ( empty( $title ) ) {
		return '';
	}

	return sprintf(
		'<p class="site-title"><a href="%1$s" rel="home">%2$s</a></p>',
		esc_url( home_url( '/' ) ),
		esc_html( $title )
	);
}
add_shortcode( 'property_listings_header_title', 'property_listings_render_header_title' );

function property_listings_register_blocks() {
	$hero_slider_editor_js_path  = get_theme_file_path( '/blocks/hero-slider/editor.js' );
	$host_section_editor_js_path = get_theme_file_path( '/blocks/host-section/editor.js' );

	wp_register_script(
		'property-listings-hero-slider-editor',
		get_theme_file_uri( '/blocks/hero-slider/editor.js' ),
		array( 'wp-blocks', 'wp-block-editor', 'wp-components', 'wp-element', 'wp-i18n' ),
		file_exists( $hero_slider_editor_js_path ) ? filemtime( $hero_slider_editor_js_path ) : null,
		true
	);

	wp_localize_script(
		'property-listings-hero-slider-editor',
		'propertyListingsHeroSlider',
		array(
			'defaultLogoUrl' => get_theme_file_uri( '/assets/images/logo.png' ),
		)
	);

	wp_register_script(
		'property-listings-host-section-editor',
		get_theme_file_uri( '/blocks/host-section/editor.js' ),
		array( 'wp-blocks', 'wp-block-editor', 'wp-components', 'wp-element', 'wp-i18n' ),
		file_exists( $host_section_editor_js_path ) ? filemtime( $host_section_editor_js_path ) : null,
		true
	);

	register_block_type( get_theme_file_path( '/blocks/hero-slider' ) );
	register_block_type( get_theme_file_path( '/blocks/host-section' ) );
}
add_action( 'init', 'property_listings_register_blocks' );

function property_listings_enqueue_assets() {
	$main_css_path = get_theme_file_path( '/assets/css/main.css' );
	$main_js_path  = get_theme_file_path( '/assets/js/main.js' );

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
		file_exists( $main_css_path ) ? filemtime( $main_css_path ) : null
	);

	wp_enqueue_script(
		'property-listings-main',
		get_theme_file_uri( '/assets/js/main.js' ),
		array(),
		file_exists( $main_js_path ) ? filemtime( $main_js_path ) : null,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'property_listings_enqueue_assets' );

function property_listings_enqueue_editor_assets() {
	$main_css_path = get_theme_file_path( '/assets/css/main.css' );

	wp_enqueue_style(
		'property-listings-editor-main',
		get_theme_file_uri( '/assets/css/main.css' ),
		array(),
		file_exists( $main_css_path ) ? filemtime( $main_css_path ) : null
	);
}
add_action( 'enqueue_block_editor_assets', 'property_listings_enqueue_editor_assets' );
