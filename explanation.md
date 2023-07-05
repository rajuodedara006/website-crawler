# Website Crawler Plugin

## Installation Guide

1. Download Plugin zip from git repo
2. Go to Add new Plugin and Upload plugin zip file
3. Activate Plugin
4. Go to Settings -> Website Crawler
5. Click Trigger Crawl
6. It will show Crawl result below and also store that result in Database
7. You can see sitemap.html file wp-content folder where clicking on that file will give you all urls as list

## Problem to be Solved

The problem is to develop a WordPress plugin called "Website Crawler" that crawls and analyzes the home webpage of a website for SEO improvement. The plugin should perform the following tasks:
- Crawl the home page and extract all the internal links.
- Store the crawled links in a database table.
- Delete previous crawl results.
- Generate an HTML sitemap with the latest crawl results.
- Save the homepage content as an HTML file.

## Technical Spec

To solve the problem, we will develop a WordPress plugin using PHP. The plugin will have the following features:
- Custom Database Table: We will create a custom database table to store the crawl results separately from the default WordPress tables.
- WordPress HTTP API: We will use the WordPress HTTP API to fetch the content of the home page.
- DOMDocument and DOMXPath: We will utilize the DOMDocument and DOMXPath classes to parse the HTML content and extract the internal links.
- HTML Sitemap: We will generate an HTML sitemap based on the latest crawl results.
- Saving Homepage Content: We will save the homepage content as an HTML file.

## Technical Decisions and Reasons

1. WordPress Plugin:
   - We chose to develop a WordPress plugin as it provides a modular and extensible way to extend the functionality of a WordPress website.

2. Custom Database Table:
   - We decided to create a custom database table to store the crawl results separately from the default WordPress tables. This allows better control and management of the crawl data.

3. WordPress HTTP API:
   - We used the WordPress HTTP API to fetch the content of the home page. This ensures compatibility with various server configurations and provides a consistent way to make HTTP requests within the WordPress ecosystem.

4. DOMDocument and DOMXPath:
   - We utilized the DOMDocument and DOMXPath classes to parse the HTML content and extract the internal links. These classes provide a convenient and reliable way to navigate and query HTML documents.

5. HTML Sitemap:
   - We generated an HTML sitemap based on the latest crawl results. This provides a structured representation of the internal links, which can be beneficial for SEO and user navigation.

6. Saving Homepage Content:
   - We saved the homepage content as an HTML file to preserve the state of the webpage at the time of crawling. This can be useful for reference or offline analysis.

## How the Code Works

The main class of the plugin is `WebsiteCrawler`. It is instantiated when the plugin is activated and sets up the necessary hooks and actions for the plugin's functionality.

- The `activate` method is called when the plugin is activated. It creates the required database table using the `dbDelta` function and schedules the crawl event to run hourly using `wp_schedule_event`.

- The `performCrawl` method is the core functionality of the plugin. It deletes previous crawl results, deletes old crawl results older than 24 hours, crawls the home page to extract internal links, stores the crawl results in the database table, saves the homepage content as an HTML file, and creates the HTML sitemap.

- The `addSettingsPage` method adds a settings page to the WordPress admin dashboard. The page allows triggering a manual crawl and displays the latest crawl results.

## Achieving the Admin's Desired Outcome

The solution achieves the admin's desired outcome by providing an automated way to crawl and analyze the home webpage for SEO improvement. The plugin performs regular crawls, stores the crawl results in a separate table, generates an HTML sitemap based on the latest crawl results, and saves the homepage content as an HTML file.

The settings page in the WordPress admin dashboard allows the admin to trigger manual crawls and view the latest crawl results, providing valuable insights for SEO analysis and decision-making.

By automating the crawl process and providing actionable data, the plugin simplifies the SEO analysis and helps the admin improve their website's SEO.x

## Approaching Problem

1. Understanding the Problem:
   - I first took the time to fully understand the requirements and objectives of the plugin. This involved identifying the problem to be solved, which was to crawl and analyze the home webpage for crawl.

2. Breaking Down the Problem:
   - I then broke down the problem into smaller, manageable tasks. This included determining the necessary functionality, such as crawling the webpage, storing crawl results, generating an HTML sitemap, and saving the homepage content.

3. Research and Planning:
   - I conducted research to explore the best approaches and techniques to implement the required functionality. This involved studying WordPress development practices, understanding the WordPress HTTP API, and familiarizing myself with DOMDocument and DOMXPath for HTML parsing.

4. Implementing the Solution:
   -  I began implementing the solution by writing the necessary code, following best practices and adhering to the WordPress coding standards. I focused on creating reusable and modular code to ensure flexibility and maintainability.

5. Testing and Debugging:
   - Throughout the development process, I conducted regular testing and debugging to ensure the plugin's functionality was working as expected. I tested various scenarios, including successful crawls, handling errors, and verifying data storage and retrieval.

6. Final Submission and Improvement:
   - Finally prepared everything required for submission Alongside the development process, I documented the code, explaining the purpose and functionality of each component. This documentation helps other developers understand and maintain the codebase.

## Thinking About Plugin

1. Problem Significance
2. User Perspective
3. Plugin Integration
4. Performance and Efficiency
5. Scalability and Flexibility
6. Error Handling
7. Security
8. Documentaion

## Why Choose This Direction

As this is quite easy to understand and use as well as easy to enhance in future that is why I choose this direction. Also followed everything necessary to build it with modern tools

## Why this Direction is better Solution

This is better direction as it is automatic as well as time saving approach. It has consistency and Comprehensive insights. its scalability, flexibility and performance are high standards. Overall, this direction provides a better solution by streamlining the website crawling and analysis process, saving time and effort, delivering comprehensive insights, integrating seamlessly with WordPress, and offering scalability and customization options. It empowers website administrators with the tools they need to optimize their websites for improved SEO performance effectively.

## Notes

Website Crawler plugin passed PHPCS
It also has all code developer by taking care of OOP and PSR
It also passes with Integration testing

