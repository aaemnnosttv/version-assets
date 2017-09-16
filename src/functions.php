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
    if (! is_relative_url($src) && ! is_local_domain($src)) {
        return false;
    }

    $web_root  = apply_filters('version_assets/web_root', dirname(WP_CONTENT_DIR));
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
