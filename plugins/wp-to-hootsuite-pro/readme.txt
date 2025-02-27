=== WordPress to Hootsuite Pro ===
Contributors: wpzinc
Donate link: https://www.wpzinc.com/plugins/wordpress-to-hootsuite-pro
Tags: hootsuite,twitter,facebook,linkedin,google,social,media,sharing,post
Requires at least: 5.0
Tested up to: 6.5.3
Requires PHP: 7.2
Stable tag: trunk

Send WordPress Pages, Posts or Custom Post Types to your Hootsuite (hootsuite.com) account for scheduled publishing to social networks.

== Description ==

WP to Hootsuite is a plugin for WordPress that sends updates to your Hootsuite (hootsuite.com) account  for scheduled publishing to social networks when you publish and/or update WordPress Pages, Posts and/or Custom Post Types.

Plugin settings allow granular control over choosing:
- Sending updates to Hootsuite for Posts, Pages and/or any Custom Post Types
- Sending updates when any of the above are published, updated or both or neither
- Text format to use when sending an update on publish or update events, with support for tags including site name, Post title, excerpt, categories, date, URL and author
- Which social media accounts connected to your Hootsuite account to publish updates to (Facebook, Twitter or LinkedIn)

When creating or editing a Page, Post or Custom Post Type, sending the update to Hootsuite can be overridden for that specific content item.

= Support =

Please email support@wpzinc.com, with your license key.

= WP Zinc =
We produce free and premium WordPress Plugins that supercharge your site, by increasing user engagement, boost site visitor numbers
and keep your WordPress web sites secure.

Find out more about us:

* <a href="http://www.wpzinc.com">Our Plugins</a>
* <a href="http://www.facebook.com/wpzinc">Facebook</a>
* <a href="http://twitter.com/wp_zinc">Twitter</a>
* <a href="https://plus.google.com/b/110192203343779769233/110192203343779769233/posts?rel=author">Google+</a>

== Installation ==

1. Upload the `wp-to-hootsuite-pro` folder to the `/wp-content/plugins/` directory
2. Active the WP to Hootsuite Pro through the 'Plugins' menu in WordPress
3. Configure the plugin by going to the `WP to Hootsuite Pro` menu that appears in your admin menu

== Frequently Asked Questions ==



== Screenshots ==

1. Settings Panel when plugin is first installed.
2. Settings Panel when Hootsuite Access Token is entered.
3. Settings Panel showing available options for Posts, Pages and any Custom Post Types when the plugin is authenticated with Hootsuite.
4. Post level settings meta box.

== Changelog ==

= 2.5.2 (2024-05-13) =
* Fix: Text to Image: Define compliant filename to prevent `Invalid image url parameter supplied` error

= 2.5.1 (2024-05-08) =
* Fix: Use WP Cron: Action would not always schedule when using Gutenberg

= 2.5.0 (2024-04-11) =
* Fix: Text to Image: PHP Deprecated notice of `Implicit conversion from float to int loses precision`

= 2.4.9 (2023-11-17) =
* Fix: Hootsuite API Error: #400: 5000: Unknown error occurred when attempting to publish a status with an image

= 2.4.8 (2023-09-28) =
* Fix: Correctly detect and differentiate REST API requests from Gutenberg REST API requests, ensuring REST API requests trigger status(es)
* Fix: Repost: Exclude Posts where "Do NOT Post to Hootsuite" selected

= 2.4.7 (2023-08-24) =
* Fix: Updated WordPress Coding Standards to 3.0.0

= 2.4.6 (2023-08-03) =
* Fix: PHP Deprecated notices in PHP 8.2

= 2.4.5 (2023-05-16) =
* Fix: Post: Log: Export Log: Check user can edit posts to permit export log functionality

= 2.4.4 (2023-02-06) =
* Fix: Status: Tags: Modern Events Calendar: Output correct date when using {mec_event_start_date} and {mec_event_end_date}

= 2.4.3 (2023-01-26) =
* Added: Status: Text: Taxonomy: Hashtag with Underscores option.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/status-text-tags/#available-tags
* Added: Status: Modern Events Calendar Integration.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/using-modern-events-calendar-plugin/

= 2.4.2 (2022-09-26) =
* Fix: Profiles: Don't Instagram Business profiles for configuration, as they are not supported by Hootsuite's API
* Fix: Import/Export: Display menu option when Plugin not authenticted with Hootsuite

= 2.4.1 (2022-09-02) =
* Added: Log: Log errors when image operations (resizing, converting, uploading to Media Library) fails
* Fix: Use get_temp_dir() instead of assumed /tmp folder for writing temporary images when resizing, converting or generating text to image
* Fix: Status: Clear profiles cache when deauthorizing and authorizing with a different Hootsuite account

= 2.4.0 (2022-08-25) =
* Fix: Featured and Additional Images: Don't include Page/Post's Featured Image if image(s) defined in "Featured and Additional Images" section and "Post to Hootsuite using Manual Settings" enabled

= 2.3.9 (2022-08-04) =
* Added: Per Post Status: Option to specify specific date and time to schedule individual statuses for
* Fix: Improved WordPress Coding Standards

= 2.3.8 (2022-06-23) =
* Fix: Removed clipboard.js, as WordPress provides this library
* Fix: Status: Correctly sanitize and escape status textarea field value to prevent possible XSS

= 2.3.7 (2022-05-12) =
* Fix: Multisite: Activation: Conditionally load required hook depending on WordPress version
* Fix: Support link would not redirect to support page

= 2.3.6 (2022-04-01) =
* Fix: Bulk Publish: Nonce check would fail when using WordPress Admin > Posts > Send to Hootsuite Bulk Action

