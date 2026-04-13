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

function property_listings_disable_agent_editor() {
	remove_post_type_support( 'agent', 'editor' );
	remove_post_type_support( 'scene', 'editor' );
}
add_action( 'init', 'property_listings_disable_agent_editor', 100 );

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

function property_listings_render_page_intro() {
	$post_id = get_queried_object_id();

	if ( ! $post_id || ! has_excerpt( $post_id ) ) {
		return '';
	}

	$excerpt = get_the_excerpt( $post_id );

	if ( ! is_string( $excerpt ) || '' === trim( wp_strip_all_tags( $excerpt ) ) ) {
		return '';
	}

	return sprintf(
		'<p class="content-page-intro">%1$s</p><hr class="section-divider" />',
		esc_html( trim( wp_strip_all_tags( $excerpt ) ) )
	);
}
add_shortcode( 'property_listings_page_intro', 'property_listings_render_page_intro' );

function property_listings_get_search_result_summary( $post ) {
	$post = get_post( $post );

	if ( ! $post instanceof WP_Post ) {
		return '';
	}

	if ( 'agent' === $post->post_type ) {
		$bio = property_listings_get_agent_meta( 'short_bio', $post->ID );

		if ( is_string( $bio ) && '' !== trim( $bio ) ) {
			return trim( wp_strip_all_tags( $bio ) );
		}
	}

	if ( 'scene' === $post->post_type ) {
		return property_listings_get_scene_card_description( $post->ID );
	}

	$excerpt = get_the_excerpt( $post );

	if ( is_string( $excerpt ) && '' !== trim( $excerpt ) ) {
		return trim( wp_strip_all_tags( $excerpt ) );
	}

	return '';
}

function property_listings_get_search_result_type_label( $post ) {
	$post = get_post( $post );

	if ( ! $post instanceof WP_Post ) {
		return '';
	}

	if ( 'agent' === $post->post_type ) {
		return 'Agent';
	}

	if ( 'scene' === $post->post_type ) {
		return 'Episode';
	}

	return get_post_type_object( $post->post_type )->labels->singular_name ?? '';
}

function property_listings_get_search_result_meta_line( $post ) {
	$post = get_post( $post );

	if ( ! $post instanceof WP_Post ) {
		return '';
	}

	if ( 'agent' === $post->post_type ) {
		$occupation = property_listings_get_agent_meta( 'occupation', $post->ID );
		$company    = property_listings_get_agent_meta( 'company', $post->ID );
		$parts      = array_filter(
			array(
				is_string( $occupation ) ? trim( wp_strip_all_tags( $occupation ) ) : '',
				is_string( $company ) ? trim( wp_strip_all_tags( $company ) ) : '',
			)
		);

		return implode( ' · ', $parts );
	}

	if ( 'scene' === $post->post_type ) {
		return property_listings_get_scene_homepage_meta( $post->ID );
	}

	return '';
}

function property_listings_get_search_result_thumbnail_html( $post ) {
	$post = get_post( $post );

	if ( ! $post instanceof WP_Post ) {
		return '<div class="search-result-thumb search-result-thumb--placeholder" aria-hidden="true"></div>';
	}

	if ( 'scene' === $post->post_type ) {
		return property_listings_clean_media_markup(
			property_listings_get_scene_card_thumbnail( $post->ID, get_the_title( $post ) )
		);
	}

	if ( has_post_thumbnail( $post ) ) {
		return property_listings_clean_media_markup(
			get_the_post_thumbnail(
				$post,
				'medium_large',
				array(
					'alt' => get_the_title( $post ),
				)
			)
		);
	}

	return '<div class="search-result-thumb search-result-thumb--placeholder" aria-hidden="true"></div>';
}

