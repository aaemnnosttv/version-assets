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

    $web_root  = apply_filters('version_assets/web_root', dirname(WP_CONTENT_DIR));
    $file_path = path_join($web_root, ltrim(parse_url($src, PHP_URL_PATH), '/\\'));

    if (realpath($file_path)) {
        return $file_path;
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
