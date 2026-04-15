( function( wp ) {
	const el = wp.element.createElement;
	const __ = wp.i18n.__;
	const registerBlockType = wp.blocks.registerBlockType;
	const InspectorControls = wp.blockEditor.InspectorControls;
	const PlainText = wp.blockEditor.PlainText;
	const PanelBody = wp.components.PanelBody;
	const TextControl = wp.components.TextControl;

	registerBlockType( "property-listings/simple-card", {
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
							title: __( "Card Settings", "property-listings" ),
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
						className: ( props.className || "" ) + " simple-card-block section-light alt-surface"
					},
					el(
						"div",
						{ className: "container" },
						el(
							"div",
							{ className: "featured-grid single-scene-featured-grid" },
							el(
								"article",
								{ className: "featured-card simple-card-preview" },
								el(
									"div",
									{ className: "featured-content" },
									el( PlainText, {
										tagName: "h3",
										value: attributes.title,
										placeholder: __( "Card title", "property-listings" ),
										onChange: function( value ) {
											setAttributes( { title: value } );
										}
									} ),
									el( PlainText, {
										tagName: "p",
										className: "featured-description",
										value: attributes.text,
										placeholder: __( "Card text", "property-listings" ),
										onChange: function( value ) {
											setAttributes( { text: value } );
										}
									} ),
									el( PlainText, {
										tagName: "p",
										className: "featured-agent",
										value: attributes.buttonText,
										placeholder: __( "Button text", "property-listings" ),
										onChange: function( value ) {
											setAttributes( { buttonText: value } );
										}
									} )
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
