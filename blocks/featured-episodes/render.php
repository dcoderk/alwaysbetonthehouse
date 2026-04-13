<?php
/**
 * Featured episodes block render callback.
 *
 * @package PropertyListings
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$defaults = array(
	'heading'     => 'Featured Episodes',
	'description' => 'Secondary highlights for signature conversations, showcase homes, and promoted content.',
);

$attributes = wp_parse_args( $attributes, $defaults );

$featured_query = new WP_Query(
	array(
		'post_type'      => 'scene',
		'post_status'    => 'publish',
		'posts_per_page' => 3,
		'orderby'        => 'date',
		'order'          => 'DESC',
		'offset'         => 3,
	)
);

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => 'featured section-light alt-surface featured-episodes-block',
		'id'    => 'featured',
	)
);
?>
<section <?php echo $wrapper_attributes; ?>>
	<div class="container">
		<div class="section-heading reveal">
			<?php if ( ! empty( $attributes['heading'] ) ) : ?>
				<h2><?php echo esc_html( $attributes['heading'] ); ?></h2>
			<?php endif; ?>
			<?php if ( ! empty( $attributes['description'] ) ) : ?>
				<p><?php echo esc_html( $attributes['description'] ); ?></p>
			<?php endif; ?>
		</div>

		<?php if ( $featured_query->have_posts() ) : ?>
			<div class="featured-grid">
				<?php while ( $featured_query->have_posts() ) : ?>
					<?php
					$featured_query->the_post();
					$post_id     = get_the_ID();
					$title       = get_the_title();
					$address     = property_listings_get_related_item_location( $post_id );
					$description = property_listings_get_scene_card_description( $post_id );
					$price       = property_listings_get_scene_price( $post_id );
					$agent_data  = property_listings_get_scene_agent_data( $post_id );
					$link_data   = property_listings_get_scene_card_link_data( $post_id );
					$image_html  = property_listings_clean_media_markup( property_listings_get_scene_card_thumbnail( $post_id, $title ) );
					?>
					<article class="featured-card reveal">
						<?php if ( ! empty( $image_html ) ) : ?>
							<a href="<?php echo esc_url( $link_data['url'] ); ?>"<?php echo $link_data['target']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo $link_data['rel']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="featured-thumb">
								<?php echo $image_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</a>
						<?php else : ?>
							<div class="featured-thumb" aria-hidden="true"></div>
						<?php endif; ?>

						<div class="featured-content">
							<?php if ( ! empty( $title ) ) : ?>
								<h3><?php echo esc_html( $title ); ?></h3>
							<?php endif; ?>
							<?php if ( ! empty( $address ) ) : ?>
								<p class="featured-address"><?php echo esc_html( $address ); ?></p>
							<?php endif; ?>
							<?php if ( ! empty( $description ) ) : ?>
								<p class="featured-description"><?php echo esc_html( $description ); ?></p>
							<?php endif; ?>
							<?php if ( ! empty( $price ) ) : ?>
								<p class="featured-price"><?php echo esc_html( $price ); ?></p>
							<?php endif; ?>
							<?php if ( ! empty( $agent_data['name'] ) ) : ?>
								<p class="featured-agent">
									Agent:
									<?php if ( ! empty( $agent_data['url'] ) ) : ?>
										<a href="<?php echo esc_url( $agent_data['url'] ); ?>"><?php echo esc_html( $agent_data['name'] ); ?></a>
									<?php else : ?>
										<?php echo esc_html( $agent_data['name'] ); ?>
									<?php endif; ?>
								</p>
							<?php endif; ?>
						</div>
					</article>
				<?php endwhile; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
<?php wp_reset_postdata(); ?>