= 2.3.5 (2022-03-25) =
* Added: Status: Text to Image: Autocomplete suggestions for Tags
* Fix: Licensing: Links would incorrectly display as HTML
* Fix: Licensing: Correctly strip HTTP/HTTPS from domain before performing license key check
* Fix: Bulk Publish: Show total number of requests in log table
* Fix: Ensure code meets WordPress Coding Standards
* Fix: Multisite: Activation: When using wp_insert_site, get blog ID from WP_Site before running activation routine
* Fix: Bulk Publish: Continue to next status when a Post does not meet status conditions, instead of trying the same Post again
* Fix: Status: Improved error handling when status(es) cannot be saved due to server error
* Fix: Status: Add nonce check before searching for Taxonomy Terms, Authors and Author Roles
* Fix: Post: Truly perform nonce check on form submissions
* Fix: Bulk Publish: Add nonce check on form submissions
* Fix: Repost: Test: Add nonce check before running test
* Fix: Status: Image: Removed wp-to-hootsuite-pro-square registered image size, as it is no longer needed

= 2.3.4 (2022-03-08) =
* Fix: Call to undefined function _disable_block_editor_for_navigation_post_type when creating/updating Post in Gutenberg or via the REST API in WordPress 5.9+
* Fix: Use WP Cron: Update action would wrongly run when publishing a Post that had no content in Gutenberg or uses a Page Builder
* Fix: Scheduled Posts: Publish action would not run when using Gutenberg
* Fix: Customizer: Don't load inline CSS for menu icon when loading WordPress Admin > Theme > Customize

= 2.3.3 (2022-03-03) =
* Fix: Multisite: Activation: Use wp_insert_site hook when available in WordPress 5.1 and higher

= 2.3.2 (2022-02-03) =
* Added: Featured and Additional Images: Select multiple images at once within the Media Library modal.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/featured-image-settings/#use-feat--image--not-linked-to-post
* Added: Featured and Additional Images: Drag and drop to reorder images.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/featured-image-settings/#use-feat--image--not-linked-to-post
* Added: Status: Insert Tags: Insert tag at textarea caret position, with leading/trailing space as applicable.
* Added: Status: Yoast SEO: Facebook and Twitter Title and Description tags.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/status-text-tags/#yoast-seo
* Fix: Instagram: Auto crop portrait images less aggressively when cropping required to meet Instagram's required aspect ratio 

= 2.3.1 (2022-01-20) =
* Added: Status: Insert Tags: Events Manager: Tags for Location Post Type.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/status-text-tags/#events-manager--locations

= 2.3.0 (2022-01-06) =
* Fix: Settings: User Access: Post Type checkboxes for each User Role would not display if Enable Specific Post Types enabled and no Post Types selected.

= 2.2.9 (2021-12-16) =
* Added: Status: Tags: {date} uses WordPress Admin > Settings > Site Language and Date Format options.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/status-text-tags/#available-tags
* Added: Status: Tags: {date:date(format)} uses WordPress Admin > Settings > Site Language option.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/status-text-tags/#applying-transformations 

= 2.2.8 (2021-12-02) =
* Fix: Always include WordPress media functions when converting a WebP image to JPEG and storig it in the Media Library to avoid PHP errors

= 2.2.7 (2021-11-22) =
* Added: Status: Insert Tags: Featured Image Caption Plugin.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/status-text-tags/#featured-image-caption-plugin

= 2.2.6 (2021-11-11) =
* Added: Support for images added to the Media Library by Plugins that don't store images locally e.g. External Media without Import

= 2.2.5 (2021-10-21) =
* Added: Licensing: Improved verification method when OpenSSL < 1.1.0 and/or web host continues to use an expired DST Root CA X3.  See Docs: https://www.wpzinc.com/documentation/installation-licensing-updates/entering-license-key/#common-issues

= 2.2.4 (2021-09-24) =
* Fix: Status: Author Conditions: Clear Author Meta Key/Value conditions in UI when switching editing between statuses

= 2.2.3 (2021-09-23) =
* Fix: Logs: Correctly escape search and form action
* Fix: Import & Export: Correctly escape form action

= 2.2.2 (2021-09-16) =
* Fix: PHP Deprecated notices in PHP 8

= 2.2.1 (2021-09-09) =
* Added: Status: Text: Convert HTML links to plain text with link in brackets, instead of just displaying the unlinked text
* Added: Status: Text: Convert HTML lists to plain text with hyphens, instead of just displaying plain text
* Fix: Status: Schedule: Custom Time (based on Custom Field / Post Meta Value): Correctly calculate status scheduled date and time relative to Timezone
* Fix: Status: Schedule: Events Manager: Correctly calculate status scheduled date and time relative to Event Start / End Date and Timezone
* Fix: Status: Schedule: The Events Calendar: Correctly calculate status scheduled date and time relative to Event Start / End Date and Timezone

= 2.2.0 (2021-09-02) =
* Added: Status: Image: Support for .webp images when Use Feat. Image enabled and .webp image used as Featured/Additional Image. See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/featured-image-settings/#webp-image-support

= 2.1.9 (2021-08-26) =
* Added: Status: Remove HTML from shortcodes included in status text

= 2.1.8 (2021-08-12) =
* FIx: Error: Could not load Plugin class image

= 2.1.7 (2021-07-29) =
* Fix: Bulk Publishing: Use improved Javascript library for sending statuses

= 2.1.6 (2021-07-22) =
* Fix: Status: Image: Improved quality by using newer Hootsuite API method for including an image with a status

= 2.1.5 (2021-07-15) =
* Added: New Installations: Clearer workflow for connecting to Hootsuite and connecting social media profiles to Hootsuite account.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/authentication-settings/
* Fix: Licensing: Quicker method to check license key for performance

= 2.1.4 (2021-07-08) =
* Added: Status: Author Conditions: Comparison options for Author and Role.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/status-settings/#status--conditions--authors

= 2.1.3 (2021-06-24) =
* Fix: Don't minify Plugin Javascript if a third party minification Plugin is active, which would prevent status settings from sometimes saving

= 2.1.2 (2021-06-17) =
* Fix: Status: PowerPress: Prevent PowerPress from appending podcast URL to Content and Excerpt tags. 

= 2.1.1 (2021-06-10) =
* Fix: Authorization: Changed oAuth Authorization URL to prevent 404 error
* Fix: Authorization: More detailed error message displayed when Hootsuite API fails

