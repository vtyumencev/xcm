<?php

/**
 * Plugin Name: Xenio Consent Manager
 * Plugin URI: https://github.com/vtyumencev/xcm
 * Description: Cookies and consent manager for WordPress websites.
 * Version: 0.1.0-alpha
 * Text Domain: xcm
 * Domain Path: /languages
 */

use XenioCookies\XenioCookies;

define('XCM_FILE', __FILE__);
define('XCM_DIR', plugin_dir_path( __FILE__ ));
define('XCM_DIR_URL', plugin_dir_url( __FILE__ ));
define('XCM_VERSION', '0.1.0-beta');
define('XCM_DB_VERSION', 1);
define('XCM_NAME', 'xcm');

require XCM_DIR . 'vendor/autoload.php';

XenioCookies::getInstance();