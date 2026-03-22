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
                    $value                         = get_post_meta($post_id, 'event_date', true);
                    $timestamp                     = strtotime($value);
                    $site_default_timestamp_format = get_option('date_format') . ' ' . get_option('time_format');
                    $date                          = date_i18n($site_default_timestamp_format, $timestamp);
                    echo esc_html($date);
                }

                if ('event_location' === $column) {
                    $value = get_post_meta($post_id, 'event_location', true);
                    echo $value ? esc_html($value) : '—';
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


        add_action(
            'enqueue_block_editor_assets',
            function () {

                wp_enqueue_script(
                    'jwes_plugin_script',
                    plugins_url('build/index.js', JWES_PLUGIN_FILE),
                    array('wp-edit-post', 'wp-element', 'wp-components', 'wp-plugins', 'wp-data'),
                    filemtime(plugin_dir_path(JWES_PLUGIN_FILE) . 'build/index.js'),
                    true
                );

                wp_enqueue_style(
                    'jwes_plugin_style',
                    plugins_url('assets/gutenberg/sidebar.css', __FILE__),
                    array(),
                    filemtime(plugin_dir_path(JWES_PLUGIN_FILE) . 'assets/gutenberg/sidebar.css'),
                    true
                );
            }
        );
    }
}
