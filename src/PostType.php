<?php
/**
 * Event Post Type Class
 *
 * @package JW Event Schedule
 */

namespace JWES;

use DateTime;

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
	public function create_the_post_type() {
		add_action(
			'init',
			function () {
				$args = array(
					'labels'       => array(
						'name'          => esc_html__( 'Events', 'jw-event-schedule' ),
						'singular_name' => esc_html__( 'Event', 'jw-event-schedule' ),
						'menu_name'     => esc_html__( 'Events', 'jw-event-schedule' ),
						'add_new'       => esc_html__( 'Add New event', 'jw-event-schedule' ),
						'add_new_item'  => esc_html__( 'Add New event', 'jw-event-schedule' ),
						'new_item'      => esc_html__( 'New event', 'jw-event-schedule' ),
						'edit_item'     => esc_html__( 'Edit event', 'jw-event-schedule' ),
						'view_item'     => esc_html__( 'View event', 'jw-event-schedule' ),
						'all_items'     => esc_html__( 'All events', 'jw-event-schedule' ),
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
	public function create_the_taxonomy() {
		add_action(
			'init',
			function () {
				$args = array(
					'labels'       => array(
						'name'          => esc_html__( 'Event Types', 'jw-event-schedule' ),
						'singular_name' => esc_html__( 'Event Type', 'jw-event-schedule' ),
						'edit_item'     => esc_html__( 'Edit Event Type', 'jw-event-schedule' ),
						'update_item'   => esc_html__( 'Update Event Type', 'jw-event-schedule' ),
						'add_new_item'  => esc_html__( 'Add New Event Type', 'jw-event-schedule' ),
						'new_item_name' => esc_html__( 'New Event Type Name', 'jw-event-schedule' ),
						'menu_name'     => esc_html__( 'Event Type', 'jw-event-schedule' ),
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
	public function create_the_custom_fields() {

		add_action(
			'rest_api_init',
			function () {

				register_meta(
					'post',
					'event_date',
					array(
						'object_subtype'    => 'event',
						'label'             => esc_html__( 'Event Date', 'jw-event-schedule' ),
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
						'label'             => esc_html__( 'Event Location', 'jw-event-schedule' ),
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
						'object_subtype'    => 'event',
						'label'             => esc_html__( 'Attendance List', 'jw-event-schedule' ),
						'type'              => 'array',
						'single'            => 'true',

						// 6. REST API Integration
						'show_in_rest'      => array(
							'schema' => array(
								'type'  => 'array',
								'items' => array(
									'type' => 'integer',
								),
							),
						),

						// 8. Security Best Practices
						'sanitize_callback' => 'sanitize_text_field',
						'auth_callback'     => function () {
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
	public function create_listing_columns() {
		add_filter(
			'manage_event_posts_columns',
			function ( $columns ) {

				$new_columns = array(
					'title'          => $columns['title'],
					'event_date'     => 'Event Date',
					'event_location' => 'Event Location',
					'date'           => $columns['date'],
				);

				return $new_columns;
			}
		);

		add_action(
			'manage_event_posts_custom_column',
			function ( $column, $post_id ) {

				if ( 'event_date' === $column ) {
					echo esc_html( PostType::get_event_date( $post_id ) );
				}

				if ( 'event_location' === $column ) {
					echo esc_html( PostType::get_event_location( $post_id ) );
				}
			},
			10,
			2
		);
	}

	/**
	 * Add new fields to the main query.
	 */
	public function create_custom_filtering_rules(): void {
		add_action(
			'pre_get_posts',
			function ( $query ) {

				if ( is_post_type_archive( 'event' ) || is_tax( 'event_type' ) || is_search() && 'event' === get_query_var( 'post_type' ) ) {
					if ( ! isset( $_GET['date_range'] ) ) {
						return;
					}

					$date_range = '';

					if (
					isset( $_GET['search_nonce'] ) &&
					wp_verify_nonce(
						sanitize_text_field( wp_unslash( $_GET['search_nonce'] ) ),
						'search_action'
					)
					) {
						$date_range = sanitize_text_field( wp_unslash( $_GET['date_range'] ) );
					}

					$dates = explode( 'to', $date_range );

					if ( ! isset( $dates[0] ) || ! isset( $dates[1] ) ) {
						return;
					}

					$date1 = sanitize_text_field( $dates[0] );
					$date2 = sanitize_text_field( $dates[1] );

					$start = ( new DateTime( $date1 ?? '1980-1-1' ) )->setTime( 0, 0 )->format( 'U' );
					$end   = ( new DateTime( $date2 ?? '2030-1-1' ) )->setTime( 23, 59, 59 )->format( 'U' );

					$meta_query = $query->get( 'meta_query' );

					if ( ! is_array( $meta_query ) ) {
						$meta_query = array();
					}

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
	public function create_custom_api_endpoint(): void {

		add_action(
			'rest_api_init',
			function () {

				register_rest_route(
					'jwes/v1',
					'/attendance',
					array(
						'methods'             => 'POST',
						'callback'            => function ( $request ) {

							$user_id = intval( sanitize_text_field( $request['user_id'] ) );
							$post_id = intval( sanitize_text_field( $request['post_id'] ) );

							$current_attendance_list = get_post_meta( $post_id, 'attendance_list', true ) ?? array();

							if ( $user_id && ! in_array( $user_id, $current_attendance_list, true ) ) {
								$current_attendance_list[] = $user_id;
								update_post_meta( $post_id, 'attendance_list', $current_attendance_list );
							}

							$response_data = array(
								'message'    => esc_html__( 'Attendance List Updated.', 'jw-event-schedule' ),
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

							$user_id = intval( sanitize_text_field( $request['user_id'] ) );
							$post_id = intval( sanitize_text_field( $request['post_id'] ) );

							$current_attendance_list = get_post_meta( $post_id, 'attendance_list', true ) ?? array();

							if ( $user_id && in_array( $user_id, $current_attendance_list, true ) ) {
								$current_attendance_list = array_values( array_diff( $current_attendance_list, array( $user_id ) ) );
								update_post_meta( $post_id, 'attendance_list', $current_attendance_list );
							}

							$response_data = array(
								'message'    => esc_html__( 'Attendance List Updated.', 'jw-event-schedule' ),
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
			return esc_html__( 'To decide', 'jw-event-schedule' );
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
		return esc_html( $date );
	}
	/**
	 * Return the event location formated.
	 *
	 * @param string|integer $post_id = The ID of the event.
	 */
	public static function get_event_location( $post_id ): string {
		$location = get_post_meta( $post_id, 'event_location', true );
		return $location ? esc_html( $location ) : esc_html__( 'Online', 'jw-event-schedule' );
	}
}