function property_listings_render_search_results_shortcode() {
	global $wp_query;

	if ( ! is_search() ) {
		return '';
	}

	$search_query = get_search_query();

	ob_start();
	?>
	<section class="content-page-shell section-light">
		<div class="container">
			<div class="content-page-card reveal search-page-card">
				<h1 class="search-page-title">Search Results</h1>
				<?php if ( '' !== $search_query ) : ?>
					<p class="content-page-intro">Results for "<?php echo esc_html( $search_query ); ?>" across agents and episodes.</p>
				<?php endif; ?>

				<hr class="section-divider" />

				<?php if ( ! empty( $wp_query->posts ) ) : ?>
					<div class="search-results-list">
						<?php foreach ( $wp_query->posts as $post ) : ?>
							<?php
							$type_label = property_listings_get_search_result_type_label( $post );
							$meta_line  = property_listings_get_search_result_meta_line( $post );
							$summary    = property_listings_get_search_result_summary( $post );
							$thumb_html = property_listings_get_search_result_thumbnail_html( $post );
							$link_url   = get_permalink( $post );
							$link_attrs = '';

							if ( 'scene' === $post->post_type ) {
								$link_data = property_listings_get_scene_card_link_data( $post->ID );
								$link_url  = $link_data['url'];
								$link_attrs = $link_data['target'] . $link_data['rel'];
							}
							?>
							<article class="search-result-card">
								<a class="search-result-media" href="<?php echo esc_url( $link_url ); ?>"<?php echo $link_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
									<?php echo $thumb_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</a>
								<div class="search-result-content">
									<?php if ( ! empty( $type_label ) ) : ?>
										<p class="meta"><?php echo esc_html( $type_label ); ?></p>
									<?php endif; ?>
									<h2><a href="<?php echo esc_url( $link_url ); ?>"<?php echo $link_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( get_the_title( $post ) ); ?></a></h2>
									<?php if ( ! empty( $meta_line ) ) : ?>
										<p class="search-result-meta-line"><?php echo esc_html( $meta_line ); ?></p>
									<?php endif; ?>
									<?php if ( ! empty( $summary ) ) : ?>
										<p><?php echo esc_html( $summary ); ?></p>
									<?php endif; ?>
								</div>
							</article>
						<?php endforeach; ?>
					</div>
				<?php else : ?>
					<div class="search-empty-state">
						<p>No matching agents or episodes were found.</p>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<?php

	return trim( ob_get_clean() );
}
add_shortcode( 'property_listings_search_results', 'property_listings_render_search_results_shortcode' );

function property_listings_get_agent_meta( $field_name, $post_id ) {
	if ( function_exists( 'get_field' ) ) {
		return get_field( $field_name, $post_id );
	}

	return get_post_meta( $post_id, $field_name, true );
}

function property_listings_get_current_view_meta( $field_name, $default = '' ) {
	$post_id = get_queried_object_id();

	if ( ! $post_id ) {
		return $default;
	}

	$value = property_listings_get_agent_meta( $field_name, $post_id );

	if ( is_string( $value ) && '' !== trim( $value ) ) {
		return trim( wp_strip_all_tags( $value ) );
	}

	return $default;
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
	$related_meta_label = '';
	$related_summary    = '';
	$open_in_new_window = false;

	if ( 'scene' === $post->post_type ) {
		$video_link        = property_listings_get_agent_meta( 'video_link', $post->ID );
		$video_screenshot  = property_listings_get_agent_meta( 'video_screenshot', $post->ID );
		$open_in_new_window = (bool) property_listings_get_agent_meta( 'open_in_new_window', $post->ID );
		$scene_taxonomy    = property_listings_get_scene_taxonomy();
		$related_meta_label = property_listings_get_scene_card_term_label( $post->ID, $scene_taxonomy );
		$related_summary    = get_the_excerpt( $post );

		if ( '' === trim( $related_summary ) ) {
			$related_summary = wp_trim_words( wp_strip_all_tags( get_post_field( 'post_content', $post->ID ) ), 16 );
		}

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

	$thumb_markup   = ! empty( $related_thumb ) ? property_listings_clean_media_markup( $related_thumb ) : '<div class="agent-listing-thumb agent-listing-thumb--placeholder" aria-hidden="true"></div>';
	$location_markup = ! empty( $related_location ) ? '<p>' . esc_html( $related_location ) . '</p>' : '';
	$meta_markup    = ! empty( $related_meta_label ) ? '<p class="meta">' . esc_html( $related_meta_label ) . '</p>' : '';
	$summary_markup = ! empty( $related_summary ) ? '<p>' . esc_html( $related_summary ) . '</p>' : $location_markup;

	return '<article class="agent-listing-card"><a href="' . esc_url( $related_link ) . '" class="agent-listing-thumb"' . $link_target . '>' . $thumb_markup . '</a><div class="agent-listing">' . $meta_markup . '<h3><a href="' . esc_url( $related_link ) . '"' . $link_target . '>' . esc_html( $related_title ) . '</a></h3>' . $summary_markup . '</div></article>';
}

function property_listings_get_scene_taxonomy() {
	$taxonomies = get_object_taxonomies( 'scene', 'objects' );

	if ( empty( $taxonomies ) || ! is_array( $taxonomies ) ) {
		return null;
	}

	$preferred_taxonomies = array(
		'scene_category',
		'scene_categories',
		'episode_category',
		'category',
	);

	foreach ( $preferred_taxonomies as $taxonomy_name ) {
		if ( isset( $taxonomies[ $taxonomy_name ] ) && ! empty( $taxonomies[ $taxonomy_name ]->public ) ) {
			return $taxonomies[ $taxonomy_name ];
		}
	}

	foreach ( $taxonomies as $taxonomy ) {
		if ( empty( $taxonomy->public ) || 'post_format' === $taxonomy->name ) {
			continue;
		}

		if ( ! empty( $taxonomy->hierarchical ) ) {
			return $taxonomy;
		}
	}

	foreach ( $taxonomies as $taxonomy ) {
		if ( ! empty( $taxonomy->public ) && 'post_format' !== $taxonomy->name ) {
			return $taxonomy;
		}
	}

	return null;
}

function property_listings_get_scene_selected_term_slug( $taxonomy ) {
	if ( empty( $taxonomy ) || empty( $taxonomy->name ) ) {
		return '';
	}

	$selected_term = isset( $_GET['episode_category'] ) ? sanitize_title( wp_unslash( $_GET['episode_category'] ) ) : '';

	if ( '' === $selected_term ) {
		return '';
	}

	$term = get_term_by( 'slug', $selected_term, $taxonomy->name );

	return $term instanceof WP_Term ? $term->slug : '';
}

function property_listings_filter_scene_archive_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() || ! $query->is_post_type_archive( 'scene' ) ) {
		return;
	}

	$taxonomy      = property_listings_get_scene_taxonomy();
	$selected_term = property_listings_get_scene_selected_term_slug( $taxonomy );

	if ( empty( $taxonomy ) || '' === $selected_term ) {
		return;
	}

	$query->set(
		'tax_query',
		array(
			array(
				'taxonomy' => $taxonomy->name,
				'field'    => 'slug',
				'terms'    => $selected_term,
			),
		)
	);
}
add_action( 'pre_get_posts', 'property_listings_filter_scene_archive_query' );

