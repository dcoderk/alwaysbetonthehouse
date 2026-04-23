document.addEventListener("DOMContentLoaded", function () {
	const searchToggle = document.getElementById("searchToggle");
	const searchPanel = document.getElementById("searchPanel");
	const searchInput = document.getElementById("header-search-input");
	const siteHeader = document.querySelector(".site-header");
	const mobileMenuContent = document.querySelector(
		".site-header .site-nav .wp-block-navigation__responsive-container-content"
	);
	const mobileNavContainer = document.querySelector(
		".site-header .site-nav .wp-block-navigation__responsive-container-content .wp-block-navigation__container"
	);
	const socialList = document.querySelector(".site-header .social-list");
	const isMobileViewport = function () {
		return window.matchMedia("(max-width: 860px)").matches;
	};

	if (searchToggle && searchPanel) {
		const closeSearch = function () {
			searchPanel.hidden = true;
			searchToggle.setAttribute("aria-expanded", "false");
		};

		const openSearch = function () {
			searchPanel.hidden = false;
			searchToggle.setAttribute("aria-expanded", "true");

			window.setTimeout(function () {
				if (searchInput) {
					searchInput.focus();
				}
			}, 0);
		};

		searchToggle.addEventListener("click", function () {
			if (searchPanel.hidden) {
				openSearch();
				return;
			}

			closeSearch();
		});

		document.addEventListener("keydown", function (event) {
			if (event.key === "Escape") {
				closeSearch();
			}
		});
	}

	if (
		siteHeader &&
		mobileMenuContent &&
		mobileNavContainer &&
		socialList &&
		!mobileMenuContent.querySelector(".mobile-menu-extras")
	) {
		const extras = document.createElement("div");
		extras.className = "mobile-menu-extras";

		const socialClone = socialList.cloneNode(true);
		extras.appendChild(socialClone);

		if (searchToggle) {
			const searchClone = searchToggle.cloneNode(true);
			const searchForm = searchPanel ? searchPanel.querySelector("form") : null;
			let mobileSearchPanel = null;
			let mobileSearchInput = null;

			searchClone.removeAttribute("id");
			searchClone.setAttribute("aria-controls", "mobileSearchPanel");

			if (searchForm) {
				mobileSearchPanel = document.createElement("div");
				mobileSearchPanel.className = "mobile-search-panel";
				mobileSearchPanel.id = "mobileSearchPanel";
				mobileSearchPanel.hidden = true;
				mobileSearchPanel.appendChild(searchForm.cloneNode(true));
				mobileSearchInput = mobileSearchPanel.querySelector('input[type="search"]');

				if (mobileSearchInput) {
					mobileSearchInput.id = "mobile-header-search-input";
				}

				const mobileSearchLabel = mobileSearchPanel.querySelector("label");

				if (mobileSearchLabel && mobileSearchInput) {
					mobileSearchLabel.setAttribute("for", mobileSearchInput.id);
				}
			}

			searchClone.addEventListener("click", function () {
				if (isMobileViewport() && mobileSearchPanel) {
					const isHidden = mobileSearchPanel.hidden;
					mobileSearchPanel.hidden = !isHidden;
					searchClone.setAttribute("aria-expanded", String(isHidden));

					if (isHidden && mobileSearchInput) {
						window.setTimeout(function () {
							mobileSearchInput.focus();
						}, 0);
					}

					return;
				}

				const closeButton = siteHeader.querySelector(
					".site-nav .wp-block-navigation__responsive-container-close"
				);

				if (closeButton) {
					closeButton.click();
				}

				searchToggle.click();
			});
			extras.appendChild(searchClone);

			if (mobileSearchPanel) {
				extras.appendChild(mobileSearchPanel);
			}
		}

		mobileMenuContent.insertBefore(extras, mobileNavContainer);
	}

	const initHeroSlider = function (sliderRoot) {
		const slides = Array.from(sliderRoot.querySelectorAll(".hero-slide"));
		const thumbs = Array.from(sliderRoot.querySelectorAll(".hero-thumb"));
		const prevBtn = sliderRoot.querySelector(".hero-arrow-prev");
		const nextBtn = sliderRoot.querySelector(".hero-arrow-next");
		const heroCarousel = sliderRoot.querySelector(".hero-carousel-full");
		let activeIndex = 0;
		let autoRotate;

		const showSlide = function (index) {
			if (!slides.length) {
				return;
			}

			activeIndex = (index + slides.length) % slides.length;

			slides.forEach(function (slide, slideIndex) {
				slide.classList.toggle("is-active", slideIndex === activeIndex);
			});

			thumbs.forEach(function (thumb, thumbIndex) {
				thumb.classList.toggle("is-active", thumbIndex === activeIndex);
			});
		};

		const stopRotation = function () {
			if (autoRotate) {
				window.clearInterval(autoRotate);
			}
		};

		const startRotation = function () {
			if (slides.length < 2) {
				return;
			}

			stopRotation();
			autoRotate = window.setInterval(function () {
				showSlide(activeIndex + 1);
			}, 5000);
		};

		if (slides.length) {
			thumbs.forEach(function (thumb, index) {
				thumb.addEventListener("click", function () {
					showSlide(index);
					startRotation();
				});
			});

			showSlide(0);
			startRotation();
		}

		if (prevBtn) {
			prevBtn.addEventListener("click", function () {
				showSlide(activeIndex - 1);
				startRotation();
			});
		}

		if (nextBtn) {
			nextBtn.addEventListener("click", function () {
				showSlide(activeIndex + 1);
				startRotation();
			});
		}

		if (heroCarousel) {
			heroCarousel.addEventListener("mouseenter", stopRotation);
			heroCarousel.addEventListener("mouseleave", startRotation);
		}
	};

	document.querySelectorAll(".hero-slider-block").forEach(function (sliderRoot) {
		initHeroSlider(sliderRoot);
	});

	document.querySelectorAll(".agent-share").forEach(function (shareRoot) {
		const shareToggle = shareRoot.querySelector(".agent-share__toggle");
		const shareMenu = shareRoot.querySelector(".agent-share__menu");

		if (!shareToggle || !shareMenu) {
			return;
		}

		const closeShareMenu = function () {
			shareMenu.hidden = true;
			shareToggle.setAttribute("aria-expanded", "false");
			shareToggle.setAttribute("aria-label", "Show share buttons");
		};

		shareToggle.addEventListener("click", function () {
			const shouldOpen = shareMenu.hidden;

			shareMenu.hidden = !shouldOpen;
			shareToggle.setAttribute("aria-expanded", String(shouldOpen));
			shareToggle.setAttribute("aria-label", shouldOpen ? "Hide share buttons" : "Show share buttons");
		});

		document.addEventListener("click", function (event) {
			if (!shareRoot.contains(event.target)) {
				closeShareMenu();
			}
		});

		document.addEventListener("keydown", function (event) {
			if (event.key === "Escape") {
				closeShareMenu();
			}
		});
	});

	if (document.querySelector(".hero") && !document.querySelector(".hero-slider-block")) {
		const fallbackSliderRoot = document.querySelector(".hero");

		if (fallbackSliderRoot) {
			initHeroSlider(fallbackSliderRoot);
		}
	}
});
