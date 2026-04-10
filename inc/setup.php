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

function property_listings_get_agent_meta( $field_name, $post_id ) {
	if ( function_exists( 'get_field' ) ) {
		return get_field( $field_name, $post_id );
	}

	return get_post_meta( $post_id, $field_name, true );
}

function property_listings_get_related_item_location( $post_id ) {
	$location_fields = array(
		'address',
		'property_address',
		'location',
		'listing_address',
	);

	foreach ( $location_fields as $field_name ) {
		$value = property_listings_get_agent_meta( $field_name, $post_id );

		if ( is_string( $value ) && '' !== trim( $value ) ) {
			return trim( wp_strip_all_tags( $value ) );
		}
	}

	$excerpt = get_post_field( 'post_excerpt', $post_id );

	if ( is_string( $excerpt ) && '' !== trim( $excerpt ) ) {
		return trim( wp_strip_all_tags( $excerpt ) );
	}

	return '';
}

function property_listings_render_agent_listing_card( $post ) {
	$post = get_post( $post );

	if ( ! $post instanceof WP_Post ) {
		return '';
	}

	$related_title      = get_the_title( $post );
	$related_link       = get_permalink( $post );
	$related_location   = property_listings_get_related_item_location( $post->ID );
	$related_thumb      = get_the_post_thumbnail( $post, 'medium_large', array( 'alt' => $related_title ) );
	$open_in_new_window = false;

	if ( 'scene' === $post->post_type ) {
		$video_link        = property_listings_get_agent_meta( 'video_link', $post->ID );
		$video_screenshot  = property_listings_get_agent_meta( 'video_screenshot', $post->ID );
		$open_in_new_window = (bool) property_listings_get_agent_meta( 'open_in_new_window', $post->ID );

		if ( ! empty( $video_link ) ) {
			$related_link = $video_link;
		}

		if ( is_array( $video_screenshot ) && ! empty( $video_screenshot['ID'] ) ) {
			$video_screenshot = (int) $video_screenshot['ID'];
		}

		if ( is_numeric( $video_screenshot ) && ! empty( $video_screenshot ) ) {
			$related_thumb = wp_get_attachment_image(
				(int) $video_screenshot,
				'medium_large',
				false,
				array(
					'alt' => $related_title,
				)
			);
		} elseif ( is_array( $video_screenshot ) && ! empty( $video_screenshot['url'] ) ) {
			$related_thumb = sprintf(
				'<img src="%1$s" alt="%2$s" />',
				esc_url( $video_screenshot['url'] ),
				esc_attr( $related_title )
			);
		} elseif ( is_string( $video_screenshot ) && '' !== trim( $video_screenshot ) ) {
			$related_thumb = sprintf(
				'<img src="%1$s" alt="%2$s" />',
				esc_url( $video_screenshot ),
				esc_attr( $related_title )
			);
		}
	}

	$link_target = $open_in_new_window ? ' target="_blank" rel="noopener noreferrer"' : '';

	$thumb_markup = ! empty( $related_thumb ) ? $related_thumb : '<div class="agent-listing-thumb agent-listing-thumb--placeholder" aria-hidden="true"></div>';
	$location_markup = ! empty( $related_location ) ? '<p>' . esc_html( $related_location ) . '</p>' : '';

	return '<article class="agent-listing-card"><a href="' . esc_url( $related_link ) . '" class="agent-listing-thumb"' . $link_target . '>' . $thumb_markup . '</a><div class="agent-listing"><h3><a href="' . esc_url( $related_link ) . '"' . $link_target . '>' . esc_html( $related_title ) . '</a></h3>' . $location_markup . '</div></article>';
}

function property_listings_get_agent_scenes( $agent_id ) {
	$related_query = new WP_Query(
		array(
			'post_type'      => 'scene',
			'post_status'    => 'publish',
			'posts_per_page' => 4,
			'meta_query'     => array(
				array(
					'key'     => 'agent_on_video',
					'value'   => '"' . (int) $agent_id . '"',
					'compare' => 'LIKE',
				),
			),
		)
	);

	return $related_query->posts;
}

