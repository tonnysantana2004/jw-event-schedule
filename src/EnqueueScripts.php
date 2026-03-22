<?php

namespace JWES;

class EnqueueScripts
{
    public function init()
    {
        add_action(
            'enqueue_block_editor_assets',
            function () {

                wp_enqueue_script(
                    'jwes_gutenberg_script',
                    plugins_url('build/index.js', JWES_PLUGIN_FILE),
                    array('wp-edit-post', 'wp-element', 'wp-components', 'wp-plugins', 'wp-data'),
                    filemtime(plugin_dir_path(JWES_PLUGIN_FILE) . 'build/index.js'),
                    true
                );

                wp_enqueue_style(
                    'jwes_gutenberg_style',
                    plugins_url('assets/gutenberg/sidebar.css', JWES_PLUGIN_FILE),
                    array(),
                    filemtime(plugin_dir_path(JWES_PLUGIN_FILE) . 'assets/gutenberg/sidebar.css'),
                );
            }
        );

        add_action(
            'wp_enqueue_scripts',
            function () {

                wp_enqueue_style(
                    'jwes_frontend_style',
                    plugins_url('assets/frontend/style.css', JWES_PLUGIN_FILE),
                    array(),
                    filemtime(plugin_dir_path(JWES_PLUGIN_FILE) . 'assets/frontend/style.css')
                );

                if (is_singular('event')) {

                    wp_enqueue_style(
                        'jwes_frontend_single_event_style',
                        plugins_url('assets/frontend/single-event.css', JWES_PLUGIN_FILE),
                        array(),
                        filemtime(plugin_dir_path(JWES_PLUGIN_FILE) . 'assets/frontend/single-event.css')
                    );
                }

                if (is_post_type_archive('event') || is_tax('event_type')) {

                    wp_enqueue_style(
                        'jwes_frontend_archive_event_style',
                        plugins_url('assets/frontend/archive-event.css', JWES_PLUGIN_FILE),
                        array(),
                        filemtime(plugin_dir_path(JWES_PLUGIN_FILE) . 'assets/frontend/archive-event.css')
                    );
                }
            }
        );
    }
}
