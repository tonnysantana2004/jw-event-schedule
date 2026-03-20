<?php

namespace JWEventSchedule;

defined('ABSPATH') || exit;

// 1. Custom Post Type and Taxonomies
class PostType
{
    public static function init()
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
            'show_in_rest' => true, // 6. REST API Integration
            'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt'),
        );

        return register_post_type('event', $args);

    }

}
