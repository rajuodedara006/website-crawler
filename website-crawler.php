<?php

/**
 * Plugin Name: Website Crawler
 * Plugin URI: https://github.com/rajuodedara006
 * Description: A plugin to crawl and analyze the home webpage for SEO improvement.
 * Version: 1.0.0
 * Author: Raju Odedara
 * Author URI: https://github.com/rajuodedara006
 * License: GPLv2 or later
 * Text Domain: website-crawler
 */

class WebsiteCrawler {

    private $wpdb;

    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;

        register_activation_hook(__FILE__, [$this, 'activate']);
        register_deactivation_hook(__FILE__, [$this, 'deactivate']);
        add_action('website_crawler_crawl', [$this, 'performCrawl']);
        add_action('admin_menu', [$this, 'addSettingsPage']);
    }

    public function activate() {
        $this->createTables();
        $this->scheduleCrawl();
    }

    public function deactivate() {
        wp_clear_scheduled_hook('website_crawler_crawl');
    }

    private function createTables() {
        $table_name = $this->wpdb->prefix . 'website_crawler_results';

        $charset_collate = $this->wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            url longtext NOT NULL,
            created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    private function scheduleCrawl() {
        if (!wp_next_scheduled('website_crawler_crawl')) {
            wp_schedule_event(time(), 'hourly', 'website_crawler_crawl');
        }
    }

    public function performCrawl() {
        $this->deletePreviousResults();
        $this->deleteOldCrawlResults();

        $home_url = get_home_url();
        $crawl_result = $this->crawlPage($home_url);

        if ($crawl_result) {
            $this->storeCrawlResult($crawl_result);
        }

        $this->saveHomepageAsHtml($home_url);
        $this->createSitemap();
    }

    private function crawlPage($url) {
        $html = $this->getPageContent($url);

        if ($html) {
            $dom = new DOMDocument;
            @$dom->loadHTML($html);

            $xpath = new DOMXPath($dom);
            $links = $xpath->query('//a[@href]');

            $result = [];

            foreach ($links as $link) {
                $href = $link->getAttribute('href');
                if ($href) {
                    $result[] = $href;
                }
            }

            $result = array_unique($result);
            return $result;
        }

        return false;
    }

    private function getPageContent($url) {
        $response = wp_remote_get($url);
        if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
            return wp_remote_retrieve_body($response);
        }
        return false;
    }

    private function deletePreviousResults() {
        $table_name = $this->wpdb->prefix . 'website_crawler_results';
        $this->wpdb->query("DELETE FROM $table_name");
    }

    private function deleteOldCrawlResults() {
        $table_name = $this->wpdb->prefix . 'website_crawler_results';
        $older_than = date('Y-m-d H:i:s', strtotime('-24 hours'));
        $this->wpdb->query($this->wpdb->prepare("DELETE FROM $table_name WHERE created_at < %s", $older_than));
    }

    private function storeCrawlResult($url) {
        $table_name = $this->wpdb->prefix . 'website_crawler_results';
        $this->wpdb->insert($table_name, [
            'url' => maybe_serialize($url),
        ]);
    }

    private function saveHomepageAsHtml($url) {
        $homepage_content = $this->getPageContent($url);

        if ($homepage_content) {
            $homepage_file_path = WP_CONTENT_DIR . '/homepage.html';
            file_put_contents($homepage_file_path, $homepage_content);
        }
    }

    private function createSitemap() {
        $table_name = $this->wpdb->prefix . 'website_crawler_results';
        $results = $this->wpdb->get_row("SELECT `url` FROM $table_name ORDER BY created_at DESC");
        $results = maybe_unserialize($results->url);
        $sitemap_content = '<ul>';

        foreach ($results as $result) {
            $sitemap_content .= '<li>' . $result . '</li>';
        }

        $sitemap_content .= '</ul>';
        $sitemap_file_path = WP_CONTENT_DIR . '/sitemap.html';
        file_put_contents($sitemap_file_path, $sitemap_content);
    }

    public function addSettingsPage() {
        add_options_page(
            'Website Crawler Settings',
            'Website Crawler',
            'manage_options',
            'website-crawler',
            [$this, 'renderSettingsPage']
        );
    }

    public function renderSettingsPage() {
        if (!current_user_can('manage_options')) {
            return;
        }

        if (isset($_POST['website_crawler_trigger_crawl']) && wp_verify_nonce($_POST['website_crawler_nonce'], 'website_crawler_trigger_crawl')) {
            $this->performCrawl();
        }

        $latest_crawl_result = maybe_unserialize($this->getLatestCrawlResult());

        ?>
        <div class="wrap">
            <h1>Website Crawler</h1>
            <form method="post" action="">
                <?php wp_nonce_field('website_crawler_trigger_crawl', 'website_crawler_nonce'); ?>
                <input type="hidden" name="website_crawler_trigger_crawl" value="1">
                <input class="button button-primary" type="submit" value="Trigger Crawl">
            </form>
            <h2>Latest Crawl Result</h2>
            <?php
            if (empty($latest_crawl_result)) {
                echo '<p>No crawl results found.</p>';
                return;
            }
            ?>
            <ol class="crawl-results">
                <?php
                foreach ($latest_crawl_result as $result) {
                    echo '<li>' . $result . '</li>';
                }
                ?>
            </ol>
        </div>
        <?php
    }

    private function getLatestCrawlResult() {
        $table_name = $this->wpdb->prefix . 'website_crawler_results';
        $result = $this->wpdb->get_var("SELECT url FROM $table_name ORDER BY created_at DESC LIMIT 1");
        return maybe_unserialize($result);
    }
}

new WebsiteCrawler();
