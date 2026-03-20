<?php

use JWEventSchedule\PostType;

defined('ABSPATH') || exit;

class JWEventSchedule
{
    public static function init()
    {
        add_action('init', [self::class, 'handleInit']);
    }

    public static function handleInit()
    {
        PostType::init();
    }

}
