<?php
/**
 * Plugin Name:       SportScore — Live Scores & Fixtures
 * Plugin URI:        https://sportscore.com/embed/
 * Description:       Free embeddable live-scores and fixtures widget for football, basketball, cricket and tennis. Drop a shortcode or Gutenberg block, pick a team or league, done. No API key, no signup.
 * Version:           1.0.1
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            SportScore
 * Author URI:        https://sportscore.com/
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       sportscore-embed
 *
 * @package SportScoreEmbed
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // No direct access.
}

define( 'SPORTSCORE_EMBED_VERSION', '1.0.1' );
define( 'SPORTSCORE_EMBED_BASE_URL', 'https://sportscore.com' );

/**
 * Allowed sport slugs. Anything else short-circuits to a safe empty string
 * so a malformed shortcode never emits an arbitrary iframe URL.
 */
function sportscore_embed_allowed_sports() {
	return array( 'football', 'basketball', 'cricket', 'tennis' );
}

/**
 * Allowed entity types. Tennis only supports 'competition' upstream but
 * we gate that at render time with a graceful fallback message.
 */
function sportscore_embed_allowed_types() {
	return array( 'team', 'competition' );
}

/**
 * Sanitise a slug for safe URL interpolation. Keeps [a-z0-9-] only.
 *
 * @param string $slug Raw slug from the user.
 * @return string Cleaned slug or empty string.
 */
function sportscore_embed_sanitise_slug( $slug ) {
	$slug = strtolower( (string) $slug );
	$slug = preg_replace( '/[^a-z0-9\-]/', '', $slug );
	return is_string( $slug ) ? $slug : '';
}

/**
 * Render the embed iframe as HTML. Used by both the shortcode and the
 * Gutenberg block. Returns an empty string on invalid input rather than
 * partial HTML so malformed attributes simply skip the insert.
 *
 * @param array $atts Attributes: sport, type, slug, width, height, src.
 * @return string Safe HTML or empty string.
 */
function sportscore_embed_render_iframe( $atts ) {
	$atts = shortcode_atts(
		array(
			'sport'  => 'football',
			'type'   => 'team',
			'slug'   => '',
			'width'  => '320',
			'height' => '420',
			'src'    => '', // Optional: identify this site for our adoption stats.
		),
		$atts,
		'sportscore'
	);

	$sport = strtolower( $atts['sport'] );
	$type  = strtolower( $atts['type'] );
	$slug  = sportscore_embed_sanitise_slug( $atts['slug'] );

	if ( ! in_array( $sport, sportscore_embed_allowed_sports(), true ) ) {
		return '';
	}
	if ( ! in_array( $type, sportscore_embed_allowed_types(), true ) ) {
		return '';
	}
	if ( '' === $slug ) {
		return '';
	}

	// Clamp dimensions to sane ranges — the upstream widget is designed for 280–480.
	$width  = max( 240, min( 600, (int) $atts['width'] ) );
	$height = max( 300, min( 900, (int) $atts['height'] ) );

	$iframe_src = SPORTSCORE_EMBED_BASE_URL . '/embed/fixtures/'
		. rawurlencode( $sport ) . '/'
		. rawurlencode( $type ) . '/'
		. rawurlencode( $slug ) . '/';

	// Optional self-identify query param — helps SportScore know who's using
	// the widget (rough adoption signal only; no user data, no per-call
	// telemetry). Falls back to the host WordPress site name.
	$src_value = trim( (string) $atts['src'] );
	if ( '' === $src_value ) {
		$site_host = wp_parse_url( home_url(), PHP_URL_HOST );
		if ( is_string( $site_host ) && '' !== $site_host ) {
			$src_value = $site_host;
		}
	}
	if ( '' !== $src_value ) {
		$iframe_src = add_query_arg( 'src', $src_value, $iframe_src );
	}

	$attribution_href = 'https://sportscore.com/';

	// The attribution line is a free-tier requirement. It ships with the
	// plugin so users don't forget it — keeps them compliant out of the box.
	ob_start();
	?>
	<div class="sportscore-embed-wrap" style="max-width: <?php echo esc_attr( $width ); ?>px;">
		<iframe
			src="<?php echo esc_url( $iframe_src ); ?>"
			width="<?php echo esc_attr( $width ); ?>"
			height="<?php echo esc_attr( $height ); ?>"
			loading="lazy"
			referrerpolicy="no-referrer-when-downgrade"
			style="border: 1px solid #dce3ef; border-radius: 6px; background: #fff; display: block; max-width: 100%;"
			title="<?php echo esc_attr( sprintf( '%s %s %s fixtures', $sport, $type, $slug ) ); ?>">
		</iframe>
		<p class="sportscore-embed-attribution" style="font-size: 12px; margin: 6px 0 0; color: #546277;">
			<a href="<?php echo esc_url( $attribution_href ); ?>" rel="dofollow" title="Sports data by SportScore">Powered by SportScore</a>
		</p>
	</div>
	<?php
	return (string) ob_get_clean();
}

/**
 * Shortcode: [sportscore sport="football" type="team" slug="real-madrid"]
 */
add_shortcode(
	'sportscore',
	function ( $atts ) {
		return sportscore_embed_render_iframe( is_array( $atts ) ? $atts : array() );
	}
);

/**
 * Register the Gutenberg block. The editor UI lives in block.json + the
 * bundled edit.js; the front-end render is handled in PHP via the same
 * renderer as the shortcode, so both surfaces stay in sync.
 */
function sportscore_embed_register_block() {
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}
	register_block_type(
		__DIR__ . '/block',
		array(
			'render_callback' => function ( $attributes ) {
				return sportscore_embed_render_iframe(
					array(
						'sport'  => isset( $attributes['sport'] ) ? $attributes['sport'] : 'football',
						'type'   => isset( $attributes['type'] ) ? $attributes['type'] : 'team',
						'slug'   => isset( $attributes['slug'] ) ? $attributes['slug'] : '',
						'width'  => isset( $attributes['width'] ) ? $attributes['width'] : 320,
						'height' => isset( $attributes['height'] ) ? $attributes['height'] : 420,
					)
				);
			},
		)
	);
}
add_action( 'init', 'sportscore_embed_register_block' );
