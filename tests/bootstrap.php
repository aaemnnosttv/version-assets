<?php

require getenv('WP_TESTS_DIR') . '/includes/functions.php';

tests_add_filter('muplugins_loaded', function () {
    require_once dirname(__DIR__) . '/version-assets.php';
});

require getenv('WP_TESTS_DIR') . '/includes/bootstrap.php';
