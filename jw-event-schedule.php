<?php
/**
 * Plugin Name:       JW Event Schedule
 * Plugin URI:        https://portfolio.tonnysantana.com
 * Description:       A plugin for create, manage and list schedules in WordPress.
 * Author:            Tonny Santana
 * Author URI:        https://portfolio.tonnysantana.com
 * Text Domain:       jw-event-schedule
 * Domain Path:       /languages
 * Version:           1.0.0
 * Requires at least: 6.9.4
 * Requires PHP:      8.2
 *
 * @package           JW Event Schedule
 */

namespace JWES;

defined( 'ABSPATH' ) || exit;
defined( 'JWES_PLUGIN_FILE' ) || define( 'JWES_PLUGIN_FILE', __FILE__ );
defined( 'JWES_PLUGIN_DIR' ) || define( 'JWES_PLUGIN_DIR', __DIR__ );

// Load the autoload and core files.
require dirname( JWES_PLUGIN_FILE ) . '/vendor/autoload.php';

add_action(
	'plugins_loaded',
	function () {
		$post_type = new PostType();
		$post_type->init();

		$template_class = new Template();
		$template_class->init();

		$enqueue_scripts_class = new EnqueueScripts();
		$enqueue_scripts_class->init();

		$notifications = new Notifications();
		$notifications->init();
	}
);