= 2.1.0 (2021-06-03) =
* Added: Status: Author Conditions.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/status-settings/#status--conditions--authors
* Added: Status: Tags: Character Limit, Sentence Limit, Word Limit, Date and URL Encoding transformations.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/status-text-tags/#applying-transformations
* Added: Status: Insert Tags: The Events Calendar: Venue Name Tag.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/status-text-tags/#the-events-calendar
* Fix: Status: Post Meta Conditions: Improved detetction of changes/deletions to honor on save
* Fix: Bulk Publish: Choose Posts: Show correct settings screen when clicking link to define Bulk Publish statuses.

= 2.0.9 (2021-05-27) =
* Added: Status: Tags: Apply Transformations (uppercase, lowercase, slug etc).  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/status-text-tags/#applying-transformations

= 2.0.8 (2021-04-29) =
* Added: Stauts: Text: Autocomplete suggestions for Tags.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/status-text-tags/#autocomplete-suggestions
* Added: Status: Conditions: IN and NOT IN conditions for conditionally sending statuses.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/status-settings/#status--conditions--post
* Fix: Status: Text: Improved autocomplete suggestions UI

= 2.0.7 (2021-04-22) =
* Added: Status: Text: Twitter: Autocomplete suggestions for Twitter Profile Mentions.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/status-text-tags/#twitter-profile-mentions

= 2.0.6 (2021-04-15) =
* Added: Settings: Post Type: Show prompt if changes made but unsaved when navigating away from the status settings
* Fix: Log: Warning: `Edit the Post` link correctly loads the Edit Post screen
* Fix: Log: Bulk Publish: Output `Bulk Publish` instead of `Bulk_publish` in Logs
* Fix: Log: Bulk Publish: Warnings and Errors were incorrectly logged as a Repost Action

= 2.0.5 (2021-04-01) =
* Added: Settings: Post Type: Immediately show/hide green tick on Post Type tab after clicking Save, to confirm whether the Post Type is configured to send status(es) to Hootsuite
* Fix: Settings: Post Type: Profile: Text order and links were incorrect when displaying a Timezone warning

= 2.0.4 (2021-03-18) =
* Fix: New Installations: Define valid schedule for default status

= 2.0.3 (2021-03-11) =
* Added: Featured and Additional Images: Option to attach additional images to a Facebook or Twitter status.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/featured-image-settings/
* Fix: Featured and Additional Images: Removed duplicate description

= 2.0.2 (2021-02-26) =
* Added: Status: Insert Tags: The Events Calendar: Organizer Name, Phone Number, Email Address and Website URL Tags.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/status-settings/#status-text--tags--the-events-calendar

= 2.0.1 (2021-02-18) =
* Fix: Status: Retain paragraphs when using {content} tag

= 2.0.0 (2021-02-08) =
* Added: Log: Enable wp-content/debug.log only when WP_DEBUG=true, WP_DEBUG_LOG=true, WP_DEBUG_DISPLAY=false and Plugin Logging enabled.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/repost-settings/#testing
* Fix: Settings: Log Settings: Corrected link to Logs screen, and don't link "Plugin Logs" text when logging not enabled

= 1.9.9 (2021-01-22) =
* Fix: Events Manager: Better method for fetching Event’s Location, so tags are correctly populated

= 1.9.8 (20201-01-21) =
* Fix: Whitelabelling: Don't display Review Request notification if whitelabelling is available after a license is upgraded to an Agency license

= 1.9.7 (2021-01-14) =
* Added: Localization support, with .pot file and translators comments
* Fix: Status: Ensure all status changes are saved when the Save button is clicked
* Fix: Posts: Status: Ensure all status changes are saved when either the Save button is clicked or the Post is saved, published or updated.
* Fix: Status: Removed Update and Cancel Buttons on individual statuses, as they're no longer needed

= 1.9.6 (2021-01-08) =
* Added: Whitelabelling for Agency Licenses.  See Docs: https://www.wpzinc.com/documentation/installation-licensing-updates/whitelabelling-access-and-domain-control/
* Fix: Log: Don't show Logs in Plugin Submenu if Logging is disabled

= 1.9.5 (2020-12-31) =
* Added: Status: If a Featured Image is required, attempt to fetch it from the Post Content when a Featured Image or Additional Image has not been specified
* Added: Log: More relevant error message when a Post is sent to Instagram or Pinterest and is missing a Featured Image 
* Fix: Status: Editing a status after clicking Save wouldn't work
* Fix: Status: Insert Tags: Events Manager: Honor timezone for Event Dates and Times output

= 1.9.4 (2020-12-18) =
* Added: Posts: Log: Refresh Log button.
* Added: Posts: Status: Option to Save Per-Post Settings without Publishing/Updating/Saving the entire Post.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/per-post-settings/
* Fix: Posts: Retain Per-Post Manual Settings when a Post is Updated and no changes were made to the Per-Post Settings
* Fix: Status: Conditions: Taxonomies: Don't evaluate if Taxonomy Conditions enabled but no Terms are specified

= 1.9.3 (2020-12-17) =
* Fix: Posts: Save Enabled Profiles when Post using Manual Settings to ensure status(es) are sent

= 1.9.2 (2020-12-17) =
* Added: Status: Clearer error message when a status fails due to the Featured Image being inaccessible from Hootsuite
* Added: Status: New UI to improve performance and decrease loading times.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/status-settings/
* Added: Log: When Test Mode is enabled, and a status has Custom Time based scheduling, show the time it would be sent to social media
* Fix: Status: Save: Send statuses in single field to avoid settings not saving or exceeding PHP's max_input_vars
* Fix: Status: Save: Don't send form fields that aren't needed to avoid settings not saving or exceeding PHP's max_input_vars
* Fix: Status: Renamed fields on Per-Post Settings to avoid conflicts with WordPress Post fields and third party Plugins
* Fix: Posts: Publish/Update/Save: Send statuses in single field to avoid settings not saving or exceeding PHP's max_input_vars
* Fix: Posts: Publish/Update/Save: Don't send form fields that aren't needed to avoid settings not saving or exceeding PHP's max_input_vars
* Fix: Posts: Renamed fields on Per-Post Settings to avoid conflicts with WordPress Post fields and third party Plugins

