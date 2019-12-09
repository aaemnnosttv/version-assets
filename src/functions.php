<?php

namespace VersionAssets;

/**
 * Get the content hash of a file for the given URL.
 *
 * @param string $src
 *
 * @return bool|string
 */
function get_hash($src)
{
    if ($file_path = get_local_path($src)) {
        return md5_file($file_path);
    }

    return false;
}

/**
 * Get the absolute path to the file served at the given URL.
 *
 * @param string $src
 *
 * @return bool|string
 */
function get_local_path($src)
{
    // Local srcs are either relative or use the local domain.
    if (! is_relative_url($src) && ! is_local_domain($src)) {
        return false;
    }

    // Core assets are all registered relative to ABSPATH,
    // so we must rewrite the src if this is not the same as the web root to get the correct path.
    if (is_core_src($src)) {
        $src = rewrite_core_src($src);
    }

    $file_path = path_join(guess_web_root(), ltrim(parse_url($src, PHP_URL_PATH), '/\\'));

    if (realpath($file_path)) {
        return $file_path;
    }

    return false;
}

/**
 * Get the absolute path to the web root directory.
 *
 * The web root is inferred as the parent of the content directory by default.
 *
 * @return string
 */
function guess_web_root() {
    /**
     * Filter the path used for the web root.
     */
    $path = apply_filters('version_assets/web_root', dirname(WP_CONTENT_DIR));

    return untrailingslashit($path);
}

/**
 * Checks if the given src is for a core asset.
 *
 * @param string $src Asset src to check.
 *
 * @return bool
 */
function is_core_src($src) {
    return (
        // Core assets are all registered using relative srcs, from ABSPATH (not web root).
        is_relative_url($src)
        // Only rewrite the src if core is not installed in the web root.
        && guess_web_root() !== untrailingslashit(ABSPATH)
        // Check that the src is for a core asset.
        && preg_match('#^/wp-(admin|includes)/#', $src)
    );
}

/**
 * Rewrites a core src to be relative to the web root.
 *
 * @param string $src Asset src to rewrite.
 *
 * @return string
 */
function rewrite_core_src($src) {
    $preg_quoted_web_root = preg_quote(guess_web_root(), '#');
    $wordpress_path = preg_replace("#^$preg_quoted_web_root#", '', untrailingslashit(ABSPATH));

    return path_join($wordpress_path, ltrim($src, '/'));
}

/**
 * Check whether the given URL belongs to this site.
 *
 * @param string $src
 *
 * @return bool
 */
function is_local_domain($src)
{
    return parse_url($src, PHP_URL_HOST) === parse_url(home_url(), PHP_URL_HOST);
}

/**
 * Check whether the given URL is relative or not.
 *
 * @param string $src
 *
 * @return bool
 */
function is_relative_url($src)
{
    $parsed = parse_url($src);

    return empty($parsed['host']) && empty($parsed['scheme']);
}
