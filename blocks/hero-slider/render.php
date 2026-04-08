<?php
/**
 * Hero slider block render callback.
 *
 * @package PropertyListings
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$defaults = array(
	'kicker'   => 'Luxury Real Estate Podcast',
	'heading'  => 'Always Bet On The House',
	'subtitle' => 'Showcasing standout homes, the agents behind them, and the stories that make each listing worth the spotlight.',
	'showText' => true,
	'logoUrl'  => '',
	'logoAlt'  => '',
	'slides'   => array(),
);

$attributes = wp_parse_args( $attributes, $defaults );

$default_logo = get_theme_file_uri( '/assets/images/logo.png' );
$logo_url     = ! empty( $attributes['logoUrl'] ) ? $attributes['logoUrl'] : $default_logo;
$logo_alt     = isset( $attributes['logoAlt'] ) ? $attributes['logoAlt'] : '';
$slides       = is_array( $attributes['slides'] ) ? array_values( $attributes['slides'] ) : array();

if ( empty( $slides ) ) {
	return '';
}

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => 'hero section-dark hero-slider-block',
	)
);
?>
<section <?php echo $wrapper_attributes; ?>>
	<div class="hero-fullwidth">
		<div class="hero-carousel-full">
			<div class="hero-logo-overlay" aria-hidden="true">
				<img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php echo esc_attr( $logo_alt ); ?>" />
			</div>

			<?php foreach ( $slides as $index => $slide ) : ?>
				<?php
				$image_url = isset( $slide['imageUrl'] ) ? $slide['imageUrl'] : '';
				$image_alt = isset( $slide['imageAlt'] ) ? $slide['imageAlt'] : '';

				if ( empty( $image_url ) ) {
					continue;
				}
				?>
				<article class="hero-slide<?php echo 0 === $index ? ' is-active' : ''; ?>">
					<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>" />
				</article>
			<?php endforeach; ?>

			<button class="hero-arrow hero-arrow-prev" type="button" aria-label="<?php esc_attr_e( 'Show previous slide', 'property-listings' ); ?>">
				<span aria-hidden="true">&larr;</span>
			</button>
			<button class="hero-arrow hero-arrow-next" type="button" aria-label="<?php esc_attr_e( 'Show next slide', 'property-listings' ); ?>">
				<span aria-hidden="true">&rarr;</span>
			</button>
		</div>
	</div>

	<?php if ( ! empty( $attributes['showText'] ) ) : ?>
		<div class="hero-slider-shell">
			<div class="hero-slider-copy">
				<?php if ( ! empty( $attributes['kicker'] ) ) : ?>
					<p class="hero-kicker"><?php echo esc_html( $attributes['kicker'] ); ?></p>
				<?php endif; ?>

				<?php if ( ! empty( $attributes['heading'] ) ) : ?>
					<h1><?php echo esc_html( $attributes['heading'] ); ?></h1>
				<?php endif; ?>

				<?php if ( ! empty( $attributes['subtitle'] ) ) : ?>
					<p class="hero-subtitle"><?php echo esc_html( $attributes['subtitle'] ); ?></p>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>
</section>