function property_listings_filter_search_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() || ! $query->is_search() ) {
		return;
	}

	$query->set( 'post_type', array( 'agent', 'scene' ) );
}
add_action( 'pre_get_posts', 'property_listings_filter_search_query' );

function property_listings_search_join_postmeta( $join, $query ) {
	global $wpdb;

	if ( is_admin() || ! $query->is_main_query() || ! $query->is_search() ) {
		return $join;
	}

	if ( false === strpos( $join, 'property_listings_search_pm' ) ) {
		$join .= " LEFT JOIN {$wpdb->postmeta} AS property_listings_search_pm ON ({$wpdb->posts}.ID = property_listings_search_pm.post_id)";
	}

	return $join;
}
add_filter( 'posts_join', 'property_listings_search_join_postmeta', 10, 2 );

function property_listings_search_where_postmeta( $where, $query ) {
	global $wpdb;

	if ( is_admin() || ! $query->is_main_query() || ! $query->is_search() ) {
		return $where;
	}

	$search_term = $query->get( 's' );

	if ( ! is_string( $search_term ) || '' === trim( $search_term ) ) {
		return $where;
	}

	$like      = '%' . $wpdb->esc_like( $search_term ) . '%';
	$meta_keys = array( 'short_bio', 'occupation', 'company' );
	$key_sql   = "'" . implode( "','", array_map( 'esc_sql', $meta_keys ) ) . "'";

	$meta_search_sql = $wpdb->prepare(
		"({$wpdb->posts}.post_type = 'agent' AND property_listings_search_pm.meta_key IN ({$key_sql}) AND property_listings_search_pm.meta_value LIKE %s)",
		$like
	);

	$search_sql = $wpdb->prepare(
		"({$wpdb->posts}.post_title LIKE %s)",
		$like
	);

	if ( false !== strpos( $where, $search_sql ) ) {
		$where = str_replace( $search_sql, '(' . $search_sql . ' OR ' . $meta_search_sql . ')', $where );
	}

	return $where;
}
add_filter( 'posts_where', 'property_listings_search_where_postmeta', 10, 2 );

function property_listings_search_distinct_results( $distinct, $query ) {
	if ( is_admin() || ! $query->is_main_query() || ! $query->is_search() ) {
		return $distinct;
	}

	return 'DISTINCT';
}
add_filter( 'posts_distinct', 'property_listings_search_distinct_results', 10, 2 );

function property_listings_get_scene_card_link_data( $post_id ) {
	$link_data = array(
		'url'       => get_permalink( $post_id ),
		'target'    => '',
		'rel'       => '',
		'is_remote' => false,
	);

	$video_link         = property_listings_get_agent_meta( 'video_link', $post_id );
	$open_in_new_window = (bool) property_listings_get_agent_meta( 'open_in_new_window', $post_id );

	if ( is_string( $video_link ) && '' !== trim( $video_link ) ) {
		$link_data['url']       = trim( $video_link );
		$link_data['is_remote'] = true;
	}

	if ( $open_in_new_window || $link_data['is_remote'] ) {
		$link_data['target'] = ' target="_blank"';
		$link_data['rel']    = ' rel="noopener noreferrer"';
	}

	return $link_data;
}

