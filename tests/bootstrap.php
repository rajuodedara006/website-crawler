<?php
/**
 * Bootstraps the Website Crawler Plugin integration tests
 *
 * @package WebsiteCrawlerPlugin\Tests\Integration
 */

// Define testing constants.
define('WEB_CRAWLER_TESTS_ROOT', __DIR__);
define('WEB_CRAWLER_ROOT', dirname(dirname(__DIR__)));

/**
 * Gets the WP tests suite directory
 *
 * @return string
 */
function webPluginGetWPTestsDir()
{
    $tests_dir = getenv('WP_TESTS_DIR');

    // Travis CI & Vagrant SSH tests directory.
    if (empty($tests_dir)) {
        $tests_dir = '/tmp/wordpress-tests-lib';
    }
    // If the tests' includes directory does not exist, try a relative path to Core tests directory.
    if (! file_exists($tests_dir . '/includes/')) {
        $tests_dir = '../../../../tests/phpunit';
    }
    // Check it again. If it doesn't exist, stop here and post a message as to why we stopped.
    if (! file_exists($tests_dir . '/includes/')) {
        trigger_error('Unable to run the integration tests, as the WordPress test suite could not be located.', E_USER_ERROR);  // @codingStandardsIgnoreLine.
    }
    // Strip off the trailing directory separator, if it exists.
    return rtrim($tests_dir, DIRECTORY_SEPARATOR);
}

$web_ll_tests_dir = webPluginGetWPTestsDir();

// Give access to tests_add_filter() function.
require_once $_tests_dir . '/includes/functions.php';

/**
 * Manually load the plugin being tested.
 */
function rocket_lazyload_manually_load_plugin()
{
    require WEB_CRAWLER_ROOT . '/rocket-lazy-load.php';
}
tests_add_filter('muplugins_loaded', 'rocket_lazyload_manually_load_plugin');

require_once $web_ll_tests_dir . '/includes/bootstrap.php';

unset($web_ll_tests_dir);