= 1.9.1 (2020-11-27) =
* Fix: WooCommerce: Uncaught Error: Call to a member function get_price() on boolean

= 1.9.0 (2020-11-26) =
* Added: Status: Insert Tags: Events Manager: Address, Town, State, Postcode, Region, Country and URL Tags.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/status-settings/#status-text--tags--events-manager
* Added: Status: Insert Tags: The Events Calendar: Address, City, Province, Postal Code and Country Tags.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/status-settings/#status-text--tags--the-events-calendar
* Added: Status: Insert Tags: Rank Math SEO.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/status-settings/#status-text--tags--rank-math-seo
* Fix: Status: Insert Tags: All in One SEO Pack Title and Description not returning values

= 1.8.9 (2020-11-05) =
* Fix: Status: Text to Image: Text color would be ignored when some background images were defined
* Fix: Status: Yoast SEO: Compatibility when using Yoast SEO 15.2+ and WPML Yoast SEO Addon

= 1.8.8 (2020-10-29) =
* Added: Display error notice if PHP cURL extension is not installed
* Added: Settings: Force Trailing Forwardslash: Updated description to clarify why this setting might need to be enabled i.e. for correct status image
* Added: Menus and Submenus: Filter to define minimum required capability for accessing Plugin Menus and Submenus.  See Docs: https://www.wpzinc.com/documentation/wp-to-hootsuite-pro/developers/#wp_to_hootsuite_pro_admin_admin_menu_minimum_capability
* Fix: Settings: Force Trailing Forwardslash: Truly force a forwardslash if Permalink settings don't add one.

= 1.8.7 (2020-10-15) =
* Fix: Fatal error: Cannot load class spintax

= 1.8.6 (2020-10-15) =
* Added: Status: Text: Spintax support.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/status-settings/#status-text--spintax

= 1.8.5 (2020-10-09) =
* Added: Use WP Cron: When enabled, status(es) are now built and sent to Hootsuite when the scheduled task is run, not when the WordPress Post is published/updated. This improves compatibility with frontend submission and feed based Plugins which publish Posts.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/using-frontend-post-submission-and-autoblogging-plugins/ 
* Fix: Status: Don't show override settings for a profile if Override Defaults isn't enabled/checked

= 1.8.4 (2020-09-17) =
* Added: Status: Insert Tags: Content, Up to More Tag.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/status-settings/#status-text--tags--up-to-more-tag

= 1.8.3 (2020-09-10) =
* Added: Status: Insert Tags: Post Content and Excerpt, Sentence Limited.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/status-settings/#status-text--tags--sentence-limits

= 1.8.2 (2020-09-03) =
* Added: Status: Insert Tags: SEOPress Title and Description.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/status-settings/#status-text--tags--seopress
* Added: Logs: Screen Options: Choose table columns to display.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/log-settings/#define-table-columns-to-display
* Added: Logs: Screen Options: Choose number of logs per page to display.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/log-settings/#define-number-of-logs-per-page
* Fix: Status: Enabling/Disabling Publish, Update, Repost or Bulk Publish wouldn't update green tick in tab UI in WordPress 5.5+
* Fix: Status: Don't display "Post sucessfully added" admin notification if Test Mode is enabled
* Fix: Logs: Lighter success/error row background colors to make text easier to read
* Fix: Logs: When filtering by date, include results matching the date, not just results between the dates

= 1.8.1 (2020-08-20) =
* Fix: Licensing: Support Meta Box styling incorrect in WordPress 5.5+
* Fix: Fatal error when detecting current admin screen on some Page Builders 
* Fix: Some notifications weren't dismissible

= 1.8.0 (2020-08-13) =
* Fix: Per-Post Status Settings: Improved meta box styling for WordPress 5.5
* Fix: Repost: Ensure enabled days and times are based on WordPress configured timezone, not UTC / server timezone
* Fix: Logs: Clear Log: Contextualized confirmation message based on whether the Log is being cleared at Post or Plugin level

= 1.7.9 (2020-08-06) ==
* Added: Status: Short URL option.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/status-settings/#status-text--tags

= 1.7.8 (2020-07-09) =
* Added: Settings: Logs: Added Pending Log Level, for status(es) due to be sent when Use WP Cron enabled in Plugin's Settings.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/log-settings/#log-level
* Fix: Publish: Correctly add pending entry to Log when Use WP Cron enabled in Plugin's Settings

= 1.7.7 (2020-06-25) =
* Fix: Bulk Publish: Use Custom Field Value as Date instead of today's date when Custom Time (based on Custom Field / Post Meta Value) is selected on a status
* Fix: Bulk Publish: The Events Calendar and Events Manager Plugins: Use Event's Start or End Date instead of today's date when The Events Calendar: Relative to Event Start (or End) Date is selected on a status

= 1.7.6 (2020-06-18) =
* Added: Settings: General Settings: Enable Test Mode. See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/general-settings/#enable-test-mode
* Added: Logs: Confirmation when clicking Clear Log button
* Added: Import: Support for Zipped JSON file
* Added: Export: Export as JSON, Zipped
* Added: Settings: Logs: Option to choose specific Log Levels.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/log-settings/#log-level
* Fix: Logs: Set Clear Log button to red
* Fix: Bulk Publish: Only display selected Posts in Bulk Publish process when selected from the Posts or Pages Bulk Actions

= 1.7.5 (2020-06-11) =
* Fix: Status: Pinterest and Instagram: Ensure Featured or Additional Image is used if ‘No Image’ selected on Status, as an image is always required for Pinterest and Instagram