function property_listings_render_agent_profile_shortcode() {
	if ( ! is_singular( 'agent' ) ) {
		return '';
	}

	$post_id = get_queried_object_id();

	if ( ! $post_id ) {
		return '';
	}

	$agent_name     = get_the_title( $post_id );
	$agent_bio      = property_listings_get_agent_meta( 'short_bio', $post_id );
	$agent_content  = get_post_field( 'post_content', $post_id );
	$occupation     = property_listings_get_agent_meta( 'occupation', $post_id );
	$company        = property_listings_get_agent_meta( 'company', $post_id );
	$phone          = property_listings_get_agent_meta( 'phone', $post_id );
	$email          = property_listings_get_agent_meta( 'email', $post_id );
	$website        = property_listings_get_agent_meta( 'website', $post_id );
	$instagram      = property_listings_get_agent_meta( 'instagram', $post_id );
	$linkedin       = property_listings_get_agent_meta( 'linkedin', $post_id );
	$latest_videos  = property_listings_get_agent_meta( 'latest_videos', $post_id );
	$latest_intro   = property_listings_get_agent_meta( 'latest_section_intro', $post_id );
	$permalink      = get_permalink( $post_id );
	$related_scenes = property_listings_get_agent_scenes( $post_id );
	$featured_image = get_the_post_thumbnail(
		$post_id,
		'large',
		array(
			'class' => 'host-photo',
			'alt'   => $agent_name,
		)
	);

	if ( empty( $agent_bio ) && ! empty( $agent_content ) ) {
		$agent_bio = wp_trim_words( wp_strip_all_tags( $agent_content ), 55 );
	}

	if ( empty( $latest_intro ) ) {
		$latest_intro = 'Episodes or scenes connected to this agent can be highlighted here.';
	}

	$contact_items = array_filter(
		array(
			$occupation ? '<p><span>Title:</span> ' . esc_html( $occupation ) . '</p>' : '',
			$company ? '<p><span>Company:</span> ' . esc_html( $company ) . '</p>' : '',
			$phone ? '<p><span>Phone:</span> ' . esc_url( 'tel:' . preg_replace( '/[^0-9+]/', '', $phone ) ) . esc_html( $phone ) . '</p>' : '',
			$email ? '<p><span>Email:</span> <a href="' . esc_url( 'mailto:' . antispambot( $email ) ) . '">' . esc_html( antispambot( $email ) ) . '</a></p>' : '',
		)
	);

	$facebook_share = 'https://www.facebook.com/sharer/sharer.php?u=' . rawurlencode( $permalink );
	$x_share        = 'https://twitter.com/intent/tweet?url=' . rawurlencode( $permalink ) . '&text=' . rawurlencode( $agent_name );
	$linkedin_share = ! empty( $linkedin ) ? $linkedin : 'https://www.linkedin.com/sharing/share-offsite/?url=' . rawurlencode( $permalink );
	$instagram_url  = ! empty( $instagram ) ? $instagram : '#';

	if ( ! is_array( $latest_videos ) ) {
		$latest_videos = array();
	}

	ob_start();
	?>
	<section class="breadcrumbs-wrap section-dark" aria-label="Breadcrumb">
		<div class="container">
			<nav class="breadcrumbs">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a>
				<span aria-hidden="true">/</span>
				<span aria-current="page"><?php echo esc_html( $agent_name ); ?></span>
			</nav>
		</div>
	</section>

	<section class="agent-hero section-dark" id="about-agent">
		<div class="container">
			<div class="agent-profile-card" itemscope itemtype="https://schema.org/RealEstateAgent">
				<div class="host-layout">
					<div class="host-photo-wrap">
						<?php if ( ! empty( $featured_image ) ) : ?>
							<div itemprop="image">
								<?php echo $featured_image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</div>
						<?php else : ?>
							<div class="host-photo host-photo-placeholder" aria-hidden="true"></div>
						<?php endif; ?>
					</div>

					<div class="agent-info-grid">
						<div class="agent-about">
							<p class="eyebrow">About</p>
							<h1 itemprop="name"><?php echo esc_html( $agent_name ); ?></h1>

							<?php if ( ! empty( $agent_bio ) ) : ?>
								<p itemprop="description"><?php echo esc_html( $agent_bio ); ?></p>
							<?php endif; ?>

							<?php if ( ! empty( $agent_content ) ) : ?>
								<div class="agent-content" itemprop="description">
									<?php echo wp_kses_post( wpautop( $agent_content ) ); ?>
								</div>
							<?php endif; ?>

							<?php if ( ! empty( $contact_items ) ) : ?>
								<hr class="section-divider" />
								<p class="eyebrow">Contact</p>
								<div class="agent-contact">
									<?php if ( ! empty( $occupation ) ) : ?>
										<p><span class="agent-contact__label">Title:</span> <span class="agent-contact__value" itemprop="jobTitle"><?php echo esc_html( $occupation ); ?></span></p>
									<?php endif; ?>
									<?php if ( ! empty( $company ) ) : ?>
										<p><span class="agent-contact__label">Company:</span> <span class="agent-contact__value" itemprop="worksFor" itemscope itemtype="https://schema.org/Organization"><span itemprop="name"><?php echo esc_html( $company ); ?></span></span></p>
									<?php endif; ?>
									<?php if ( ! empty( $phone ) ) : ?>
										<p><span class="agent-contact__label">Phone:</span> <span class="agent-contact__value" itemprop="telephone"><?php echo esc_html( $phone ); ?></span></p>
									<?php endif; ?>
									<?php if ( ! empty( $email ) ) : ?>
										<p><span class="agent-contact__label">Email:</span> <span class="agent-contact__value" itemprop="email"><?php echo esc_html( antispambot( $email ) ); ?></span></p>
									<?php endif; ?>
								</div>
							<?php endif; ?>

							<hr class="section-divider" />
							<p class="share-label">Share This</p>
							<div class="agent-share">
								<a class="agent-share__icon" href="<?php echo esc_url( $facebook_share ); ?>" target="_blank" rel="noopener noreferrer" aria-label="Share on Facebook"><i class="bi bi-facebook" aria-hidden="true"></i></a>
								<a class="agent-share__icon<?php echo '#' === $instagram_url ? ' is-disabled' : ''; ?>" href="<?php echo esc_url( $instagram_url ); ?>"<?php echo '#' === $instagram_url ? ' aria-disabled="true"' : ' target="_blank" rel="noopener noreferrer" itemprop="sameAs"'; ?> aria-label="Share on Instagram"><i class="bi bi-instagram" aria-hidden="true"></i></a>
								<a class="agent-share__icon" href="<?php echo esc_url( $x_share ); ?>" target="_blank" rel="noopener noreferrer" aria-label="Share on X"><i class="bi bi-twitter-x" aria-hidden="true"></i></a>
								<a class="agent-share__icon" href="<?php echo esc_url( $linkedin_share ); ?>" target="_blank" rel="noopener noreferrer" aria-label="Share on LinkedIn"<?php echo ! empty( $linkedin ) ? ' itemprop="sameAs"' : ''; ?>><i class="bi bi-linkedin" aria-hidden="true"></i></a>
							</div>
							<meta itemprop="url" content="<?php echo esc_url( $permalink ); ?>" />
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section id="listings" class="listing-section section-light">
		<div class="container">
			<div class="listing-heading">
				<p class="eyebrow">Latest Episodes</p>
				<h2>Latest <?php echo esc_html( $agent_name ); ?> Real Estate Listing</h2>
				<p><?php echo esc_html( $latest_intro ); ?></p>
			</div>

			<?php if ( ! empty( $related_scenes ) ) : ?>
				<div class="agent-listing-grid">
					<?php foreach ( $related_scenes as $related_post ) : ?>
						<?php echo property_listings_render_agent_listing_card( $related_post ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php endforeach; ?>
				</div>
			<?php else : ?>
				<div class="agent-empty-state">
					<p>Assign this agent in the Scene `Agent on Video` field to populate this section.</p>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<?php

	return ob_get_clean();
}
add_shortcode( 'property_listings_agent_profile', 'property_listings_render_agent_profile_shortcode' );

function property_listings_get_seo_post_id() {
	if ( is_singular() ) {
		return get_queried_object_id();
	}

	if ( is_posts_page() ) {
		return (int) get_option( 'page_for_posts' );
	}

	return 0;
}

function property_listings_get_seo_field_value( $field_name, $post_id = 0 ) {
	if ( ! $post_id ) {
		$post_id = property_listings_get_seo_post_id();
	}

	if ( ! $post_id ) {
		return '';
	}

	if ( function_exists( 'get_field' ) ) {
		$value = get_field( $field_name, $post_id );
	} else {
		$value = get_post_meta( $post_id, $field_name, true );
	}

	if ( is_string( $value ) ) {
		return trim( wp_strip_all_tags( $value ) );
	}

	return '';
}

function property_listings_filter_document_title( $title ) {
	$seo_title = property_listings_get_seo_field_value( 'page_title' );

	if ( ! empty( $seo_title ) ) {
		return $seo_title;
	}

	return $title;
}
add_filter( 'pre_get_document_title', 'property_listings_filter_document_title' );

function property_listings_output_seo_meta_tags() {
	$post_id = property_listings_get_seo_post_id();

	if ( ! $post_id ) {
		return;
	}

	$meta_description = property_listings_get_seo_field_value( 'meta_description', $post_id );
	$meta_keywords    = property_listings_get_seo_field_value( 'meta_keywords', $post_id );

	if ( ! empty( $meta_description ) ) {
		echo '<meta name="description" content="' . esc_attr( $meta_description ) . '" />' . "\n";
	}

	if ( ! empty( $meta_keywords ) ) {
		echo '<meta name="keywords" content="' . esc_attr( $meta_keywords ) . '" />' . "\n";
	}
}
add_action( 'wp_head', 'property_listings_output_seo_meta_tags', 1 );

function property_listings_output_agent_schema() {
	if ( ! is_singular( 'agent' ) ) {
		return;
	}

	$post_id = get_queried_object_id();

	if ( ! $post_id ) {
		return;
	}

	$name        = get_the_title( $post_id );
	$description = property_listings_get_agent_meta( 'short_bio', $post_id );
	$content     = get_post_field( 'post_content', $post_id );
	$email       = property_listings_get_agent_meta( 'email', $post_id );
	$phone       = property_listings_get_agent_meta( 'phone', $post_id );
	$website     = property_listings_get_agent_meta( 'website', $post_id );
	$instagram   = property_listings_get_agent_meta( 'instagram', $post_id );
	$linkedin    = property_listings_get_agent_meta( 'linkedin', $post_id );
	$occupation  = property_listings_get_agent_meta( 'occupation', $post_id );
	$company     = property_listings_get_agent_meta( 'company', $post_id );
	$image_url   = get_the_post_thumbnail_url( $post_id, 'full' );
	$permalink   = get_permalink( $post_id );

	if ( empty( $description ) && ! empty( $content ) ) {
		$description = wp_trim_words( wp_strip_all_tags( $content ), 55 );
	}

	$schema = array(
		'@context'         => 'https://schema.org',
		'@type'            => 'RealEstateAgent',
		'@id'              => trailingslashit( $permalink ) . '#agent',
		'url'              => $permalink,
		'mainEntityOfPage' => $permalink,
		'name'             => $name,
	);

	if ( ! empty( $description ) ) {
		$schema['description'] = $description;
	}

	if ( ! empty( $image_url ) ) {
		$schema['image'] = $image_url;
	}

	if ( ! empty( $email ) ) {
		$schema['email'] = $email;
	}

	if ( ! empty( $phone ) ) {
		$schema['telephone'] = $phone;
	}

	if ( ! empty( $occupation ) ) {
		$schema['jobTitle'] = $occupation;
	}

	if ( ! empty( $website ) ) {
		$schema['sameAs'][] = esc_url_raw( $website );
	}

	if ( ! empty( $instagram ) ) {
		$schema['sameAs'][] = esc_url_raw( $instagram );
	}

	if ( ! empty( $linkedin ) ) {
		$schema['sameAs'][] = esc_url_raw( $linkedin );
	}

	if ( ! empty( $company ) ) {
		$schema['worksFor'] = array(
			'@type' => 'Organization',
			'name'  => $company,
		);
	}

	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}
add_action( 'wp_head', 'property_listings_output_agent_schema', 5 );

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
		'property-listings-bootstrap-icons',
		'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css',
		array(),
		'1.11.3'
	);

	wp_enqueue_style(
		'property-listings-main',
		get_theme_file_uri( '/assets/css/main.css' ),
		array( 'property-listings-fonts', 'property-listings-bootstrap-icons' ),
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
	$screen        = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	$screen_base   = $screen && ! empty( $screen->base ) ? $screen->base : '';
	$post_type     = $screen && ! empty( $screen->post_type ) ? $screen->post_type : '';

	$load_frontend_editor_styles = in_array(
		$screen_base,
		array( 'site-editor', 'appearance_page_gutenberg-edit-site' ),
		true
	) || in_array(
		$post_type,
		array( 'page', 'wp_template', 'wp_template_part' ),
		true
	);

	if ( ! $load_frontend_editor_styles ) {
		return;
	}

	wp_enqueue_style(
		'property-listings-editor-main',
		get_theme_file_uri( '/assets/css/main.css' ),
		array(),
		file_exists( $main_css_path ) ? filemtime( $main_css_path ) : null
	);
}
add_action( 'enqueue_block_editor_assets', 'property_listings_enqueue_editor_assets' );
