<?php
/**
 * Title: Hero Slider
 * Slug: property-listing/hero-slider
 * Categories: property-listing, banner
 * Inserter: true
 * Description: Editable homepage hero slider for Always Bet On The House.
 *
 * @package PropertyListing
 */
?>
<!-- wp:group {"tagName":"section","className":"hero section-dark","layout":{"type":"default"}} -->
<section class="wp-block-group hero section-dark">
	<!-- wp:html -->
	<div class="hero-fullwidth">
		<div class="hero-carousel-full" id="heroCarousel">
			<div class="hero-logo-overlay" aria-hidden="true">
				<img src="/wp-content/themes/property-listing/assets/images/logo.png" alt="" />
			</div>

			<article class="hero-slide is-active">
				<img src="https://images.unsplash.com/photo-1600585154526-990dced4db0d?auto=format&amp;fit=crop&amp;w=1800&amp;q=80" alt="Modern luxury estate exterior at dusk" />
			</article>

			<article class="hero-slide">
				<img src="https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&amp;fit=crop&amp;w=1800&amp;q=80" alt="Luxury home with dramatic interior and pool lighting" />
			</article>

			<article class="hero-slide">
				<img src="https://images.unsplash.com/photo-1600047509807-ba8f99d2cdde?auto=format&amp;fit=crop&amp;w=1800&amp;q=80" alt="Contemporary luxury residence with expansive windows" />
			</article>

			<button class="hero-arrow hero-arrow-prev" type="button" aria-label="Show previous slide">
				<span aria-hidden="true">&larr;</span>
			</button>
			<button class="hero-arrow hero-arrow-next" type="button" aria-label="Show next slide">
				<span aria-hidden="true">&rarr;</span>
			</button>
		</div>
	</div>
	<!-- /wp:html -->

	<!-- wp:group {"className":"hero-slider-shell","layout":{"type":"constrained"}} -->
	<div class="wp-block-group hero-slider-shell">
		<!-- wp:group {"className":"hero-slider-copy","layout":{"type":"constrained"}} -->
		<div class="wp-block-group hero-slider-copy">
			<!-- wp:paragraph {"className":"hero-kicker"} -->
			<p class="hero-kicker">Luxury Real Estate Podcast</p>
			<!-- /wp:paragraph -->

			<!-- wp:heading {"level":1} -->
			<h1 class="wp-block-heading">Always Bet On The House</h1>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"className":"hero-subtitle"} -->
			<p class="hero-subtitle">Showcasing standout homes, the agents behind them, and the stories that make each listing worth the spotlight.</p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</section>
<!-- /wp:group -->
