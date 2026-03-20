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

defined('ABSPATH') || exit;

defined('JWES_PLUGIN_FILE') || define('JWES_PLUGIN_FILE', __FILE__);
defined('JWES_PLUGIN_DIR') || define('JWES_PLUGIN_DIR', __DIR__);

// Load the autoload and core files
require dirname(JWES_PLUGIN_FILE)  . '/vendor/autoload.php';
require dirname(JWES_PLUGIN_FILE) . '/core/JWEventSchedule.php';

JWEventSchedule::init();
