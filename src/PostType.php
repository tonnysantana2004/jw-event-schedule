<?php

namespace JWES;

use DateTime;
use DateTimeZone;

defined('ABSPATH') || exit;

// 1. Custom Post Type and Taxonomies
class PostType
{

    public function init()
    {

        add_action(
            'init',
            function () {
                $this->create_the_post_type();
                $this->create_the_taxonomy();
            }
        );

        add_action(
            'rest_api_init',
            function () {
                $this->create_the_custom_fields();
            }
        );

        // 2. Admin Interface Enhancements
        // TODO: use the PostType class to proccess these functionalities
        // TODO: make the columns sortable
        // The columns.
        add_filter(
            'manage_event_posts_columns',
            function ($columns) {

                $new_columns = array(
                    'title'          => $columns['title'],
                    'event_date'     => 'Event Date',
                    'event_location' => 'Event Location',
                    'date'           => $columns['date'],
                );

                return $new_columns;
            }
        );

        // Column Values.
        add_action(
            'manage_event_posts_custom_column',
            function ($column, $post_id) {

                if ('event_date' === $column) {
                    echo PostType::get_the_event_date_formated($post_id);
                }

                if ('event_location' === $column) {
                    echo PostType::get_the_event_location_formated($post_id);
                }
            },
            10,
            2
        );

        $this->custom_filtering();
    }

    public function create_the_post_type()
    {
        $args = array(
            'labels' => array(
                'name'          => 'Events',
                'singular_name' => 'Event',
                'menu_name'     => 'Events',
                'add_new'       => 'Add New event',
                'add_new_item'  => 'Add New event',
                'new_item'      => 'New event',
                'edit_item'     => 'Edit event',
                'view_item'     => 'View event',
                'all_items'     => 'All events',
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => [
                'slug' => 'events'
            ],

            // 6. REST API Integration
            'show_in_rest' => true,
            'rest_base' => 'events',
            'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'custom-fields'),
        );

        return register_post_type('event', $args);
    }

    public function create_the_taxonomy()
    {
        $args = array(
            'labels'       => array(
                'name'          => 'Event Types',
                'singular_name' => 'Event Type',
                'edit_item'     => 'Edit Event Type',
                'update_item'   => 'Update Event Type',
                'add_new_item'  => 'Add New Event Type',
                'new_item_name' => 'New Event Type Name',
                'menu_name'     => 'Event Type',
            ),
            'hierarchical' => true,
            'rewrite' => array(
                'slug' => 'event-type',
            ),

            // 6. REST API Integration
            'show_in_rest'           => true,
        );

        register_taxonomy('event_type', 'event', $args);
    }

    // 2. Admin Interface Enhancements:
    public function create_the_custom_fields()
    {
        register_meta(
            'post',
            'event_date',
            array(
                'object_subtype' => 'event',
                'label' => __('Event Date', 'jw-event-schedule'),
                'type' => 'string',

                // 6. REST API Integration
                'show_in_rest' => true,
                'single' => true,

                // 8. Security Best Practices
                // There is no default sanatizer for datetime
                'sanitize_callback' => 'sanitize_text_field',
                'auth_callback' => function () {
                    return current_user_can('edit_posts');
                }
            )
        );

        register_meta(
            'post',
            'event_location',
            array(
                'object_subtype' => 'event',
                'label' => __('Event Location', 'jw-event-schedule'),
                'type' => 'string',

                // 6. REST API Integration
                'show_in_rest' => true,
                'single' => true,

                // 8. Security Best Practices
                'sanitize_callback' => 'sanitize_text_field',
                'auth_callback' => function () {
                    return current_user_can('edit_posts');
                }
            )
        );
    }

    public function custom_filtering()
    {
        add_action('pre_get_posts', function ($query) {

            if (is_post_type_archive('event') || is_tax('event_type') || is_search() && 'event' === get_query_var('post_type')) {
               
                if (!isset($_GET['date_range'])) {
                    return;
                }

                $dates = explode('to', $_GET['date_range']);

                $date1 = sanitize_text_field(trim($dates[0]));
                $date2 = sanitize_text_field(trim($dates[1]));

                $start = (new DateTime($date1 ?? '1980-1-1'))->setTime(0, 0)->format('U');
                $end   = (new DateTime($date2 ?? '2030-1-1'))->setTime(23, 59, 59)->format('U');

                $meta_query = $query->get('meta_query') ?: [];

                $meta_query[] = [
                    'key'     => 'event_date',
                    'value'   => [$start, $end],
                    'compare' => 'BETWEEN',
                    'type'    => 'NUMERIC'
                ];

                $query->set('meta_query', $meta_query);
            }
        });
    }

    public static function get_the_event_date_formated($post_id, $include_date = true, $include_hour = true): string
    {

        $event_date = get_post_meta($post_id, 'event_date', true);

        if (empty($event_date)) return 'To decide';

        $timestamp_format = "";

        if ($include_date) {
            $timestamp_format .= get_option('date_format');
        }

        if ($include_date && $include_hour) {
            $timestamp_format .= ' | ';
        }

        if ($include_hour) {
            $timestamp_format .= get_option('time_format');
        }

        $date = wp_date($timestamp_format, $event_date);
        return esc_html($date);
    }

    public static function get_the_event_location_formated($post_id): string
    {
        $location = get_post_meta($post_id, 'event_location', true);
        return $location ? esc_html($location) : 'Online';
    }
}
