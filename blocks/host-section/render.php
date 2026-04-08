<?php
/**
 * Host section block render callback.
 *
 * @package PropertyListings
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$defaults = array(
	'showTitle'   => true,
	'title'       => 'Always Bet On The House',
	'eyebrow'     => 'Hosted by',
	'name'        => 'Chrishena Stanley',
	'description' => 'Join Chrishena Stanley as she takes you inside luxury properties, showcases standout agents, and explores what makes each featured home worth the spotlight. This layout keeps the host introduction elegant while pushing visitors directly to external episode links.',
	'imageUrl'    => '',
	'imageAlt'    => '',
);

$attributes = wp_parse_args( $attributes, $defaults );

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => 'host-section section-dark host-section-block',
	)
);
?>
<section <?php echo $wrapper_attributes; ?>>
	<div class="host-shell">
		<div class="host-layout">
			<div class="host-photo-wrap">
				<?php if ( ! empty( $attributes['imageUrl'] ) ) : ?>
					<img src="<?php echo esc_url( $attributes['imageUrl'] ); ?>" alt="<?php echo esc_attr( $attributes['imageAlt'] ); ?>" class="host-photo" />
				<?php else : ?>
					<div class="host-photo host-photo-placeholder" aria-hidden="true"></div>
				<?php endif; ?>
			</div>

			<div class="host-copy">
				<?php if ( ! empty( $attributes['showTitle'] ) && ! empty( $attributes['title'] ) ) : ?>
					<h1><?php echo esc_html( $attributes['title'] ); ?></h1>
				<?php endif; ?>

				<?php if ( ! empty( $attributes['eyebrow'] ) ) : ?>
					<p class="eyebrow"><?php echo esc_html( $attributes['eyebrow'] ); ?></p>
				<?php endif; ?>

				<?php if ( ! empty( $attributes['name'] ) ) : ?>
					<h2><?php echo esc_html( $attributes['name'] ); ?></h2>
				<?php endif; ?>

				<?php if ( ! empty( $attributes['description'] ) ) : ?>
					<p><?php echo esc_html( $attributes['description'] ); ?></p>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