= 1.7.4 (2020-06-04) =
* Added: Settings: General Settings: Disable Fallback to Content if Excerpt Empty.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/general-settings/#disable-fallback-to-content-if-excerpt-empty-
* Fix: Import & Export: Include Custom Tags, Disable Excerpt Fallback, Hide Meta Box by Roles, Log, Override, Proxy and Text to Image Settings
* Fix: Status: Text to Image: Could not load Media Library class error
* Fix: Status: Text to Image: Removed Use Text to Image, Linked to Post, as this isn't an option available in Hootsuite

= 1.7.3 (2020-05-28) =
* Added: Settings: General Settings: Use Proxy option.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/general-settings/#use-proxy-
* Fix: Status: Text to Image: Honor Text Font setting when not using Custom Font
* Fix: Status: Yoast SEO: Only register Title and Description replacements if using Yoast 14.x or higher
* Removed: Repost: Show error message if Repost Settings are defined and DISABLE_WP_CRON prevents WordPress CRON tasks (including Reposting) from running

= 1.7.2 (2020-05-21) =
* Added: Status: Text to Image.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/text-to-image-settings/
* Added: Settings: Log Settings: Log Level option.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/log-settings/#log-level 
* Fix: Log: Honor Enabled Setting, ensuring logging does not take place if not enabled
* Fix: Pinterest: Force Override so Board is included in each status request, ensuring it schedules

= 1.7.1 (2020-05-07) =
* Added: Status: Insert Tags: Added option to output Taxonomy Terms as Hashtags, retaining case.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/status-settings/#status-text--tags
* Fix: Status: Taxonomy Tags: Remove non-alphanumeric characters to avoid breaking tag links
* Fix: Status: Yoast SEO Title and Description not correctly replaced when using Yoast 14.x or higher

= 1.7.0 (2020-04-30) =
* Added: Repost: Show error message if Repost Settings are defined and DISABLE_WP_CRON prevents WordPress CRON tasks (including Reposting) from running
* Added: Repost: Test Repost Cron option and improved logging to wp-content/debug.log when enabled.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/repost-settings/#debugging
* Fix: Status: Don't attempt to restrict Profiles or assign Author data to a status if the Post's Author no longer exists as a WordPress User
* Fix: Status: Enabling/Disabling Bulk Publish on Defaults or Social Media Profile wouldn't update green tick in tab UI
* Fix: CSS: Renamed option class to wpzinc-option to avoid CSS conflicts with third party Plugins
* Fix: Log: Set Test Mode entries Result = Test, ensuring filtering by Result = Test works

= 1.6.9 (2020-04-23) =
* Fix: Log: Unknown column 'status' in 'where clause' for query when clearing pending status log entries
* Fix: Elementor: Removed unused tooltip classes to prevent Menu and Element Icons from not displaying

= 1.6.8 (2020-04-16) =
* Added: Licensing: Verbose error message when unable to connect to Licensing API
* Fix: Licensing: Don't repetitively check the validity of a license that's invalid or exceeds the number of sites permitted, unless we're on the Licensing screen
* Fix: Dashboard > Updates: Show link to Changelog on View version details link

= 1.6.7 (2020-04-09) =
* Fix: Status: Manual Override: Don't save Post-specific settings for non-public Post Types
* Fix: Status: Don't send status(es) to Hootsuite for non-public Post Types containing Post-level Status Settings copied from a public Post

= 1.6.6 (2020-03-27) =
* Fix: Bulk Publish: Error: Could not load Plugin class bulk_publish

= 1.6.5 (2020-03-26) =
* Added: WP-CLI: Bulk Publish: Support for search parameters displayed at WP to Hootsuite Pro > Bulk Publish.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/wp-cli/#bulk-publish
* Fix: Activation: Prevent DB character set / collation errors on table creation by using WordPress' native get_charset_collate()
* Fix: Bulk Publish: Show error messages when Posts could not be found based on the given criteria
* Fix: Repost: Remove duplicate Reposting by not attempting to retry for a given Post that successfully schedules with one profile but fails with others

= 1.6.4 (2020-03-19) =
* Fix: Status: Better method to remove double/triple spaces in text whilst retaining newlines/breaklines and unicode/accented characters

= 1.6.3 (2020-03-12) =
* Added: Schedule statuses relative to Events Manager Plugin's Event's start or end date/time.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/status-settings/#status-text--scheduling
* Added: Insert Tags for Events Manager Plugin's Event data.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/status-settings/#status-text--tags--events-manager

= 1.6.2 (2020-02-27) =
* Added: Repost: Option to define days and hours to run Repost action.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/repost-settings/
* Fix: Status: Better method to remove double/triple spaces in text whilst retaining newlines/breaklines

= 1.6.1 (2020-02-20) =
* Fix: Status: Conditions: Return Usernames based on search, not all / no results

= 1.6.0 (2020-02-20) =
* Fix: Status: Better method to remove double/triple spaces in text, to avoid some installations where single spaces would be removed in a status
* Fix: Settings: Removed disabled CSS class on tabs, as not used and avoids potential conflicts with third party Plugins
* Fix: Repost: Return eligible Posts for reposting when Minimum and Maximum Post Age are both set to zero
* Fix: WP-CLI: Bulk Publish: Remove deprecated call to WP_To_Social_Pro_Log::build_log_output_array()

= 1.5.9 (2020-02-13) =
* Added: Status: Conditions: Conditionally send status based on Post Title, Excerpt and/or Content.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/status-settings/#status--conditions--post
* Added: Forms: Accessibility: Replaced Titles with <label> elements that focus the given input element on click
* Fix: Status: Conditions: Test all statuses' conditions when more than one status is defined
* Fix: Status: Conditions: Fix width of Actions column to allow more space for other fields
* Fix: Status: Conditions: Custom Fields: Don't display Remove button on first Custom Field Row
* Fix: Status: Use AJAX to save statuses to avoid settings not saving or changing when PHP's max_input_vars is exceeded due to e.g. several profiles and statuses defined
* Fix: Log: Display Status Text's breaklines

= 1.5.8 (2020-02-06) =
* Added: Status: Conditions: Custom Fields: NOT EXISTS Comparison Operator.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/status-settings/#status--conditions--custom-fields
* Added: Status: Conditions: Updated UI, adding all Conditions to a single table
* Fix: Repost: Allow zero for Minimum and Maximum Post Age, as stated in the setting description

