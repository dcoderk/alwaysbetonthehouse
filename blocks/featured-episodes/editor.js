( function( wp ) {
	const el = wp.element.createElement;
	const __ = wp.i18n.__;
	const registerBlockType = wp.blocks.registerBlockType;
	const PlainText = wp.blockEditor.PlainText;
	const InspectorControls = wp.blockEditor.InspectorControls;
	const PanelBody = wp.components.PanelBody;
	const Notice = wp.components.Notice;

	registerBlockType( "property-listings/featured-episodes", {
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
							title: __( "Dynamic Content", "property-listings" ),
							initialOpen: true
						},
						el(
							"p",
							null,
							__( "This block shows real Scene posts automatically. It uses the next 3 scenes after the Latest Episodes block.", "property-listings" )
						)
					)
				),
				el(
					"section",
					{
						key: "preview",
						className: ( props.className || "" ) + " featured section-light alt-surface featured-episodes-block"
					},
					el(
						"div",
						{ className: "container" },
						el(
							Notice,
							{
								status: "info",
								isDismissible: false
							},
							__( "Front-end cards now load dynamically from Scene posts. Use this block to edit the section heading and description.", "property-listings" )
						),
						el(
							"div",
							{ className: "section-heading reveal" },
							el( PlainText, {
								tagName: "h2",
								value: attributes.heading,
								placeholder: __( "Featured Episodes", "property-listings" ),
								onChange: function( value ) {
									setAttributes( { heading: value } );
								}
							} ),
							el( PlainText, {
								tagName: "p",
								value: attributes.description,
								placeholder: __( "Section intro", "property-listings" ),
								onChange: function( value ) {
									setAttributes( { description: value } );
								}
							} )
						),
						el(
							"div",
							{ className: "featured-grid" },
							[ 1, 2, 3 ].map( function( index ) {
								return el(
									"article",
									{
										key: "featured-card-" + index,
										className: "featured-card reveal"
									},
									el(
										"div",
										{ className: "featured-thumb" }
									),
									el(
										"div",
										{ className: "featured-content" },
										el( "h3", null, __( "Dynamic Featured Scene", "property-listings" ) ),
										el( "p", { className: "featured-address" }, __( "Address from scene fields", "property-listings" ) ),
										el( "p", { className: "featured-description" }, __( "Description from scene excerpt or summary fields", "property-listings" ) ),
										el( "p", { className: "featured-price" }, __( "Price from scene price fields", "property-listings" ) ),
										el(
											"p",
											{ className: "featured-agent" },
											__( "Agent: ", "property-listings" ),
											__( "linked agent profile", "property-listings" )
										)
									)
								);
							} )
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
