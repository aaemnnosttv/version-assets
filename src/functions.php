<?php

namespace VersionAssets;

function get_hash($src)
{
    $filepath = get_local_path($src);

    if ($filepath) {
        return md5_file($filepath);
    }

    return false;
}

function get_local_path($src)
{
    if (! is_relative_url($src) && ! is_local_domain($src)) {
        return false;
    }

    $path = parse_url($src, PHP_URL_PATH);
    $clean_path = ltrim($path, '/');
    $base_paths = [
        ABSPATH,
        WP_CONTENT_DIR,
    ];

    foreach ($base_paths as $base_path) {
        $realpath = realpath(path_join($base_path, $clean_path));

        if ($realpath) {
            return $realpath;
        }
    }

    return false;
}

function is_local_domain($src)
{
    return parse_url($src, PHP_URL_HOST) === parse_url(home_url(), PHP_URL_HOST);
}

function is_relative_url($src)
{
    $parsed = parse_url($src);

    return empty($parsed['host']) && empty($parsed['scheme']);
}
