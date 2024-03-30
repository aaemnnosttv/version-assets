<?php
/**
 * Plugin Name: Version Assets
 * Plugin URI: https://aaemnnost.tv/version-assets/
 * Description: Automatically apply a content-based version on all of your assets to optimize browser caching. This plugin has no settings.
 * Author: Evan Mattson
 * Author URI: https://aaemnnost.tv
 * License: GPLv2
 * Version: 1.1.2
 */

namespace VersionAssets;

require_once __DIR__ . '/src/functions.php';
require_once __DIR__ . '/src/HashifyVersion.php';
require_once __DIR__ . '/src/Styles.php';
require_once __DIR__ . '/src/Scripts.php';

/**
 * Set placeholder WP_Query before initializing Styles and Scripts to avoid
 * _doing_it_wrong warning triggered since WP 5.8.
 * @see https://core.trac.wordpress.org/ticket/53848
 */
$GLOBALS['wp_query'] = new \WP_Query();

$GLOBALS['wp_styles'] = new Styles();
$GLOBALS['wp_scripts'] = new Scripts();

unset($GLOBALS['wp_query']);
