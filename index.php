<?php

/**
 * Plugin Name: Xenio Consent Manager
 * Plugin URI: https://www.xenio-marketing.de
 * Description: Cookies and consent manager for WordPress websites.
 * Version: 1.0.0
 * Text Domain: xcm
 * Domain Path: /languages
 */

use XenioCookies\XenioCookies;

define( 'XCM_FILE', __FILE__ );
define( 'XCM_DIR', plugin_dir_path( __FILE__ ) );
define( 'XCM_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'XCM_VERSION', '1.0.0' );
define( 'XCM_DB_VERSION', '1.0.0' );
define( 'XCM_NAME', 'xcm' );

require XCM_DIR . 'vendor/autoload.php';

new XenioCookies();