function property_listings_clean_media_markup( $markup ) {
	if ( ! is_string( $markup ) || '' === $markup ) {
		return $markup;
	}

	$markup = preg_replace( '#^\s*(<br\s*/?>\s*)+#i', '', $markup );
	$markup = preg_replace( '#(<a[^>]*>)\s*(<br\s*/?>\s*)+#i', '$1', $markup );

	return trim( $markup );
}

function property_listings_get_scene_card_thumbnail( $post_id, $title ) {
	$video_screenshot = property_listings_get_agent_meta( 'video_screenshot', $post_id );

	if ( is_array( $video_screenshot ) && ! empty( $video_screenshot['ID'] ) ) {
		$video_screenshot = (int) $video_screenshot['ID'];
	}

	if ( is_numeric( $video_screenshot ) && ! empty( $video_screenshot ) ) {
		return property_listings_clean_media_markup(
			wp_get_attachment_image(
			(int) $video_screenshot,
			'large',
			false,
			array(
				'alt' => $title,
			)
		)
		);
	}

	if ( is_array( $video_screenshot ) && ! empty( $video_screenshot['url'] ) ) {
		return property_listings_clean_media_markup(
			sprintf(
			'<img src="%1$s" alt="%2$s" />',
			esc_url( $video_screenshot['url'] ),
			esc_attr( $title )
			)
		);
	}

	if ( is_string( $video_screenshot ) && '' !== trim( $video_screenshot ) ) {
		return property_listings_clean_media_markup(
			sprintf(
			'<img src="%1$s" alt="%2$s" />',
			esc_url( $video_screenshot ),
			esc_attr( $title )
			)
		);
	}

	if ( has_post_thumbnail( $post_id ) ) {
		return property_listings_clean_media_markup(
			get_the_post_thumbnail(
			$post_id,
			'large',
			array(
				'alt' => $title,
			)
			)
		);
	}

	return '<div class="scene-thumb scene-thumb--placeholder" aria-hidden="true"></div>';
}

function property_listings_get_scene_card_term_label( $post_id, $taxonomy ) {
	if ( empty( $taxonomy ) || empty( $taxonomy->name ) ) {
		return '';
	}

	$terms = get_the_terms( $post_id, $taxonomy->name );

	if ( is_wp_error( $terms ) || empty( $terms ) ) {
		return '';
	}

	return $terms[0]->name;
}

function property_listings_get_scene_card_description( $post_id ) {
	$description_fields = array(
		'short_description',
		'description',
		'scene_description',
		'episode_description',
		'summary',
	);

	foreach ( $description_fields as $field_name ) {
		$value = property_listings_get_agent_meta( $field_name, $post_id );

		if ( is_string( $value ) && '' !== trim( $value ) ) {
			return trim( wp_strip_all_tags( $value ) );
		}
	}

	$excerpt = get_post_field( 'post_excerpt', $post_id );

	if ( is_string( $excerpt ) && '' !== trim( $excerpt ) ) {
		return trim( wp_strip_all_tags( $excerpt ) );
	}

	$location = property_listings_get_related_item_location( $post_id );

	if ( '' !== trim( $location ) ) {
		return $location;
	}

	return 'Episode details coming soon.';
}

function property_listings_render_scene_archive_pagination( $selected_term ) {
	global $wp_query;

	if ( empty( $wp_query ) || $wp_query->max_num_pages < 2 ) {
		return '';
	}

	$links = paginate_links(
		array(
			'total'     => (int) $wp_query->max_num_pages,
			'current'   => max( 1, get_query_var( 'paged', 1 ) ),
			'type'      => 'array',
			'mid_size'  => 1,
			'end_size'  => 1,
			'prev_text' => __( 'Previous', 'property-listings' ),
			'next_text' => __( 'Next', 'property-listings' ),
			'add_args'  => $selected_term ? array( 'episode_category' => $selected_term ) : array(),
		)
	);

	if ( empty( $links ) ) {
		return '';
	}

	$items = array();

	foreach ( $links as $link ) {
		if ( false !== strpos( $link, 'dots' ) ) {
			$items[] = '<span class="pagination-ellipsis" aria-hidden="true">...</span>';
			continue;
		}

		$classes = 'pagination-link';

		if ( false !== strpos( $link, 'current' ) ) {
			$classes .= ' is-active';
		}

		if ( false !== strpos( $link, 'prev' ) ) {
			$classes .= ' pagination-prev';
		}

		if ( false !== strpos( $link, 'next' ) ) {
			$classes .= ' pagination-next';
		}

		$items[] = preg_replace( '/class="[^"]*"/', 'class="' . esc_attr( $classes ) . '"', $link, 1 );
	}

	return '<nav class="pagination reveal" aria-label="Scenes pagination">' . implode( '', $items ) . '</nav>';
}

