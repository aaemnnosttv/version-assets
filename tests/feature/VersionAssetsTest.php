<?php

namespace Tests;

use VersionAssets\Scripts;
use VersionAssets\Styles;

class VersionAssetsTest extends \WP_UnitTestCase
{

    public function tear_down()
    {
        parent::tear_down();

        if (file_exists(WP_CONTENT_DIR . '/test.css')) {
            unlink(WP_CONTENT_DIR . '/test.css');
        }

        wp_styles()->registered = [];
        wp_styles()->to_do = [];
        wp_styles()->done = [];
        wp_scripts()->registered = [];
        wp_scripts()->to_do = [];
        wp_scripts()->done = [];
    }

    /** @test */
    function it_replaces_an_assets_registered_version_with_a_content_hash()
    {
        $content = '/* some content */';
        $hash    = md5($content);
        file_put_contents(WP_CONTENT_DIR . '/test.css', $content);

        wp_enqueue_style('test', WP_CONTENT_URL . '/test.css', [], 'registered-version');

        $url = $this->get_enqueued_url('test');
        parse_str(parse_url($url, PHP_URL_QUERY), $url_query_params);
        $this->assertStringContainsString($hash, $url);
        $this->assertEquals(['ver' => $hash], $url_query_params);
    }

    /** @test */
    function it_preserves_the_registered_version_if_the_asset_file_cannot_be_located()
    {
        wp_enqueue_style('test-missing', WP_CONTENT_URL . '/test-non-existent.css', [], 'registered-version');

        $url = $this->get_enqueued_url('test-missing');
        parse_str(parse_url($url, PHP_URL_QUERY), $url_query_params);
        $this->assertStringContainsString('registered-version', $url);
        $this->assertEquals(['ver' => 'registered-version'], $url_query_params);
    }

    /** @test */
    function it_preserves_the_registered_version_if_the_src_is_falsy()
    {
        wp_register_style('test-real', WP_CONTENT_URL . '/test.css', [], 'registered-version');
        // Some assets may have false for their src which is used for aliases (e.g. 'jquery').
        wp_register_style('test-alias', false, ['test-real'], 'registered-version');
        wp_enqueue_style('test-alias');

        $url = $this->get_enqueued_url('test-alias');
        parse_str(parse_url($url, PHP_URL_QUERY), $url_query_params);
        $this->assertStringContainsString('registered-version', $url);
        $this->assertEquals(['ver' => 'registered-version'], $url_query_params);
    }

    /** @test */
    function it_replaces_the_global_wp_styles_instance_when_it_is_initialzed()
    {
        $instance = wp_styles();
        $this->assertInstanceOf(\WP_Styles::class, $instance);
        $this->assertInstanceOf(Styles::class, $instance);
    }

    /** @test */
    function it_replaces_the_global_wp_scripts_instance_when_it_is_initialzed()
    {
        $instance = wp_scripts();
        $this->assertInstanceOf(\WP_Scripts::class, $instance);
        $this->assertInstanceOf(Scripts::class, $instance);
    }

    /** @test */
    function the_hashed_version_can_be_filtered()
    {
        add_filter('version_assets/asset_version', function ($hashed_version, $args) {
            $this->assertSame('registered-version', $hashed_version); // file doesn't exist to hash
            $this->assertSame('registered-version', $args['ver']);
            $this->assertSame('test', $args['handle']);
            $this->assertSame(WP_CONTENT_URL . '/test.css', $args['src']);
            $this->assertSame([], $args['deps']);

            return 'version-returned-from-filter';
        }, 10, 2);

        wp_enqueue_style('test', WP_CONTENT_URL . '/test.css', [], 'registered-version');
        $this->assertStringContainsString('ver=version-returned-from-filter', $this->get_enqueued_url('test'));
    }


    private function get_enqueued_url($handle)
    {
        ob_start();
        wp_print_styles($handle);
        $tag = ob_get_clean();
        preg_match('/\bhref=[\'"]([^\'"]+)[\'"]/', $tag, $matches);

        return $matches[1];
    }
}
