document.addEventListener("DOMContentLoaded", function () {
	const searchToggle = document.getElementById("searchToggle");
	const searchPanel = document.getElementById("searchPanel");
	const searchInput = document.getElementById("header-search-input");

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
});
