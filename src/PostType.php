<?php

namespace JWEventSchedule;

defined('ABSPATH') || exit;

// 1. Custom Post Type and Taxonomies
class PostType
{
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

            // 6. REST API Integration
            'show_in_rest' => true,
            'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'custom-fields'),
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

}
