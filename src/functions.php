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
    $file_path = get_local_path($src);

    if ($file_path && is_file($file_path) && is_readable($file_path)) {
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
    if (! $src) {
        return false;
    }

    // Local srcs are either relative or use the local domain.
    if (! is_relative_url($src) && ! is_local_domain($src)) {
        return false;
    }

    /**
     * Filter the path used for the web root.
     */
    $web_root = apply_filters('version_assets/web_root', dirname(WP_CONTENT_DIR));
    $web_root = untrailingslashit($web_root);

    // Core assets are all registered using relative srcs, from ABSPATH (not web root),
    // so we must rewrite the src if ABSPATH is not the same as the web root to get the correct path.
    if (preg_match('#^/wp-(admin|includes)/#', $src) && $web_root !== untrailingslashit(ABSPATH)) {
        $preg_quoted_web_root = preg_quote($web_root, '#');
        $wordpress_path = preg_replace("#^$preg_quoted_web_root#", '', untrailingslashit(ABSPATH));
        $src = path_join($wordpress_path, ltrim($src, '/'));
    }

    $file_path = path_join($web_root, ltrim(parse_url($src, PHP_URL_PATH), '/\\'));

    if (realpath($file_path)) {
        return $file_path;
    }

    return false;
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
