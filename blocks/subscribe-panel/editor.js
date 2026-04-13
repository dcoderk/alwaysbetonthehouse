( function( wp ) {
	const el = wp.element.createElement;
	const __ = wp.i18n.__;
	const registerBlockType = wp.blocks.registerBlockType;
	const PlainText = wp.blockEditor.PlainText;
	const InspectorControls = wp.blockEditor.InspectorControls;
	const PanelBody = wp.components.PanelBody;
	const TextControl = wp.components.TextControl;

	registerBlockType( "property-listings/subscribe-panel", {
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
							title: __( "Button Settings", "property-listings" ),
							initialOpen: true
						},
						el( TextControl, {
							label: __( "Button URL", "property-listings" ),
							value: attributes.buttonUrl || "",
							onChange: function( value ) {
								setAttributes( { buttonUrl: value } );
							}
						} )
					)
				),
				el(
					"section",
					{
						key: "preview",
						className: ( props.className || "" ) + " subscribe section-dark subscribe-panel-block"
					},
					el(
						"div",
						{ className: "container reveal" },
						el(
							"div",
							{ className: "subscribe-panel" },
							el( PlainText, {
								tagName: "h2",
								value: attributes.heading,
								placeholder: __( "Subscribe to Podcast", "property-listings" ),
								onChange: function( value ) {
									setAttributes( { heading: value } );
								}
							} ),
							el( PlainText, {
								tagName: "p",
								value: attributes.description,
								placeholder: __( "Subscribe description", "property-listings" ),
								onChange: function( value ) {
									setAttributes( { description: value } );
								}
							} ),
							el( PlainText, {
								tagName: "span",
								className: "btn btn-gold",
								value: attributes.buttonText,
								placeholder: __( "Subscribe", "property-listings" ),
								onChange: function( value ) {
									setAttributes( { buttonText: value } );
								}
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
