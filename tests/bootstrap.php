<?php

require_once dirname( __DIR__ ) . '/vendor/autoload.php';
require_once getenv( 'WP_PHPUNIT__DIR' ) . '/includes/functions.php';

tests_add_filter( 'muplugins_loaded', function() {
    require_once dirname(__DIR__) . '/version-assets.php';
} );

require getenv( 'WP_PHPUNIT__DIR' ) . '/includes/bootstrap.php';