= 1.5.7 (2020-01-16) =
* Added: General Settings: Option to force trailing forwardslash on {url}.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/general-settings/#force-trailing-forwardslash 
* Fix: Strip query parameters (added by e.g. Jetpack) from images before sending status to prevent errors
* Fix: Post: Featured and Additional Images: CSS issue prevented adding Featured/Additional Images

= 1.5.6 (2019-12-19) =
* Added: Status: Scheduling: Custom Time (Relative Format): Today/Tomorrow options.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/status-settings/#status-text--scheduling
* Fix: Repost: Ensure Custom Time offset is based on when the Repost event is run, not the Post's last modified date
* Fix: WP-CLI: PHP Fatal error:  Uncaught Error: Call to undefined method WP_To_Social_Pro_Log::build_log_output_array()

= 1.5.5 (2019-12-13) =
* Fix: Settings: Display confirmation notice that settings have saved

= 1.5.4 (2019-12-12) =
* Added: Status: Image: No Image option
* Removed: Status: Image: Use OpenGraph settings.  Hootsuite have disabled the ability for third party applications (such as this Plugin) to request that status messages read OpenGraph data for a given URL.

= 1.5.3 (2019-12-05) =
* Fix: Call to undefined method WP_To_Social_Pro_Log::clear_pending_log()

= 1.5.2 (2019-11-21) =
* Fix: Licensing: Obscure License Key if valid

= 1.5.1 (2019-11-14) =
* Added: Licensing: Clear WordPress options cache when updating or deleting license validity information, to prevent aggressive third party caching solutions from storing stale data.

= 1.5.0 (2019-11-07) =
* Added: Log: If DB_CHARSET empty or not defined in wp-config.php, use utf8mb4 for Log table.

= 1.4.9 (2019-10-24) =
* Added: Status: Scheduling: Custom Time (Relative Format) option.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/status-settings/#status-text--scheduling
* Added: Settings: Log Settings: Log settings moved to new tab in UI.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/log-settings/
* Added: Settings: Log Settings: Option to display / hide Log on individual Posts.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/log-settings/
* Added: Settings: Log Settings: Number of days to preserve Logs before automatic deletion.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/log-settings/
* Added: Log: Option to filter Logs by Request Sent Date. See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/log-settings/#filtering-logs
* Added: Log: Provide solutions to common issues
* Added: Instagram and Pinterest: Attempt to use image in Post's Content when no Featured Image or Additional Images specified (as both networks require an image for a status)
* Fix: Status: Conditions: Custom Fields: Add Meta / Custom Field Condition button was not working
* Fix: Per-Post Settings: Ensure JS loaded for Meta Boxes to function correctly on Custom Post Types
* Fix: Don't attempt to run upgrade routines between Plugin versions more than once

= 1.4.8 (2019-10-18) =
* Fix: Activation: WP to Hootsuite Pro: Error: Could not load Plugin class log

= 1.4.7 (2019-10-17) =
* Added: Log: New Log screen with filters and searching to view Status Logs across all Posts for all actions (Publish, Update, Repost, Bulk Publish).  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/logs/
* Added: Log: Improved messages explaining why a Post is not sent to Hootsuite
* Added: Log: Use separate database table for storing Plugin Status Logs instead of Post Meta, for performance
* Fix: Conditionally load JS, for performance
* Fix: Licensing: Don't show license expired notice on Plugins screen, for performance

= 1.4.6 (2019-10-03) =
* Added: Status: Conditions: Send if Post matches specified Author(s).  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/status-settings/#status--conditions--authors
* Added: Licensing: Show licensing server response on HTTP or server error
* Fix: Licensing: Updated endpoint URL
* Fix: Licensing: Use options cache instead of transients to reduce license key and update failures

= 1.4.5 (2019-08-31) =
* Fix: Status: Tags: Ensure Syntax for Word and Character Limited Content and Excerpt Tags is correct

= 1.4.4 (2019-08-29) =
* Added: Status: Tags: Content and Excerpt Tag options with Word or Character Limits
* Fix: Status: Removed loading of unused tags.js dependency for performance

= 1.4.3 (2019-08-15) =
* Fix: Status: Hootsuite API error which would occur when a profile has been disconnected from Hootsuite

= 1.4.2 (2019-07-25) =
* Added: Publish: More verbose error message when duplicate statuses are detected, detailing the Post Type, Profile and Action containing duplicate statuses.
* Added: Gutenberg: Better detection to check if Gutenberg is enabled
* Added: Gutenberg: Better detection to check if Post Content contains Gutenberg Block Markup
* Fix: Status: Custom Time (based on Custom Field / Post Meta Value): Ensure "Before Custom Field Value" subtracts days, hours and minutes
* Fix: Status: Custom Time (based on Custom Field / Post Meta Value): Convert timestamp to date/time if custom field value is a timestamp
* Fix: Status: {content} would return blank on WordPress 5.1.x or older

= 1.4.1 (2019-07-18) =
* Added: Support for Status and Featured Image Meta Boxes to display on Envira Galleries when Envira Standalone Addon is also active
* Fix: Bulk Publishing: Search / Filter by Taxonomy was not working
* Fix: Status: Manual Override: Taxonomy Conditions were not populating when creating/editing a Post, Page or Custom Post Type

= 1.4.0 (2019-06-22) =
* Fix: Uncaught ReferenceError: autosize is not defined

= 1.3.9 (2019-06-20) =
* Added: Status: Option to limit the number of words or characters output on a Template Tag.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/status-settings/
* Added: Status: Textarea will automatically expand based on the length of the status text. Fixes issues for some iOS devices where textarea scrolling would not work
* Fix: Status: {content} and {excerpt} tags always return the full content / excerpt, which can then be limited using the above word / character limits
* Fix: Publish: Add checks to prevent duplicate statuses being sent when a Page Builder (Elementor) fires wp_update_post multiple times when publishing

