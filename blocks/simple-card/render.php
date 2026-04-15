<?php
/**
 * Simple card block render callback.
 *
 * @package PropertyListings
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$defaults = array(
	'title'      => 'Simple Card Title',
	'text'       => 'This is a starter custom block. Edit the content in the sidebar or directly in the block preview.',
	'buttonText' => 'Learn More',
	'buttonUrl'  => '#',
);

$attributes = wp_parse_args( $attributes, $defaults );

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => 'simple-card-block section-light alt-surface',
	)
);
?>
<section <?php echo $wrapper_attributes; ?>>
	<div class="container">
		<div class="featured-grid single-scene-featured-grid">
			<article class="featured-card simple-card-preview">
				<div class="featured-content">
					<?php if ( ! empty( $attributes['title'] ) ) : ?>
						<h3><?php echo esc_html( $attributes['title'] ); ?></h3>
					<?php endif; ?>

					<?php if ( ! empty( $attributes['text'] ) ) : ?>
						<p class="featured-description"><?php echo esc_html( $attributes['text'] ); ?></p>
					<?php endif; ?>

					<?php if ( ! empty( $attributes['buttonText'] ) ) : ?>
						<div class="single-scene-actions">
							<a href="<?php echo esc_url( $attributes['buttonUrl'] ); ?>" class="btn btn-gold"><?php echo esc_html( $attributes['buttonText'] ); ?></a>
						</div>
					<?php endif; ?>
				</div>
			</article>
		</div>
	</div>
</section>