function property_listings_get_scene_archive_source_page() {
	$page_candidates = array(
		(int) apply_filters( 'property_listings_scene_archive_page_id', 10 ),
		get_page_by_path( 'episodes' ),
		get_page_by_path( 'episode-page' ),
		get_page_by_title( 'Episodes' ),
		get_page_by_title( 'Episode Page' ),
	);

	foreach ( $page_candidates as $candidate ) {
		if ( $candidate instanceof WP_Post && 'page' === $candidate->post_type ) {
			return $candidate;
		}

		if ( is_numeric( $candidate ) && $candidate > 0 ) {
			$page = get_post( (int) $candidate );

			if ( $page instanceof WP_Post && 'page' === $page->post_type ) {
				return $page;
			}
		}
	}

	return null;
}

function property_listings_get_scene_archive_text( $field_name, $default = '', $page_id = 0 ) {
	if ( $page_id ) {
		$value = property_listings_get_agent_meta( $field_name, $page_id );

		if ( is_string( $value ) && '' !== trim( $value ) ) {
			return trim( wp_strip_all_tags( $value ) );
		}
	}

	return $default;
}

function property_listings_collect_scene_archive_text_from_blocks( $blocks, &$text_parts ) {
	foreach ( $blocks as $block ) {
		if ( ! is_array( $block ) ) {
			continue;
		}

		$block_name = isset( $block['blockName'] ) ? $block['blockName'] : '';
		$inner_html = isset( $block['innerHTML'] ) ? trim( (string) $block['innerHTML'] ) : '';

		if ( in_array( $block_name, array( 'core/paragraph', 'core/heading' ), true ) && '' !== $inner_html ) {
			$text = trim( wp_strip_all_tags( $inner_html ) );

			if ( '' !== $text ) {
				$text_parts[] = array(
					'type' => 'core/heading' === $block_name ? 'heading' : 'paragraph',
					'text' => $text,
				);
			}
		}

		if ( ! empty( $block['innerBlocks'] ) && is_array( $block['innerBlocks'] ) ) {
			property_listings_collect_scene_archive_text_from_blocks( $block['innerBlocks'], $text_parts );
		}
	}
}

function property_listings_get_scene_archive_page_content_parts( $page_id ) {
	$defaults = array(
		'eyebrow'        => 'Real Estate Podcast',
		'heading'        => 'Our Real Estate Podcast Videos',
		'intro'          => 'A clean archive page for browsing podcast videos, spotlight scenes, and premium property moments from the show.',
		'section_heading'=> 'Episodes',
	);

	if ( ! $page_id ) {
		return $defaults;
	}

	$page_content = get_post_field( 'post_content', $page_id );

	if ( ! is_string( $page_content ) || '' === trim( $page_content ) ) {
		return $defaults;
	}

	$text_parts = array();
	property_listings_collect_scene_archive_text_from_blocks( parse_blocks( $page_content ), $text_parts );

	if ( ! empty( $text_parts[0]['text'] ) ) {
		$defaults['eyebrow'] = $text_parts[0]['text'];
	}

	if ( ! empty( $text_parts[1]['text'] ) ) {
		$defaults['heading'] = $text_parts[1]['text'];
	}

	if ( ! empty( $text_parts[2]['text'] ) ) {
		$defaults['intro'] = $text_parts[2]['text'];
	}

	if ( ! empty( $text_parts[3]['text'] ) ) {
		$defaults['section_heading'] = $text_parts[3]['text'];
	}

	return $defaults;
}

