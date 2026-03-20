<?php

/**
 * Plugin Name:     JW Event Schedule
 * Plugin URI:      https://portfolio.tonnysantana.com
 * Description:     A plugin for create, manage and list schedules in wordpress.
 * Author:          Tonny Santana
 * Author URI:      https://portfolio.tonnysantana.com
 * Text Domain:     jw-event-schedule
 * Domain Path:     /languages
 * Version:         0.1.0
 * Requires at least: 6.9.4
 * Requires PHP: 8.2
 *
 * @package         Jw_Event_Schedule
 */

if (defined('JWES_PLUGIN_FILE')) {
    define('JWES_PLUGIN_FILE', __FILE__);
}

// Load core files
require JWES_PLUGIN_FILE . '/vendor/autoload.php';
