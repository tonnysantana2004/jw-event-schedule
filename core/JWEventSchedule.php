<?php

use JWEventSchedule\PostType;
use JWEventSchedule\Template;

defined('ABSPATH') || exit;

class JWEventSchedule
{
    public static function init()
    {
        $JWESPostType = new PostType();

        add_action('init', function () use ($JWESPostType) {
            $JWESPostType->create_the_post_type();
            $JWESPostType->create_the_taxonomy();
        });

        add_action('rest_api_init', function () use ($JWESPostType) {
            $JWESPostType->create_the_custom_fields();
        });

        // 2. Admin Interface Enhancements
        // TODO: use the PostType class to proccess these functionalities
        // TODO: make the columns sortable

        // Set the columns
        add_filter('manage_event_posts_columns', function ($columns) {

            $newColumns = [
                'title' => $columns['title'],
                'event_date' => 'Event Date',
                'event_location' => 'Event Location',
                'date' => $columns['date']
            ];

            return $newColumns;

        });

        // Populate the column values
        add_action('manage_event_posts_custom_column', function ($column, $post_id) {

            if ($column === 'event_date') {
                $value = get_post_meta($post_id, 'event_date', true);
                $timestamp =  strtotime($value);
                echo date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $timestamp);
            }

            if ($column === 'event_location') {
                $value = get_post_meta($post_id, 'event_location', true);
                echo $value ? esc_html($value) : '—';
            }

        }, 10, 2);

        // Use the single template for Events
        add_action('single_template', function ($originalTemplate) {
            $TemplateClass = new Template();
            return $TemplateClass->singleEvent($originalTemplate);
        });



    }

}
