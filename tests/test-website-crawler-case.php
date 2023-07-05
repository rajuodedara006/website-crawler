<?php

use PHPUnit\Framework\TestCase as TestCase;
/**
 * Class SampleTest
 *
 * @package Website_Crawler
 */

/**
 * Sample test case.
 */
class WebsiteCrawlerTestCase extends TestCase {

    /**
	 * Holds the WP_UnitTest_Factory instance.
	 *
	 * @var WebsiteCrawler
	 */
	protected $webSiteCrawlerObj;

    /**
	 * Setup the test case case.
	 *
	 * @since 1.0.0
	 * @see WC_Unit_Test_Case::setUp()
	 */
	public function setUp(): void {
		parent::setUp();
        // _manually_load_plugin();
        // Create object of WebsiteCrawler class.
		$this->webSiteCrawlerObj = new \WebsiteCrawler();
	}

	 /**
     * Test that the necessary database tables are created on plugin activation.
     */
    public function test_database_tables_created() {
        global $wpdb;

        // Activate the plugin
        // _manually_load_plugin();

        // Check if the table exists in the database
        $table_name = $wpdb->prefix . 'website_crawler_results';
        $this->assertTrue($wpdb->get_var("SHOW TABLES LIKE '$table_name'") === $table_name);
    }

    /**
     * Test the crawling functionality.
     */
    public function test_crawl_functionality() {
        // Test the crawlPage function
        $result = $this->webSiteCrawlerObj->crawlPage(site_url());
        $this->assertNotEmpty($result);

        // Test the website_crawler_get_page_content function
        $content = $this->webSiteCrawlerObj->getPageContent(site_url());
        $this->assertNotEmpty($content);
    }

    // Add more test methods for other functionality as needed
}
