/**
 * SportScore embed block — editor UI.
 *
 * Kept intentionally vanilla (no JSX, no build step) so the plugin can ship
 * a single .zip with no npm toolchain required. WordPress exposes React and
 * block primitives on the global `wp` object so we consume them directly.
 */
(function (wp) {
	if (!wp || !wp.blocks || !wp.element) {
		return;
	}
	var el = wp.element.createElement;
	var Fragment = wp.element.Fragment;
	var InspectorControls = wp.blockEditor
		? wp.blockEditor.InspectorControls
		: wp.editor.InspectorControls;
	var useBlockProps = wp.blockEditor
		? wp.blockEditor.useBlockProps
		: function () { return {}; };
	var PanelBody = wp.components.PanelBody;
	var SelectControl = wp.components.SelectControl;
	var TextControl = wp.components.TextControl;
	var RangeControl = wp.components.RangeControl;

	var SPORTS = [
		{ label: "Football / Soccer", value: "football" },
		{ label: "Basketball", value: "basketball" },
		{ label: "Cricket", value: "cricket" },
		{ label: "Tennis", value: "tennis" }
	];
	var TYPES = [
		{ label: "Team", value: "team" },
		{ label: "Competition / League", value: "competition" }
	];

	// Slug examples — deliberately picked to match known-good server-side
	// slugs so a brand-new user lands on something that renders.
	var PRESET_HINTS = {
		football: {
			team: "real-madrid, manchester-united, liverpool, fc-barcelona",
			competition: "premier-league"
		},
		basketball: {
			team: "los-angeles-lakers",
			competition: "national-basketball-association"
		},
		cricket: {
			team: "",
			competition: "indian-premier-league"
		},
		tennis: {
			team: "",
			competition: "atp-madrid-spain-men-singles"
		}
	};

	wp.blocks.registerBlockType("sportscore/embed", {
		edit: function (props) {
			var attributes = props.attributes || {};
			var setAttributes = props.setAttributes;
			var blockProps = useBlockProps();

			var hint = (PRESET_HINTS[attributes.sport] || {})[attributes.type] || "";
			var previewSrc =
				attributes.slug
					? "https://sportscore.com/embed/fixtures/" +
					  encodeURIComponent(attributes.sport) +
					  "/" +
					  encodeURIComponent(attributes.type) +
					  "/" +
					  encodeURIComponent(attributes.slug) +
					  "/"
					: "";

			return el(
				Fragment,
				null,
				el(
					InspectorControls,
					null,
					el(
						PanelBody,
						{ title: "Widget settings", initialOpen: true },
						el(SelectControl, {
							label: "Sport",
							value: attributes.sport,
							options: SPORTS,
							onChange: function (value) { setAttributes({ sport: value }); }
						}),
						el(SelectControl, {
							label: "Type",
							value: attributes.type,
							options: TYPES,
							onChange: function (value) { setAttributes({ type: value }); }
						}),
						el(TextControl, {
							label: "Slug",
							help:
								"Team or competition slug from sportscore.com" +
								(hint ? " — e.g. " + hint : ""),
							value: attributes.slug,
							onChange: function (value) { setAttributes({ slug: value }); }
						}),
						el(RangeControl, {
							label: "Width (px)",
							min: 240,
							max: 600,
							value: attributes.width,
							onChange: function (value) { setAttributes({ width: value }); }
						}),
						el(RangeControl, {
							label: "Height (px)",
							min: 300,
							max: 900,
							value: attributes.height,
							onChange: function (value) { setAttributes({ height: value }); }
						})
					)
				),
				el(
					"div",
					blockProps,
					attributes.slug
						? el("iframe", {
								src: previewSrc,
								width: attributes.width,
								height: attributes.height,
								loading: "lazy",
								referrerPolicy: "no-referrer-when-downgrade",
								style: {
									border: "1px solid #dce3ef",
									borderRadius: "6px",
									background: "#fff",
									maxWidth: "100%"
								},
								title: "SportScore widget preview"
						  })
						: el(
								"div",
								{
									style: {
										border: "1px dashed #b8c2d2",
										borderRadius: "6px",
										padding: "20px",
										background: "#f7f9fc",
										color: "#546277",
										textAlign: "center",
										fontSize: "0.9rem"
									}
								},
								"Pick a sport + team/competition slug in the sidebar to preview the widget. Browse slugs at ",
								el(
									"a",
									{ href: "https://sportscore.com/embed/", target: "_blank", rel: "noreferrer" },
									"sportscore.com/embed/"
								),
								"."
						  )
				)
			);
		},
		save: function () {
			// Server-rendered via render_callback — no save output needed.
			return null;
		}
	});
})(window.wp);
