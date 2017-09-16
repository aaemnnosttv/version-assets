<?php

namespace Tests;

use VersionAssets\Scripts;
use VersionAssets\Styles;

class VersionAssetsTest extends \WP_UnitTestCase
{

    public function tearDown()
    {
        parent::tearDown();

        if (file_exists(WP_CONTENT_DIR . '/test.css')) {
            unlink(WP_CONTENT_DIR . '/test.css');
        }
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
        $this->assertContains($hash, $url);
        $this->assertEquals(['ver' => $hash], $url_query_params);
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

    private function get_enqueued_url($handle)
    {
        ob_start();
        wp_print_styles($handle);
        $tag = ob_get_clean();
        preg_match('/\bhref=[\'"]([^\'"]+)[\'"]/', $tag, $matches);

        return $matches[1];
    }
}
