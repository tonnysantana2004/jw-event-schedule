<?php

namespace JWES;

class EnqueueScripts
{
    public function init()
    {
        $this->gutenberg();
        $this->frontend();
    }

    public function gutenberg()
    {
        add_action(
            'enqueue_block_editor_assets',
            function () {

                $this->enqueue_script(
                    'build/index.js',
                    array('wp-edit-post', 'wp-element', 'wp-components', 'wp-plugins', 'wp-data')
                );

                $this->enqueue_style('assets/gutenberg/sidebar.css');
            }
        );
    }

    public function frontend()
    {
        add_action(
            'wp_enqueue_scripts',
            function () {

                if (!is_admin()) {
                    $this->enqueue_style('assets/frontend/style.css');
                }

                if (is_singular('event')) {
                    $this->enqueue_style('assets/frontend/single-event.css');
                }

                if (is_post_type_archive('event') || is_tax('event_type') || is_search() && 'event' === get_query_var('post_type')) {

                    $this->enqueue_style('assets/frontend/archive-event.css');

                    $this->enqueue_style('assets/flatpickr/style.css');
                    $this->enqueue_script('assets/flatpickr/script.js');
                }
            }
        );
    }

    public function enqueue_style($related_file_path, $handle = false)
    {;
        wp_enqueue_style(
            $handle ? '' : str_replace(['/', '.'], '-', $related_file_path),
            plugins_url($related_file_path, JWES_PLUGIN_FILE),
            array(),
            filemtime(plugin_dir_path(JWES_PLUGIN_FILE) . $related_file_path),
            false
        );
    }


    public function enqueue_script($related_file_path, $dependencies = '', $handle = false)
    {
        wp_enqueue_script(
            $handle ? '' : str_replace(['/', '.'], '-', $related_file_path),
            plugins_url($related_file_path, JWES_PLUGIN_FILE),
            $dependencies,
            filemtime(plugin_dir_path(JWES_PLUGIN_FILE) . $related_file_path),
            true
        );
    }
}
