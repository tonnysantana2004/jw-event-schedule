<?php

namespace JWES;

use JWES\PostType;
use JWES\Template;

class Main
{
    public static function init()
    {
        $jwes_post_type = new PostType();

        add_action(
            'init',
            function () use ($jwes_post_type) {
                $jwes_post_type->create_the_post_type();
                $jwes_post_type->create_the_taxonomy();
            }
        );

        add_action(
            'rest_api_init',
            function () use ($jwes_post_type) {
                $jwes_post_type->create_the_custom_fields();
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

        // Single Template.
        add_action(
            'single_template',
            function ($original_template) {
                $template_class = new Template();
                return $template_class->singleEvent($original_template);
            }
        );

        // Archive Template.
        add_action(
            'archive_template',
            function ($original_template) {
                $template_class = new Template();
                return $template_class->archiveEvent($original_template);
            }
        ); 

        // Assets
        $enqueue_scripts_class = new EnqueueScripts;
        $enqueue_scripts_class->init();

        
    }
}
