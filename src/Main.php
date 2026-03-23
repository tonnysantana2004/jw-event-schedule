<?php

namespace JWES;

use JWES\PostType;
use JWES\Template;

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
    }
}
