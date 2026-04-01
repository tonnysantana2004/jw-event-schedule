<?php
/**
 * Event Post Type Class
 *
 * @package JW Event Schedule
 */

namespace JWES;

use DateTime;
use Exception;
use JWES\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Handle the Post Type and Taxonomy functionalities.
 */
class PostType {

	/**
	 * Start the class functions.
	 */
	public function init() {
		$this->create_the_post_type();
		$this->create_the_taxonomy();
		$this->create_the_custom_fields();
		$this->create_listing_columns();
		$this->create_custom_filtering_rules();
		$this->create_custom_api_endpoint();
	}

	/**
	 * Register the event post type.
	 */
	private function create_the_post_type() {
		add_action(
			'init',
			function () {
				$args = array(
					'labels'       => array(
						'name'          => __( 'Events', 'jw-event-schedule' ),
						'singular_name' => __( 'Event', 'jw-event-schedule' ),
						'menu_name'     => __( 'Events', 'jw-event-schedule' ),
						'add_new'       => __( 'Add New event', 'jw-event-schedule' ),
						'add_new_item'  => __( 'Add New event', 'jw-event-schedule' ),
						'new_item'      => __( 'New event', 'jw-event-schedule' ),
						'edit_item'     => __( 'Edit event', 'jw-event-schedule' ),
						'view_item'     => __( 'View event', 'jw-event-schedule' ),
						'all_items'     => __( 'All events', 'jw-event-schedule' ),
					),
					'public'       => true,
					'has_archive'  => true,
					'rewrite'      => array(
						'slug' => 'events',
					),

					// 6. REST API Integration
					'show_in_rest' => true,
					'rest_base'    => 'events',
					'supports'     => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'custom-fields' ),
				);

				register_post_type( 'event', $args );
			}
		);
	}

	/**
	 * Register the taxonomy event type.
	 */
	private function create_the_taxonomy() {
		add_action(
			'init',
			function () {
				$args = array(
					'labels'       => array(
						'name'          => __( 'Event Types', 'jw-event-schedule' ),
						'singular_name' => __( 'Event Type', 'jw-event-schedule' ),
						'edit_item'     => __( 'Edit Event Type', 'jw-event-schedule' ),
						'update_item'   => __( 'Update Event Type', 'jw-event-schedule' ),
						'add_new_item'  => __( 'Add New Event Type', 'jw-event-schedule' ),
						'new_item_name' => __( 'New Event Type Name', 'jw-event-schedule' ),
						'menu_name'     => __( 'Event Type', 'jw-event-schedule' ),
					),
					'hierarchical' => true,
					'rewrite'      => array(
						'slug' => 'event-type',
					),

					// 6. REST API Integration
					'show_in_rest' => true,
				);

				register_taxonomy( 'event_type', 'event', $args );
			}
		);
	}

	/**
	 * Add custom fields to the post type.
	 */
	private function create_the_custom_fields() {

		add_action(
			'rest_api_init',
			function () {

				register_meta(
					'post',
					'event_date',
					array(
						'object_subtype'    => 'event',
						'label'             => __( 'Event Date', 'jw-event-schedule' ),
						'type'              => 'string',

						// 6. REST API Integration
						'show_in_rest'      => true,
						'single'            => true,

						// 8. Security Best Practices
						// There is no default sanatizer for datetime
						'sanitize_callback' => 'sanitize_text_field',
						'auth_callback'     => function () {
							return current_user_can( 'edit_posts' );
						},
					)
				);

				register_meta(
					'post',
					'event_location',
					array(
						'object_subtype'    => 'event',
						'label'             => __( 'Event Location', 'jw-event-schedule' ),
						'type'              => 'string',

						// 6. REST API Integration
						'show_in_rest'      => true,
						'single'            => true,

						// 8. Security Best Practices
						'sanitize_callback' => 'sanitize_text_field',
						'auth_callback'     => function () {
							return current_user_can( 'edit_posts' );
						},
					)
				);

				register_meta(
					'post',
					'attendance_list',
					array(
						'object_subtype' => 'event',
						'label'          => __( 'Attendance List', 'jw-event-schedule' ),
						'type'           => 'array',
						'single'         => true,

						// 6. REST API Integration
						'show_in_rest'   => array(
							'schema' => array(
								'type'  => 'array',
								'items' => array(
									'type' => 'integer',
								),
							),
						),

						// 8. Security Best Practices
						'auth_callback'  => function () {
							return current_user_can( 'edit_posts' );
						},
					)
				);
			}
		);
	}

	/**
	 * Add new columns to the events listing view on the WordPress dashboard.
	 */
	private function create_listing_columns() {
		add_filter(
			'manage_event_posts_columns',
			function ( $columns ) {

				$new_columns = array(
					'title'          => $columns['title'],
					'event_date'     => esc_html__( 'Event Date', 'jw-event-schedule' ),
					'event_location' => esc_html__( 'Event Location', 'jw-event-schedule' ),
					'date'           => $columns['date'],
				);

				return $new_columns;
			}
		);

		add_action(
			'manage_event_posts_custom_column',
			function ( $column, $post_id ) {

				if ( 'event_date' === $column ) {
					echo esc_html( self::get_event_date( $post_id ) );
				}

				if ( 'event_location' === $column ) {
					echo esc_html( self::get_event_location( $post_id ) );
				}
			},
			10,
			2
		);
	}

	/**
	 * Add new fields to the search query.
	 */
	private function create_custom_filtering_rules(): void {
		add_action(
			'pre_get_posts',
			function ( $query ) {

				if ( ! $query->is_main_query() ) {
					return;
				}

				if ( is_post_type_archive( 'event' ) || is_tax( 'event_type' ) || ( is_search() && 'event' === get_query_var( 'post_type' ) ) ) {
					if ( ! wp_verify_nonce( Helper::sanitize_setted_get_field( 'search_nonce', true ), 'search_action' ) ) {
						return;
					}

					$date_range = Helper::sanitize_setted_get_field( 'date_range', true );

					$dates = ! empty( $date_range ) ? explode( 'to', $date_range ) : array();

					try {
						$start = ( new DateTime( trim( $dates[0] ?? '1980-1-1' ) ) )->setTime( 0, 0 )->format( 'U' );
						$end   = ( new DateTime( trim( $dates[1] ?? '2050-1-1' ) ) )->setTime( 23, 59, 59 )->format( 'U' );
					} catch ( Exception $e ) {
						return;
					}

					$meta_query = ! empty( $query->get( 'meta_query' ) ) ? $query->get( 'meta_query' ) : array();

					$meta_query[] = array(
						'key'     => 'event_date',
						'value'   => array( $start, $end ),
						'compare' => 'BETWEEN',
						'type'    => 'NUMERIC',
					);

					$query->set( 'meta_query', $meta_query );
				}
			}
		);
	}

	/**
	 * Add the attendance endpoints to add and remove users from the list.
	 */
	private function create_custom_api_endpoint(): void {

		add_action(
			'rest_api_init',
			function () {

				register_rest_route(
					'jwes/v1',
					'/attendance',
					array(
						'methods'             => 'POST',
						'callback'            => function ( $request ) {

							$user_id = get_current_user_id();
							$post_id = intval( sanitize_text_field( $request['post_id'] ) );

							if ( get_post_type( $post_id ) !== 'event' ) {
								return;
							}

							$current_attendance_list = get_post_meta( $post_id, 'attendance_list', true );

							if ( ! is_array( $current_attendance_list ) ) {
								$current_attendance_list = array();
							}

							if ( $user_id && ! in_array( $user_id, $current_attendance_list, true ) ) {
								$current_attendance_list[] = $user_id;
								update_post_meta( $post_id, 'attendance_list', $current_attendance_list );
							}

							$response_data = array(
								'message'    => __( 'Attendance List Updated.', 'jw-event-schedule' ),
								'attendance' => $current_attendance_list,
								'timestamp'  => current_time( 'mysql' ),
							);

							return rest_ensure_response( $response_data );
						},
						'permission_callback' => function () {
							return is_user_logged_in();
						},
					)
				);

				register_rest_route(
					'jwes/v1',
					'/attendance',
					array(
						'methods'             => 'DELETE',
						'callback'            => function ( $request ) {

							$user_id = get_current_user_id();
							$post_id = intval( sanitize_text_field( $request['post_id'] ) );

							if ( 'event' !== get_post_type( $post_id ) ) {
								return;
							}

							$current_attendance_list = get_post_meta( $post_id, 'attendance_list', true );

							if ( ! is_array( $current_attendance_list ) ) {
								$current_attendance_list = array();
							}

							if ( $user_id && in_array( $user_id, $current_attendance_list, true ) ) {
								$current_attendance_list = array_values( array_diff( $current_attendance_list, array( $user_id ) ) );
								update_post_meta( $post_id, 'attendance_list', $current_attendance_list );
							}

							$response_data = array(
								'message'    => __( 'Attendance List Updated.', 'jw-event-schedule' ),
								'attendance' => $current_attendance_list,
								'timestamp'  => current_time( 'mysql' ),
							);

							return rest_ensure_response( $response_data );
						},
						'permission_callback' => function () {
							return is_user_logged_in();
						},
					)
				);
			}
		);
	}

	/**
	 * Return the event date field formated.
	 *
	 * @param string|integer $post_id = The ID of the event.
	 * @param bool           $include_date =  Optional. Show the date of the event.
	 * @param bool           $include_hour =  Optional. Show the hour of the event.
	 */
	public static function get_event_date( $post_id, $include_date = true, $include_hour = true ): string {

		$event_date = get_post_meta( $post_id, 'event_date', true );

		if ( empty( $event_date ) ) {
			return __( 'To decide', 'jw-event-schedule' );
		}

		$timestamp_format = '';

		if ( $include_date ) {
			$timestamp_format .= get_option( 'date_format' );
		}

		if ( $include_date && $include_hour ) {
			$timestamp_format .= ' | ';
		}

		if ( $include_hour ) {
			$timestamp_format .= get_option( 'time_format' );
		}

		$date = wp_date( $timestamp_format, $event_date );
		return $date;
	}
	/**
	 * Return the event location formated.
	 *
	 * @param string|integer $post_id = The ID of the event.
	 */
	public static function get_event_location( $post_id ): string {
		$location = get_post_meta( $post_id, 'event_location', true );
		return $location ? $location : __( 'Online', 'jw-event-schedule' );
	}
}
