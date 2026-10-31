=== SportScore — Live Scores & Fixtures ===
Contributors: sportscore
Tags: live scores, fixtures, sports, football, widget
Requires at least: 6.0
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.0.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Free embeddable live-scores and fixtures widget for football, basketball, cricket and tennis. No API key. No signup.

== Description ==

SportScore is a free sports-data service. This plugin is a one-click WordPress wrapper around the SportScore fixtures widget, so you can drop live scores and upcoming matches into any page, post, widget area, or block theme.

**What you get**

* Live scores + upcoming fixtures for a team or a whole league
* Four sports: football (soccer), basketball, cricket, tennis
* No API key, no signup, no rate-limit paperwork (fair-use cap around 10,000 requests / 24h / IP)
* Classic shortcode AND a native Gutenberg block — pick either
* Auto-refreshing, mobile-responsive, dark-mode-aware
* Shows a small "Powered by SportScore" credit under the widget

**How to use it — shortcode**

`[sportscore sport="football" type="team" slug="real-madrid"]`

**How to use it — Gutenberg block**

Insert the "SportScore — Live Scores & Fixtures" block, then pick sport, type, and slug from the sidebar. A live preview renders right in the editor.

**Finding slugs**

Browse teams and competitions at [sportscore.com/embed/](https://sportscore.com/embed/) — there's a one-click builder with presets for popular teams and leagues. Slugs look like `real-madrid`, `premier-league`, `national-basketball-association`, `indian-premier-league`.

**Attribution**

The widget shows a small "Powered by SportScore" credit. SportScore is free to use — see the [terms of use](https://sportscore.com/developers/terms/) for details.

== External services ==

This plugin displays live sports data by embedding the SportScore widget from sportscore.com, and relies on this external service to function.

When a visitor opens a page that contains a SportScore block or the [sportscore] shortcode, their browser loads the widget (an iframe) from https://sportscore.com/. The widget URL contains only the sport and the team/league slug you selected. This plugin sends no other data from your site or your visitors to SportScore.

Service provided by SportScore:

* Website: https://sportscore.com/
* Terms of use: https://sportscore.com/developers/terms/
* Privacy policy: https://sportscore.com/privacy/

== Installation ==

1. Upload the plugin folder to `/wp-content/plugins/` or install from the WordPress Plugin Directory.
2. Activate through the **Plugins** screen in WordPress.
3. Insert a **SportScore** block in any post/page, OR use the `[sportscore]` shortcode.

== Frequently Asked Questions ==

= Do I need an API key? =

No. SportScore is CORS-open and key-less. Just pick a slug and embed.

= What sports are supported? =

Football (soccer), basketball, cricket, tennis. More on the roadmap.

= Can I use it on a commercial site? =

Yes. The plugin is free to use on any site, personal or commercial.

= Where do I find the slug for my team or league? =

Visit [sportscore.com/embed/](https://sportscore.com/embed/) — the showcase page has a one-click slug builder with popular presets (Real Madrid, Premier League, NBA, Lakers, IPL, ATP Madrid, etc.).

= My slug returns a blank widget — what went wrong? =

Double-check the slug on sportscore.com. The URL format is `sportscore.com/embed/fixtures/{sport}/{team|competition}/{slug}/`. If that URL works in a browser, the shortcode will too.

== Screenshots ==

1. Gutenberg block with inspector controls and live preview in the editor.
2. Rendered widget on the front end with attribution.
3. Classic Editor — shortcode usage with three sports in a single post.
4. Block inserter — finding the SportScore block.
5. Multiple sports on a single page — Premier League, NBA and IPL fixtures together.

== Changelog ==

= 1.0.2 =
* Tested up to WordPress 7.1.
* Aligned text domain with the plugin slug (`sportscore-live-scores-fixtures`).
* No code changes — functionality identical to 1.0.1.

= 1.0.1 =
* Polished readme and screenshots for plugin directory submission.
* No code changes — functionality identical to 1.0.0.

= 1.0.0 =
* Initial release.
* Shortcode `[sportscore sport="…" type="…" slug="…"]`.
* Gutenberg block with live preview.
* Automatic "Powered by SportScore" dofollow attribution.
* Width / height controls clamped to widget-friendly ranges.
* Slug input sanitised to `[a-z0-9-]` — malformed shortcodes skip rendering.

== Upgrade Notice ==

= 1.0.2 =
Metadata-only update for WordPress 6.9 compatibility. Safe to upgrade.

= 1.0.1 =
Documentation polish only — no functional changes.

= 1.0.0 =
Initial release.
