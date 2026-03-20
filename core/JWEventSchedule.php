<?php

use JWEventSchedule\PostType;

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
    }

}
