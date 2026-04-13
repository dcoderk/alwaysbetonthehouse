( function( wp ) {
	const el = wp.element.createElement;
	const __ = wp.i18n.__;
	const registerBlockType = wp.blocks.registerBlockType;
	const InspectorControls = wp.blockEditor.InspectorControls;
	const PanelBody = wp.components.PanelBody;
	const TextControl = wp.components.TextControl;

	function socialItem( iconClass, label, url ) {
		return el(
			"li",
			{ className: "header-social-item" },
			el(
				"a",
				{
					className: "header-social-link",
					href: url || "#"
				},
				el( "i", { className: iconClass, "aria-hidden": "true" } ),
				el( "span", { className: "screen-reader-text" }, label )
			)
		);
	}

	registerBlockType( "property-listings/header-socials", {
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
							title: __( "Social URLs", "property-listings" ),
							initialOpen: true
						},
						el( TextControl, {
							label: __( "X URL", "property-listings" ),
							value: attributes.xUrl || "",
							onChange: function( value ) {
								setAttributes( { xUrl: value } );
							}
						} ),
						el( TextControl, {
							label: __( "Instagram URL", "property-listings" ),
							value: attributes.instagramUrl || "",
							onChange: function( value ) {
								setAttributes( { instagramUrl: value } );
							}
						} ),
						el( TextControl, {
							label: __( "TikTok URL", "property-listings" ),
							value: attributes.tiktokUrl || "",
							onChange: function( value ) {
								setAttributes( { tiktokUrl: value } );
							}
						} ),
						el( TextControl, {
							label: __( "LinkedIn URL", "property-listings" ),
							value: attributes.linkedinUrl || "",
							onChange: function( value ) {
								setAttributes( { linkedinUrl: value } );
							}
						} )
					)
				),
				el(
					"ul",
					{
						key: "preview",
						className: ( props.className || "" ) + " social-list header-socials"
					},
					socialItem( "bi bi-twitter-x", "X", attributes.xUrl ),
					socialItem( "bi bi-instagram", "Instagram", attributes.instagramUrl ),
					socialItem( "bi bi-tiktok", "TikTok", attributes.tiktokUrl ),
					socialItem( "bi bi-linkedin", "LinkedIn", attributes.linkedinUrl )
				)
			];
		},
		save: function() {
			return null;
		}
	} );
} )( window.wp );