function property_listings_render_scene_archive_shortcode() {
	if ( ! is_post_type_archive( 'scene' ) ) {
		return '';
	}

	global $wp_query;

	$taxonomy         = property_listings_get_scene_taxonomy();
	$selected_term    = property_listings_get_scene_selected_term_slug( $taxonomy );
	$current_page     = max( 1, get_query_var( 'paged', 1 ) );
	$total_pages      = max( 1, (int) $wp_query->max_num_pages );
	$available_terms  = array();
	$pagination_markup = property_listings_render_scene_archive_pagination( $selected_term );
	$source_page      = property_listings_get_scene_archive_source_page();
	$source_page_id   = $source_page instanceof WP_Post ? (int) $source_page->ID : 0;
	$page_content_parts = property_listings_get_scene_archive_page_content_parts( $source_page_id );
	$page_title       = $page_content_parts['heading'];
	$page_eyebrow     = $page_content_parts['eyebrow'];
	$section_heading  = $page_content_parts['section_heading'];
	$intro_copy       = $page_content_parts['intro'];

	if ( ! empty( $taxonomy ) && ! empty( $taxonomy->name ) ) {
		$available_terms = get_terms(
			array(
				'taxonomy'   => $taxonomy->name,
				'hide_empty' => true,
			)
		);

		if ( is_wp_error( $available_terms ) ) {
			$available_terms = array();
		}
	}

	ob_start();
	?>
	<section class="episodes-page-intro section-light">
		<div class="container">
			<div class="episodes-page-head reveal">
				<div class="episodes-title-block">
					<p class="eyebrow"><?php echo esc_html( $page_eyebrow ); ?></p>
					<h1><?php echo esc_html( $page_title ); ?></h1>
				</div>

				<?php if ( ! empty( $available_terms ) && ! empty( $taxonomy ) ) : ?>
					<form class="episodes-filter-panel" method="get" action="<?php echo esc_url( get_post_type_archive_link( 'scene' ) ); ?>">
						<label for="episodeCategory" class="episodes-filter-label"><?php echo esc_html( $taxonomy->labels->name ); ?></label>
						<div class="episodes-select-wrap">
							<select id="episodeCategory" name="episode_category" onchange="this.form.submit()">
								<option value=""><?php esc_html_e( 'All Categories', 'property-listings' ); ?></option>
								<?php foreach ( $available_terms as $term ) : ?>
									<option value="<?php echo esc_attr( $term->slug ); ?>" <?php selected( $selected_term, $term->slug ); ?>><?php echo esc_html( $term->name ); ?></option>
								<?php endforeach; ?>
							</select>
							<i class="bi bi-chevron-down" aria-hidden="true"></i>
						</div>
					</form>
				<?php endif; ?>
			</div>

			<p class="episodes-intro-copy reveal"><?php echo esc_html( $intro_copy ); ?></p>
		</div>
	</section>

	<section class="scene-library section-light alt-surface">
		<div class="container">
			<div class="scene-library-bar reveal">
				<h2><?php echo esc_html( $section_heading ); ?></h2>
				<p><?php echo esc_html( sprintf( 'Page %1$d of %2$d', $current_page, $total_pages ) ); ?></p>
			</div>

			<?php if ( have_posts() ) : ?>
				<div class="scene-grid">
					<?php
					while ( have_posts() ) :
						the_post();

						$post_id     = get_the_ID();
						$title       = get_the_title();
						$link_data   = property_listings_get_scene_card_link_data( $post_id );
						$term_label  = property_listings_get_scene_card_term_label( $post_id, $taxonomy );
						$thumb_html  = property_listings_clean_media_markup( property_listings_get_scene_card_thumbnail( $post_id, $title ) );
						$description = property_listings_get_scene_card_description( $post_id );
						?>
						<article class="scene-card reveal">
							<a href="<?php echo esc_url( $link_data['url'] ); ?>" class="scene-thumb"<?php echo $link_data['target']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo $link_data['rel']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
								<?php echo $thumb_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</a>
							<div class="scene-card-content">
								<?php if ( ! empty( $term_label ) ) : ?>
									<p class="meta"><?php echo esc_html( $term_label ); ?></p>
								<?php endif; ?>
								<h3><a href="<?php echo esc_url( $link_data['url'] ); ?>"<?php echo $link_data['target']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo $link_data['rel']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $title ); ?></a></h3>
								<?php if ( ! empty( $description ) ) : ?>
									<p><?php echo esc_html( $description ); ?></p>
								<?php endif; ?>
							</div>
						</article>
					<?php endwhile; ?>
				</div>

				<?php echo $pagination_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php else : ?>
				<div class="scene-empty-state reveal">
					<p>No episodes found yet. Add `scene` entries to populate this archive.</p>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<?php
	wp_reset_postdata();

	$output = shortcode_unautop( trim( ob_get_clean() ) );
	$output = preg_replace( '#<p>\s*</p>#i', '', $output );
	$output = preg_replace( '#<p>(\s|&nbsp;)*</p>#i', '', $output );
	$output = preg_replace( '#<br\s*/?>\s*(?=<img)#i', '', $output );
	$output = preg_replace( '#<a([^>]*)>\s*<br\s*/?>#i', '<a$1>', $output );

	return $output;
}
add_shortcode( 'property_listings_scene_archive', 'property_listings_render_scene_archive_shortcode' );

function property_listings_get_scene_platform_label( $url ) {
	if ( ! is_string( $url ) || '' === trim( $url ) ) {
		return '';
	}

	$host = wp_parse_url( trim( $url ), PHP_URL_HOST );

	if ( ! is_string( $host ) || '' === $host ) {
		return '';
	}

	$host = strtolower( $host );

	if ( false !== strpos( $host, 'youtube.com' ) || false !== strpos( $host, 'youtu.be' ) ) {
		return 'YouTube';
	}

	if ( false !== strpos( $host, 'vimeo.com' ) ) {
		return 'Vimeo';
	}

	return 'Watch';
}

