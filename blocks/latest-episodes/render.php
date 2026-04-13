<?php
/**
 * Latest episodes block render callback.
 *
 * @package PropertyListings
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$defaults = array(
	'heading'     => 'Latest Episodes',
	'description' => 'Large 700x394 style thumbnails only. No video player. Every episode links out to YouTube or Vimeo.',
	'buttonText'  => 'All Episodes',
);

$attributes = wp_parse_args( $attributes, $defaults );

if ( ! function_exists( 'property_listings_render_latest_episodes_section' ) ) {
	return;
}

echo property_listings_render_latest_episodes_section(
	array(
		'heading'     => $attributes['heading'],
		'description' => $attributes['description'],
		'buttonText'  => $attributes['buttonText'],
		'className'   => 'latest-episodes-block',
	)
);
