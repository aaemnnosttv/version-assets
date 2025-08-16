=== Version Assets ===
Contributors: aaemnnosttv
Tags: content hash, css, js, styles, scripts
Requires at least: 2.6.0
Tested up to: 6.8.2
Requires PHP: 5.4
Stable tag: 1.1.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Automatically apply a content-based version on all of your assets to optimize browser caching.

== Installation ==
1. Upload the plugin files to the `/wp-content/plugins/version-assets/` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the Plugins screen in WordPress

== Screenshots ==
1. Diff of affected assets in head element (purple: before, green: after)
2. Diff of affected assets in footer scripts (purple: before, green: after)

== Changelog ==
= 1.1.3 (2024-03-29) =
* Fixed a potential notice from core introduced in WP 5.8. See [#9](https://github.com/aaemnnosttv/version-assets/issues/9).

= 1.1.2 (2020-02-02) =
* Fixed a potential notice for script/style aliases which resolved to a local directory.

= 1.1.1 (2019-12-09) =
* Fixed version rewriting of core assets when WordPress is in its own directory.

= 1.1 (2019-03-30) =
* Introduce `version_assets/asset_version` filter.

= 1.0 (2017-09-18) =
* Initial release!