function property_listings_get_scene_homepage_meta( $post_id ) {
	$meta_fields = array(
		'episode_label',
		'episode_number',
		'episode_code',
		'season_episode',
		'display_label',
	);

	foreach ( $meta_fields as $field_name ) {
		$value = property_listings_get_agent_meta( $field_name, $post_id );

		if ( is_string( $value ) && '' !== trim( $value ) ) {
			return trim( wp_strip_all_tags( $value ) );
		}
	}

	return get_the_date( 'F j, Y', $post_id );
}

function property_listings_get_scene_price( $post_id ) {
	$price_fields = array(
		'price',
		'listing_price',
		'property_price',
		'asking_price',
	);

	foreach ( $price_fields as $field_name ) {
		$value = property_listings_get_agent_meta( $field_name, $post_id );

		if ( is_string( $value ) && '' !== trim( $value ) ) {
			return trim( wp_strip_all_tags( $value ) );
		}
	}

	return '';
}

function property_listings_get_scene_agent_data( $post_id ) {
	$agent_value = property_listings_get_agent_meta( 'agent_on_video', $post_id );
	$agent_id    = 0;

	if ( is_array( $agent_value ) ) {
		$first = reset( $agent_value );

		if ( $first instanceof WP_Post ) {
			$agent_id = (int) $first->ID;
		} elseif ( is_numeric( $first ) ) {
			$agent_id = (int) $first;
		}
	} elseif ( $agent_value instanceof WP_Post ) {
		$agent_id = (int) $agent_value->ID;
	} elseif ( is_numeric( $agent_value ) ) {
		$agent_id = (int) $agent_value;
	}

	if ( ! $agent_id ) {
		return array(
			'name' => '',
			'url'  => '',
		);
	}

	return array(
		'name' => get_the_title( $agent_id ),
		'url'  => get_permalink( $agent_id ),
	);
}

function property_listings_render_latest_episodes_section( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'heading'     => 'Latest Episodes',
			'description' => 'Large 700x394 style thumbnails only. No video player. Every episode links out to YouTube or Vimeo.',
			'buttonText'  => 'All Episodes',
			'className'   => '',
		)
	);

	$episodes_query = new WP_Query(
		array(
			'post_type'      => 'scene',
			'post_status'    => 'publish',
			'posts_per_page' => 3,
			'orderby'        => 'date',
			'order'          => 'DESC',
		)
	);

	ob_start();
	?>
	<section class="episodes section-grey <?php echo esc_attr( trim( (string) $args['className'] ) ); ?>">
		<div class="container">
			<div class="section-heading reveal">
				<?php if ( ! empty( $args['heading'] ) ) : ?>
					<h2><?php echo esc_html( $args['heading'] ); ?></h2>
				<?php endif; ?>
				<?php if ( ! empty( $args['description'] ) ) : ?>
					<p><?php echo esc_html( $args['description'] ); ?></p>
				<?php endif; ?>
			</div>

			<?php if ( $episodes_query->have_posts() ) : ?>
				<div class="episode-list">
					<?php
					while ( $episodes_query->have_posts() ) :
						$episodes_query->the_post();

						$post_id        = get_the_ID();
						$title          = get_the_title();
						$link_data      = property_listings_get_scene_card_link_data( $post_id );
						$thumb_html     = property_listings_clean_media_markup( property_listings_get_scene_card_thumbnail( $post_id, $title ) );
						$description    = property_listings_get_scene_card_description( $post_id );
						$platform_label = property_listings_get_scene_platform_label( $link_data['url'] );
						$meta_label     = property_listings_get_scene_homepage_meta( $post_id );
						?>
						<article class="episode-row reveal">
							<a class="episode-thumb" href="<?php echo esc_url( $link_data['url'] ); ?>"<?php echo $link_data['target']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo $link_data['rel']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
								<?php echo $thumb_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<?php if ( ! empty( $platform_label ) ) : ?>
									<span class="platform-chip"><?php echo esc_html( $platform_label ); ?></span>
								<?php endif; ?>
							</a>
							<div class="episode-body">
								<?php if ( ! empty( $meta_label ) ) : ?>
									<p class="meta"><?php echo esc_html( $meta_label ); ?></p>
								<?php endif; ?>
								<h2><?php echo esc_html( $title ); ?></h2>
								<p><?php echo esc_html( $description ); ?></p>
								<a href="<?php echo esc_url( $link_data['url'] ); ?>"<?php echo $link_data['target']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo $link_data['rel']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="text-link">Watch Episode &rarr;</a>
							</div>
						</article>
					<?php endwhile; ?>
				</div>
			<?php else : ?>
				<div class="episode-list">
					<article class="episode-row reveal">
						<div class="episode-body">
							<h2>Latest Episodes Coming Soon</h2>
							<p>Add `scene` posts to populate this section automatically.</p>
						</div>
					</article>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $args['buttonText'] ) ) : ?>
				<div class="center-button reveal">
					<a href="<?php echo esc_url( get_post_type_archive_link( 'scene' ) ); ?>" class="btn btn-dark"><?php echo esc_html( $args['buttonText'] ); ?></a>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<?php
	wp_reset_postdata();

	return trim( ob_get_clean() );
}

