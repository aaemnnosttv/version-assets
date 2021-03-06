<?php

namespace Tests;

class FunctionsTest extends \WP_UnitTestCase
{
    /**
     * @test
     * @dataProvider relative_url_provider
     */
    function is_relative_url($url, $is_relative)
    {
        $this->assertSame($is_relative, \VersionAssets\is_relative_url($url));
    }

    function relative_url_provider()
    {
        return [
            ['/', true],
            ['/some/path/', true],
            ['some/path/', true],
            ['//example.com', false],
            ['//example.com/path/', false],
            ['http://example.com', false],
        ];
    }

    /**
     * @test
     * @dataProvider local_path_provider
     */
    function get_local_path($url, $expected)
    {
        $this->assertSame($expected, \VersionAssets\get_local_path($url));
    }

    function local_path_provider()
    {
        return [
            // Core registers its assets using relative paths, regardless of subdirectory or not.
            ['/wp-admin/css/common.css', ABSPATH . 'wp-admin/css/common.css'],
            ['/wp-admin/css/about.css', ABSPATH . 'wp-admin/css/about.css'],
            [admin_url('css/about.css'), ABSPATH . 'wp-admin/css/about.css'],
            [admin_url('js/common.js'), ABSPATH . 'wp-admin/js/common.js'],
            // Asset aliases have an src of `false`
            [false, false],
        ];
    }
}
