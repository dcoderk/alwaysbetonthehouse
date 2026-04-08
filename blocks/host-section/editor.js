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
	const TextControl = wp.components.TextControl;
	const ToggleControl = wp.components.ToggleControl;

	registerBlockType( "property-listings/host-section", {
		edit: function( props ) {
			const attributes = props.attributes;
			const setAttributes = props.setAttributes;

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
							label: __( "Show section title", "property-listings" ),
							checked: attributes.showTitle !== false,
							onChange: function( value ) {
								setAttributes( { showTitle: value } );
							}
						} ),
						el( TextControl, {
							label: __( "Image alt text", "property-listings" ),
							value: attributes.imageAlt || "",
							onChange: function( value ) {
								setAttributes( { imageAlt: value } );
							}
						} )
					),
					el(
						PanelBody,
						{
							title: __( "Photo", "property-listings" ),
							initialOpen: true
						},
						el(
							MediaUploadCheck,
							null,
							el( MediaUpload, {
								onSelect: function( media ) {
									setAttributes( {
										imageUrl: media.url || "",
										imageAlt: media.alt || attributes.imageAlt || ""
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
										attributes.imageUrl ? __( "Replace host photo", "property-listings" ) : __( "Select host photo", "property-listings" )
									);
								}
							} )
						)
					)
				),
				el(
					"section",
					{
						key: "preview",
						className: props.className + " host-section section-dark host-section-block"
					},
					el(
						"div",
						{ className: "host-shell" },
						el(
							"div",
							{ className: "host-layout" },
							el(
								"div",
								{ className: "host-photo-wrap" },
								attributes.imageUrl
									? el( "img", {
										src: attributes.imageUrl,
										alt: attributes.imageAlt || "",
										className: "host-photo"
									} )
									: el( "div", { className: "host-photo host-photo-placeholder" } )
							),
							el(
								"div",
								{ className: "host-copy" },
								attributes.showTitle !== false
									? el( PlainText, {
										tagName: "h1",
										value: attributes.title,
										placeholder: __( "Show title", "property-listings" ),
										onChange: function( value ) {
											setAttributes( { title: value } );
										}
									} )
									: null,
								el( PlainText, {
									tagName: "p",
									className: "eyebrow",
									value: attributes.eyebrow,
									placeholder: __( "Hosted by", "property-listings" ),
									onChange: function( value ) {
										setAttributes( { eyebrow: value } );
									}
								} ),
								el( PlainText, {
									tagName: "h2",
									value: attributes.name,
									placeholder: __( "Host name", "property-listings" ),
									onChange: function( value ) {
										setAttributes( { name: value } );
									}
								} ),
								el( PlainText, {
									tagName: "p",
									value: attributes.description,
									placeholder: __( "Host description", "property-listings" ),
									onChange: function( value ) {
										setAttributes( { description: value } );
									}
								} )
							)
						)
					)
				)
			];
		},
		save: function() {
			return null;
		}
	} );
} )( window.wp );
