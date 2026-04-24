# SportScore WordPress Plugin — sportscore-embed

[![Plugin version](https://img.shields.io/badge/version-1.0.1-blue)](./sportscore-embed/sportscore-embed.php)
[![License: GPL v2+](https://img.shields.io/badge/license-GPLv2%2B-green)](./sportscore-embed/LICENSE.txt)
[![Tested with](https://img.shields.io/badge/WordPress-6.0%2B-21759b)](https://wordpress.org/)

Free, embeddable live-scores and fixtures widget for WordPress. Football, basketball, cricket, tennis — no API key, no signup, no paid tier.

Official plugin repo mirrored from the SportScore WordPress.org submission. Canonical site: [sportscore.com/embed/](https://sportscore.com/embed/).

---

## What it does

Drop a block or a shortcode into any WordPress post, page, or widget area and render live fixtures for a specific team or an entire competition.

```
[sportscore sport="football" type="team" slug="real-madrid"]
```

Or, in the block editor, search for "SportScore" → pick sport + type + slug from the inspector sidebar → live preview renders right in the editor.

## Sports supported

| Sport | Entity types | Example slug |
|---|---|---|
| Football (soccer) | team, competition | `real-madrid`, `premier-league` |
| Basketball | team, competition | `los-angeles-lakers`, `national-basketball-association` |
| Cricket | competition | `indian-premier-league` |
| Tennis | competition | `atp-madrid-spain-men-singles` |

Browse all slugs: [sportscore.com/embed/](https://sportscore.com/embed/)

## Install

### Option A — from WordPress.org (pending approval)

1. WP admin → Plugins → Add New → search "SportScore"
2. Install + Activate

### Option B — from the signed zip at sportscore.com

1. Download [sportscore-embed-v1.0.1.zip](https://sportscore.com/static/downloads/sportscore-embed-v1.0.1.zip)
2. WP admin → Plugins → Add New → Upload Plugin → pick the zip
3. Activate

### Option C — from this repo

```bash
git clone https://github.com/Backspace-me/sportscore-wp-embed.git
cp -r sportscore-wp-embed/sportscore-embed /path/to/wp-content/plugins/
```

## Usage

### Shortcode

```
[sportscore sport="football" type="team" slug="real-madrid"]
[sportscore sport="football" type="competition" slug="premier-league"]
[sportscore sport="basketball" type="competition" slug="national-basketball-association" width="360" height="500"]
```

| Attribute | Required | Default | Notes |
|---|---|---|---|
| `sport` | yes | `football` | One of: `football`, `basketball`, `cricket`, `tennis` |
| `type` | yes | `team` | One of: `team`, `competition` |
| `slug` | yes | — | Slug from sportscore.com (`[a-z0-9-]`) |
| `width` | no | 320 | Clamped 240–600 |
| `height` | no | 420 | Clamped 300–900 |
| `src` | no | site host | Override the `src=` query param used for adoption stats |

### Gutenberg block

Insert the "SportScore — Live Scores & Fixtures" block. Pick sport, type, and slug from the inspector sidebar. The block renders a live preview in the editor and server-renders the same iframe on the front end, so both stay in sync.

## Free-tier attribution

SportScore is free in exchange for a visible `rel="dofollow"` link back to sportscore.com on any page that renders the data. The plugin embeds the `Powered by SportScore` line under the widget automatically — compliance is zero-effort for anyone using the default template.

If you need commercial / white-label use without the attribution, email `api@sportscore.com`.

## How it works

- **Server-rendered.** The iframe contents are a Django template on sportscore.com that fetches ~14 days of fixtures for the requested entity and renders self-contained HTML with its own CSS.
- **Sandboxed.** The iframe boundary prevents your theme's CSS from leaking into the widget (or vice versa).
- **Refreshing.** A small AJAX poll inside the iframe pulls fresh data roughly once a minute.
- **CORS-open.** No allow-list needed; the endpoint accepts requests from any origin behind Cloudflare.
- **Rate-limited on honour.** Approximately 10,000 requests per IP per 24 hours for the free tier. Enforced loosely at the edge.

## Security

- All shortcode attributes are sanitised (`[a-z0-9-]` for slugs, allow-lists for sport/type, numeric clamps for width/height).
- All output is escaped via `esc_url()`, `esc_attr()`, `wp_parse_url()`.
- The plugin makes no external HTTP calls itself; the iframe does that inside its own sandbox.
- No `wp_options` writes, no scheduled cron, no database touches — fully stateless.

## Development

The plugin ships without a build step so the zip works as-is. The block editor UI is hand-written vanilla JS that consumes the `wp.blocks` / `wp.element` / `wp.components` globals — no JSX, no webpack.

```
sportscore-embed/
├── LICENSE.txt          — GPL-2.0-or-later
├── readme.txt           — WordPress.org plugin directory readme
├── sportscore-embed.php — main plugin file (shortcode + block registration)
└── block/
    ├── block.json       — block metadata
    └── edit.js          — editor UI (vanilla wp.element)
```

## Related

- [SportScore API](https://sportscore.com/developers/) — the underlying REST API (also free, also no key)
- [`sportscore` on PyPI](https://pypi.org/project/sportscore/) — Python client
- [`sportscore-mcp` on npm](https://www.npmjs.com/package/sportscore-mcp) — MCP server for Claude, Cursor, Zed
- [RapidAPI Hub listing](https://rapidapi.com/villacharlie269/api/sportscore6) — for users who prefer RapidAPI's auth model

## License

GPL-2.0-or-later. See [`sportscore-embed/LICENSE.txt`](./sportscore-embed/LICENSE.txt).