= 1.3.8 (2019-06-06) =
* Fix: Status: Strip additional unwanted newlines produced by Gutenberg when using {content}
* Fix: Status: Convert <br> and <br /> in Post Content to newlines when using {content}
* Fix: Status: Trim Post Content when using {content}

= 1.3.7 (2019-05-30) =
* Fix: Notice: Undefined variable: conditions_met 
* Fix: Log: Don't wrongly log that status(es) exist but conditions not met when an action has no statuses in the first place

= 1.3.6 (2019-05-25) =
* Fix: Repost: Ensure limit is honored

= 1.3.5 (2019-05-23) =
* Added: Status: Criteria: Dates: Option to require Post's published date be within defined start and end dates for a status to be sent

= 1.3.4 (2019-05-16) =
* Added: Settings: Display notice if the Hootsuite account does not have any social media profiles attached to it
* Fix: Publish: Display errors and log if authentication fails, or profiles cannot be fetched

= 1.3.3 (2019-05-09) =
* Added: Settings: Repost Settings: Display Icons on each Post Type Tab
* Fix: Settings: Status: Display warning if a timezone in WordPress or Hootsuite is not a valid timezone, instead of throwing a fatal error

= 1.3.2 (2019-05-02) =
* Added: Status: Insert Tags: WooCommerce: Regular Price, Sale Price, Sale Date From and To Tags.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/status-settings
* Added: Repost: Repost settings have now moved to Settings > Repost Settings, with options to specify settings for each Post Type.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hoosuite-pro/repost-settings/
* Added: Settings: Repost Settings: Maximum Posts per day Setting
* Added: Settings: Repost Settings: Per-Post Frequency
* Added: Settings: Repost Settings: Published Date / Post Age Criteria

= 1.3.1 (2019-04-04) =
* Added: Status: Secondary level tabbed UI for Profile actions (Publish, Update, Repost, Bulk Publish)
* Added: Status: Insert Tags: All in One SEO Pack Title and Description
* Added: Status: Insert Tags: Yoast SEO Title and Description
* Added: Settings: Post Type: Profile: Display warning with instructions when the WordPress Timezone and Hootsuite Profile Timezone do not match
* Added: Settings: Warning if the max_input_vars PHP setting might be too low for the Plugin's settings to successfully be saved
* Added: Bulk Publish: Moved Bulk Publish status options into Post Type Tabs, to allow selection of Post Type specific Taxonomies etc.  See Docs: https://www.wpzinc.com/documentation/wordpress-hootsuite-pro/status-settings/#post-action--bulk-publish
* Added: WP-CLI: Bulk Publish Action.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/wp-cli/
* Fix: Status: Prevent UI temporarily freezing when switching Profile and Action Tabs
* Fix: Status: Conditions: Taxonomies: Don't clear Term values when adding or removing statuses, or switching Profiles
* Fix: Status: Documentation Tab Link

= 1.3.0 (2019-03-28) =
* Fix: Bulk Publish / Repost: Clear Plugin Cache before replacing template tags with Post Data, to prevent statuses containing wrong Post data.

= 1.2.9 (2019-03-14) =
* Added: New Installations: Automatically enable Publish and Update Statuses on Posts
* Added: Plugin Activation: Enable Logging by default
* Added: Bulk Publish: More verbose logging, similar to "Enable Logging" functionality
* Fix: Bulk Publish: Undefined index errors
* Fix: Bulk Publish: Don't require Published Date parameters when querying for Posts to select for Bulk Publishing
* Fix: Bulk Publish: Return results when no start and end date is provided
* Fix: Bulk Publishing: Ensure Custom Time offset is based on now, not the Post’s published date
* Fix: Log: Output dates according to WordPress' installation date locale formatting
* Fix: Log: Split data into more table columns for easier reading

= 1.2.8 (2019-03-07) =
* Added: Status: Option to limit the number of characters output on a Template Tag.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/status-settings/
* Fix: Status: Don't attempt publishing to any existing linked Google+ Accounts, as Google+ no longer exists.
* Fix: Publish: Improved performance when sending several statuses for a single Post.
* Fix: Publish: Display errors on Post Edit screen if status(es) failed to send to Hootsuite.
* Fix: Post: Settings: Don't save Post level settings in Post Meta table if Override isn't enabled, to improve performance

= 1.2.7 (2019-02-28) =
* Fix: Status: Conditions: Custom Fields: When specifying multiple statuses for a single action, conditions would wrongly be attached to all statuses
* Fix: Menu Icon size preserved when Gravity Forms no conflict mode is set to on
* Fix: Display White Menu Icon unless the User is using WordPress' Light Admin Color Scheme, in which case display the Dark Menu Icon

= 1.2.6 (2019-02-14) =
* Fix: Publish: Removed global $post reference, which caused some installations to fetch the wrong Post to send to Hootsuite

= 1.2.5 (2019-01-31) =
* Added: Developers: Docblock comments on all Plugin specific filters and actions.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/developers/
* Fix: Settings: Use WP Cron previously failed
* Fix: Settings: Custom Tags: Prevent duplicate Custom Tags for a Post Type that have the same keys
* Fix: Licensing and Updates: Improved mechanism for WP-CLI support
* Fix: Minified all CSS and JS for performance

= 1.2.4 (2019-01-24) =
* Fix: Multisite: Network Activation: Ensure activation routines automatically run on all existing sites
* Fix: Multisite: Network Activation: Ensure activation routines automatically run created on new sites created after Network Activation of Plugin
* Fix: Multisite: Site Activation: Ensure activation routines automatically run
* Fix: Per Post Status: Undefined variable warning

