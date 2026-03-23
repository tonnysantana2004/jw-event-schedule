<?php

use JWES\PostType;
use JWES\Notifications;

/**
 * Class Test_Main
 *
 * @package Jw_Event_Schedule
 */

/**
 * Tests the plugin's core functionalities.
 */
class Test_Main extends WP_UnitTestCase
{

	public function test_event_post_type_is_being_registered()
	{
		$post_type = new PostType();
		$post_type->init();

		$this->assertTrue(post_type_exists('event'));
	}

	public function test_event_type_taxonomy_is_being_registered()
	{
		$post_type = new PostType();
		$post_type->init();

		$this->assertTrue(taxonomy_exists('event_type'));
	}


	public function test_api_endpoint_is_being_registered()
	{
		$post_type = new PostType();
		$post_type->init();

		$this->assertArrayHasKey('/jwes/v1/attendance', rest_get_server()->get_routes());
	}

	public function test_notifications_function_is_being_registered()
	{
		$notifications = new Notifications();
		$notifications->init();

		$this->assertNotFalse(has_action('save_post', [$notifications, 'jwes_notification_service']));
	}
}
