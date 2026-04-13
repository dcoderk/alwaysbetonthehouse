( function( wp ) {
	const el = wp.element.createElement;
	const __ = wp.i18n.__;
	const registerBlockType = wp.blocks.registerBlockType;
	const PlainText = wp.blockEditor.PlainText;
	const InspectorControls = wp.blockEditor.InspectorControls;
	const PanelBody = wp.components.PanelBody;
	const TextControl = wp.components.TextControl;

	registerBlockType( "property-listings/latest-episodes", {
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
							title: __( "Section Settings", "property-listings" ),
							initialOpen: true
						},
						el( TextControl, {
							label: __( "Button text", "property-listings" ),
							value: attributes.buttonText || "",
							onChange: function( value ) {
								setAttributes( { buttonText: value } );
							}
						} )
					)
				),
				el(
					"section",
					{
						key: "preview",
						className: ( props.className || "" ) + " episodes section-grey latest-episodes-block"
					},
					el(
						"div",
						{ className: "container" },
						el(
							"div",
							{ className: "section-heading reveal" },
							el( PlainText, {
								tagName: "h2",
								value: attributes.heading,
								placeholder: __( "Latest Episodes", "property-listings" ),
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
							{ className: "episode-list" },
							el(
								"article",
								{ className: "episode-row reveal" },
								el(
									"div",
									{ className: "episode-thumb" },
									el( "span", { className: "platform-chip" }, __( "YouTube", "property-listings" ) )
								),
								el(
									"div",
									{ className: "episode-body" },
									el( "p", { className: "meta" }, __( "Latest scene posts will render here automatically.", "property-listings" ) ),
									el( "h2", null, __( "Live Episode Content", "property-listings" ) ),
									el( "p", null, __( "This block pulls the newest Scene entries using your video link, image, date, and description fields.", "property-listings" ) ),
									el( "span", { className: "text-link" }, ( attributes.buttonText || __( "All Episodes", "property-listings" ) ) + " / " + __( "Watch links update automatically", "property-listings" ) )
								)
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
