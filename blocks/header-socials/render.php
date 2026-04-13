<?php
/**
 * Header socials block render callback.
 *
 * @package PropertyListings
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$defaults = array(
	'xUrl'         => '#',
	'instagramUrl' => '#',
	'tiktokUrl'    => '#',
	'linkedinUrl'  => '#',
);

$attributes = wp_parse_args( $attributes, $defaults );

$items = array(
	array(
		'label' => 'X',
		'url'   => $attributes['xUrl'],
		'icon'  => 'bi bi-twitter-x',
	),
	array(
		'label' => 'Instagram',
		'url'   => $attributes['instagramUrl'],
		'icon'  => 'bi bi-instagram',
	),
	array(
		'label' => 'TikTok',
		'url'   => $attributes['tiktokUrl'],
		'icon'  => 'bi bi-tiktok',
	),
	array(
		'label' => 'LinkedIn',
		'url'   => $attributes['linkedinUrl'],
		'icon'  => 'bi bi-linkedin',
	),
);

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => 'social-list header-socials',
	)
);
?>
<ul <?php echo $wrapper_attributes; ?>>
	<?php foreach ( $items as $item ) : ?>
		<?php if ( empty( $item['url'] ) ) : ?>
			<?php continue; ?>
		<?php endif; ?>
		<li class="header-social-item">
			<a class="header-social-link" href="<?php echo esc_url( $item['url'] ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $item['label'] ); ?>">
				<i class="<?php echo esc_attr( $item['icon'] ); ?>" aria-hidden="true"></i>
			</a>
		</li>
	<?php endforeach; ?>
</ul>