function property_listings_render_latest_episodes_shortcode() {
	return property_listings_render_latest_episodes_section();
}
add_shortcode( 'property_listings_latest_episodes', 'property_listings_render_latest_episodes_shortcode' );

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

	if ( function_exists( 'is_home' ) && is_home() ) {
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
	$email       = property_listings_get_agent_meta( 'email', $post_id );
	$phone       = property_listings_get_agent_meta( 'phone', $post_id );
	$website     = property_listings_get_agent_meta( 'website', $post_id );
	$instagram   = property_listings_get_agent_meta( 'instagram', $post_id );
	$linkedin    = property_listings_get_agent_meta( 'linkedin', $post_id );
	$occupation  = property_listings_get_agent_meta( 'occupation', $post_id );
	$company     = property_listings_get_agent_meta( 'company', $post_id );
	$image_url   = get_the_post_thumbnail_url( $post_id, 'full' );
	$permalink   = get_permalink( $post_id );

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
	$hero_slider_editor_js_path       = get_theme_file_path( '/blocks/hero-slider/editor.js' );
	$host_section_editor_js_path      = get_theme_file_path( '/blocks/host-section/editor.js' );
	$latest_episodes_editor_js_path   = get_theme_file_path( '/blocks/latest-episodes/editor.js' );
	$featured_episodes_editor_js_path = get_theme_file_path( '/blocks/featured-episodes/editor.js' );
	$subscribe_panel_editor_js_path   = get_theme_file_path( '/blocks/subscribe-panel/editor.js' );
	$header_socials_editor_js_path    = get_theme_file_path( '/blocks/header-socials/editor.js' );

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

	wp_register_script(
		'property-listings-latest-episodes-editor',
		get_theme_file_uri( '/blocks/latest-episodes/editor.js' ),
		array( 'wp-blocks', 'wp-block-editor', 'wp-components', 'wp-element', 'wp-i18n' ),
		file_exists( $latest_episodes_editor_js_path ) ? filemtime( $latest_episodes_editor_js_path ) : null,
		true
	);

	wp_register_script(
		'property-listings-featured-episodes-editor',
		get_theme_file_uri( '/blocks/featured-episodes/editor.js' ),
		array( 'wp-blocks', 'wp-block-editor', 'wp-components', 'wp-element', 'wp-i18n' ),
		file_exists( $featured_episodes_editor_js_path ) ? filemtime( $featured_episodes_editor_js_path ) : null,
		true
	);

	wp_register_script(
		'property-listings-subscribe-panel-editor',
		get_theme_file_uri( '/blocks/subscribe-panel/editor.js' ),
		array( 'wp-blocks', 'wp-block-editor', 'wp-components', 'wp-element', 'wp-i18n' ),
		file_exists( $subscribe_panel_editor_js_path ) ? filemtime( $subscribe_panel_editor_js_path ) : null,
		true
	);

	wp_register_script(
		'property-listings-header-socials-editor',
		get_theme_file_uri( '/blocks/header-socials/editor.js' ),
		array( 'wp-blocks', 'wp-block-editor', 'wp-components', 'wp-element', 'wp-i18n' ),
		file_exists( $header_socials_editor_js_path ) ? filemtime( $header_socials_editor_js_path ) : null,
		true
	);

	register_block_type( get_theme_file_path( '/blocks/hero-slider' ) );
	register_block_type( get_theme_file_path( '/blocks/host-section' ) );
	register_block_type( get_theme_file_path( '/blocks/latest-episodes' ) );
	register_block_type( get_theme_file_path( '/blocks/featured-episodes' ) );
	register_block_type( get_theme_file_path( '/blocks/subscribe-panel' ) );
	register_block_type( get_theme_file_path( '/blocks/header-socials' ) );
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

function property_listings_strip_empty_paragraphs_from_markup( $content ) {
	if ( ! is_string( $content ) || '' === $content ) {
		return $content;
	}

	$content = preg_replace( '#<p>(?:\s|&nbsp;|<br\s*/?>)*</p>#i', '', $content );

	return $content;
}

function property_listings_filter_rendered_block_markup( $block_content, $block ) {
	if ( empty( $block_content ) || empty( $block['blockName'] ) ) {
		return $block_content;
	}

	$allowed_blocks = array(
		'core/template-part',
		'core/shortcode',
		'core/group',
		'core/post-content',
	);

	if ( ! in_array( $block['blockName'], $allowed_blocks, true ) ) {
		return $block_content;
	}

	return property_listings_strip_empty_paragraphs_from_markup( $block_content );
}
add_filter( 'render_block', 'property_listings_filter_rendered_block_markup', 10, 2 );
