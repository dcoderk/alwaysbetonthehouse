( function( wp ) {
	const el = wp.element.createElement;
	const __ = wp.i18n.__;
	const registerBlockType = wp.blocks.registerBlockType;
	const InspectorControls = wp.blockEditor.InspectorControls;
	const MediaUpload = wp.blockEditor.MediaUpload;
	const MediaUploadCheck = wp.blockEditor.MediaUploadCheck;
	const PlainText = wp.blockEditor.PlainText;
	const PanelBody = wp.components.PanelBody;
	const Button = wp.components.Button;
	const ToggleControl = wp.components.ToggleControl;
	const TextControl = wp.components.TextControl;

	const DEFAULT_SLIDES = [
		{
			imageUrl: "https://images.unsplash.com/photo-1600585154526-990dced4db0d?auto=format&fit=crop&w=1800&q=80",
			imageAlt: "Modern luxury estate exterior at dusk"
		},
		{
			imageUrl: "https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1800&q=80",
			imageAlt: "Luxury home with dramatic interior and pool lighting"
		},
		{
			imageUrl: "https://images.unsplash.com/photo-1600047509807-ba8f99d2cdde?auto=format&fit=crop&w=1800&q=80",
			imageAlt: "Contemporary luxury residence with expansive windows"
		}
	];

	const normalizeSlides = function( slides ) {
		if ( Array.isArray( slides ) && slides.length ) {
			return slides;
		}

		return DEFAULT_SLIDES;
	};

	registerBlockType( "property-listings/hero-slider", {
		edit: function( props ) {
			const attributes = props.attributes;
			const setAttributes = props.setAttributes;
			const slides = normalizeSlides( attributes.slides );
			const logoUrl =
				attributes.logoUrl ||
				( window.propertyListingsHeroSlider && window.propertyListingsHeroSlider.defaultLogoUrl ) ||
				"";

			const updateSlide = function( index, updates ) {
				const nextSlides = slides.map( function( slide, slideIndex ) {
					if ( slideIndex !== index ) {
						return slide;
					}

					return Object.assign( {}, slide, updates );
				} );

				setAttributes( { slides: nextSlides } );
			};

			return [
				el(
					InspectorControls,
					{ key: "inspector" },
					el(
						PanelBody,
						{
							title: __( "Content", "property-listings" ),
							initialOpen: true
						},
						el( ToggleControl, {
							label: __( "Show slider text", "property-listings" ),
							checked: attributes.showText !== false,
							onChange: function( value ) {
								setAttributes( { showText: value } );
							}
						} )
					),
					el(
						PanelBody,
						{
							title: __( "Logo", "property-listings" ),
							initialOpen: true
						},
						el(
							MediaUploadCheck,
							null,
							el( MediaUpload, {
								onSelect: function( media ) {
									setAttributes( {
										logoUrl: media.url || "",
										logoAlt: media.alt || ""
									} );
								},
								allowedTypes: [ "image" ],
								render: function( obj ) {
									return el(
										Button,
										{
											variant: "secondary",
											onClick: obj.open
										},
										logoUrl ? __( "Replace logo", "property-listings" ) : __( "Select logo", "property-listings" )
									);
								}
							} )
						)
					),
					el(
						PanelBody,
						{
							title: __( "Slides", "property-listings" ),
							initialOpen: true
						},
						slides.map( function( slide, index ) {
							return el(
								"div",
								{
									key: index,
									style: {
										borderTop: index ? "1px solid #ddd" : "0",
										paddingTop: index ? "16px" : "0",
										marginTop: index ? "16px" : "0"
									}
								},
								el( "strong", null, __( "Slide ", "property-listings" ) + ( index + 1 ) ),
								el( TextControl, {
									label: __( "Image alt text", "property-listings" ),
									value: slide.imageAlt || "",
									onChange: function( value ) {
										updateSlide( index, { imageAlt: value } );
									}
								} ),
								el(
									MediaUploadCheck,
									null,
									el( MediaUpload, {
										onSelect: function( media ) {
											updateSlide( index, {
												imageUrl: media.url || "",
												imageAlt: media.alt || slide.imageAlt || ""
											} );
										},
										allowedTypes: [ "image" ],
										render: function( obj ) {
											return el(
												Button,
												{
													variant: "secondary",
													onClick: obj.open
												},
												slide.imageUrl ? __( "Replace slide image", "property-listings" ) : __( "Select slide image", "property-listings" )
											);
										}
									} )
								)
							);
						} )
					)
				),
				el(
					"section",
					{
						key: "preview",
						className: props.className + " hero section-dark hero-slider-block"
					},
					el(
						"div",
						{ className: "hero-fullwidth" },
						el(
							"div",
							{ className: "hero-carousel-full" },
							el(
								"div",
								{ className: "hero-logo-overlay", "aria-hidden": "true" },
								logoUrl ? el( "img", { src: logoUrl, alt: attributes.logoAlt || "" } ) : null
							),
							slides.map( function( slide, index ) {
								return el(
									"article",
									{
										key: index,
										className: "hero-slide" + ( index === 0 ? " is-active" : "" )
									},
									slide.imageUrl ? el( "img", { src: slide.imageUrl, alt: slide.imageAlt || "" } ) : null
								);
							} )
						)
					),
					el(
						"div",
						{ className: "hero-slider-shell" },
						attributes.showText !== false
							? el(
								"div",
								{ className: "hero-slider-copy" },
								el( PlainText, {
									tagName: "p",
									className: "hero-kicker",
									value: attributes.kicker,
									placeholder: __( "Luxury Real Estate Podcast", "property-listings" ),
									onChange: function( value ) {
										setAttributes( { kicker: value } );
									}
								} ),
								el( PlainText, {
									tagName: "h1",
									value: attributes.heading,
									placeholder: __( "Always Bet On The House", "property-listings" ),
									onChange: function( value ) {
										setAttributes( { heading: value } );
									}
								} ),
								el( PlainText, {
									tagName: "p",
									className: "hero-subtitle",
									value: attributes.subtitle,
									placeholder: __( "Add slider subtitle", "property-listings" ),
									onChange: function( value ) {
										setAttributes( { subtitle: value } );
									}
								} )
							)
							: null
					)
				)
			];
		},
		save: function() {
			return null;
		}
	} );
} )( window.wp );