= 1.2.3 (2019-01-17) =
* Added: Status: Insert Tags: Added WooCommerce Product Tags.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/status-settings/
* Fix: Settings: Added Pinterest Board URL option for Pinterest Statuses.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/status-settings/
* Fix: Settings: Display Twitter Usernames
* Fix: Settings: Status: When using Custom Time, ensure it is at least 5 minutes after Publish, Update or Repost (required by Hootsuite's API)
* Fix: PHP warning on count() when trying to fetch an excerpt for a Post

= 1.2.2 (2019-01-10) =
* Added: Status: Repost functionality.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/repost-settings/
* Added: Settings: Repost Settings.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/repost-settings/
* Added: Settings: User Access: Enable Specific Post Types by Role.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/user-access/
* Added: Settings: Header UI enhancements
* Fix: Settings: Only load settings for the displayed screen, for better performance
* Fix: Settings: Save settings more efficiently, for better performance

= 1.2.1 (2019-01-03) =
* Added: Status: Insert Tags: Added option to output Taxonomy Terms as Hashtags or Names.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/status-settings/
* Fix: Settings: Changed Authentication Tab Icon
* Fix: Settings and Status Settings: UI Enhancements for mobile compatibility
* Fix: {title} would sometimes result in HTML encoded characters on Facebook

= 1.2.0 (2018-12-27) =
* Fix: Status: Apply WordPress default filters to Post Title, Excerpt and Content. Ensures third party Plugins e.g. qtranslate can process content and remove shortcodes

= 1.1.9 (2018-12-20) =
* Fix: Removed all select2 references, as select2 is no longer used 

= 1.1.8 (2018-12-13) =
* Added: Gutenberg: Support for Custom Field Tags when Custom Fields / Meta are registered as a meta box outside of the Gutenberg editor.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/using-custom-fields-statuses/
* Added: REST API: Support for Custom Field Tags when Posts are created or updated via the REST API with Custom Field / Meta data.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/using-custom-fields-statuses/

= 1.1.7 (2018-12-06) =
* Fix: Settings: Bulk Publish: Warnings on undefined labels attribute
* Fix: Settings: Bulk Publish: Ensure that all Taxonomies are available for selection in the Insert Tags dropdown
* Fix: Settings: Bulk Publish: Don't display Conditional Options, as they're defined on the Bulk Publish screen itself
* Fix: Settings: Insert Tags: Ensure that excluded Taxonomies are not displayed in the dropdown
* Fix: Bulk Publish: Use selectize instead of select2 for Taxonomy Condition dropdowns

= 1.1.6 (2018-11-22) =
* Fix: Bulk Publish: Active UI Tab State

= 1.1.5 (2018-11-15) =
* Added: Gutenberg Support
* Added: Settings and Status Settings: UI Enhancements to allow for a larger number of connected social media profiles
* Added: Status: Tag: Post ID option
* Fix: Removed unused datepicker dependency
* Fix: CRON Scheduled Posts: Don't rely on wp_get_current_user() for User Access settings, as it's not always available

= 1.1.4 (2018-10-03) =
* Added: Status: Support for Shortcode processing on Status Text

= 1.1.3 (2018-09-13) =
* Added: Settings: Add / Edit / Delete Custom Field / Post Meta Tags to 'Insert Tags' dropdown for each Post Type

= 1.1.2 (2018-09-06) =
* Fix: Post and Publishing: Remove Profiles based on the logged in user, not the Post Author
* Fix: Per Post Status: Support UTC offsets defined in Settings > General, as well as timezone locations 
* Fix: Publish: Ensure Post has fully saved (including all Custom Fields / ACF / Yoast data etc) before sending status(es) to Hootsuite.

= 1.1.1 (2018-08-23) =
* Fix: Log: Report 'Plugin: Request Sent' and 'Created At' datetime using WordPress configured date time zone.
* Fix: Per Post Status: Adjust datetime based on WordPress locale, to ensure the social network schedules the status at the requested datetime.
* Fix: Status: Display Image options as full width dropdown on mobile devices

= 1.1.0 (2018-08-16) =
* Added: Per Post Status: Option to specify specific date and time to schedule individual statuses for.
* Added: Status: Option to specify number of Terms to output when using {taxonomy_} tags in statuses.  See Docs: https://www.wpzinc.com/documentation/wordpress-to-hootsuite-pro/status-settings/

= 1.0.9 (2018-08-09) =
* Added: Status: Use Post Type's Featured Image Label for the Image Dropdown option on Statuses (i.e. display Product Image when setting statuses for WooCommerce, for clarity)
* Fix: Profiles: Serve social media profile images over SSL to avoid mixed content warning messages

= 1.0.8 (2018-07-26) =
* Fix: Settings: Changed WordPress standard .nav-tab-active class to .wpzinc-nav-tab-active, to prevent third party plugins greedily trying to control our UI.
* Fix: Publish: Better handle error messages from Hootsuite's API

= 1.0.7 (2018-07-12) =
* Fix: Publish: Removed duplicate do_action() call on save_post to prevent some third party plugins running routines twice

= 1.0.6 (2018-06-28) =
* Fix: Improved licensing mechanism

= 1.0.5 (2018-05-10) =
* Added: Pinterest Support
* Fix: Licensing: Improved performance
* Fix: Activation: Deactivate free version of the plugin if it's still active

= 1.0.4 (2018-05-03)
* Fix: Status: Improved duplicate status detection to prevent false positives when statuses with the same text have conditions set, which would prevent duplicate statuses being sent to Hootsuite.
* Fix: Call to member function get_error_message() on null when attempting to fetch Hootsuite User Profile.
* Fix: Publish: Only consider publishing statuses to Hootsuite on supported Post Types (resolves issues with Advanced Custom Fields Free Version saving Fields).

= 1.0.3 (2018-04-26) =
* Added: Status Conditions: Custom Fields: Optionally define custom field(s) that are required for a status to be sent to Hootsuite.

= 1.0.2 (2018-04-19) =
* Added: Insert Tags for The Event Calendar's Event data

= 1.0.1 (2018-04-12) =
* Added: Schedule statuses relative to The Event Calendar Event's start or end date/time
* Fix: Import: Import all settings
* Fix: Export: Export all settings
* Fix: Custom Time (based on Custom Field / Post Meta Value) wasn't using the specified Post Meta Value

= 1.0.0 (2018-04-05) =
* First release.

== Upgrade Notice ==
