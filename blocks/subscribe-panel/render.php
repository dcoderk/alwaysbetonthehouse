<?php
/**
 * Subscribe panel block render callback.
 *
 * @package PropertyListings
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$defaults = array(
	'heading'     => 'Subscribe to Podcast',
	'description' => 'Get updates on new episodes, guest features, and spotlight properties.',
	'buttonText'  => 'Subscribe',
	'buttonUrl'   => '#',
);

$attributes = wp_parse_args( $attributes, $defaults );

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => 'subscribe section-dark subscribe-panel-block',
		'id'    => 'subscribe',
	)
);
?>
<section <?php echo $wrapper_attributes; ?>>
	<div class="container reveal">
		<div class="subscribe-panel">
			<?php if ( ! empty( $attributes['heading'] ) ) : ?>
				<h2><?php echo esc_html( $attributes['heading'] ); ?></h2>
			<?php endif; ?>

			<?php if ( ! empty( $attributes['description'] ) ) : ?>
				<p><?php echo esc_html( $attributes['description'] ); ?></p>
			<?php endif; ?>

			<?php if ( ! empty( $attributes['buttonText'] ) ) : ?>
				<a href="<?php echo esc_url( $attributes['buttonUrl'] ? $attributes['buttonUrl'] : '#' ); ?>" class="btn btn-gold"><?php echo esc_html( $attributes['buttonText'] ); ?></a>
			<?php endif; ?>
		</div>
	</div>
</section>
