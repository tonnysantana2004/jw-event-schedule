<?php

namespace JWES;

class Main
{
    public static function init()
    {
        $post_type = new PostType();
        $post_type->init();

        $template_class = new Template;
        $template_class->init();

        $enqueue_scripts_class = new EnqueueScripts;
        $enqueue_scripts_class->init();

        $notifications = new Notifications();
        $notifications->init();

        // Gutenberg Blocks
        add_action('init', function () {
            register_block_type(JWES_PLUGIN_DIR . '/build/blocks/listing-grid');
            register_block_type(JWES_PLUGIN_DIR . '/build/blocks/sidebar');
        });
    }
}
