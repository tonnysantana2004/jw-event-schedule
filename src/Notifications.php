<?php

namespace JWES;

defined('ABSPATH') || exit;

class Notifications
{

    public function init()
    {
        add_action('save_post', function ($post_id, $post, $update) {

            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE || get_post_type($post_id) !== 'event') {
                return;
            }

            $post_status = $post->post_status;
            $new_post = $post->post_date === $post->post_modified;
            $users = get_users();

            foreach ($users as $user) {

                $to = $user->user_email;

                if ($update && $new_post && $post_status === 'publish') {
                    $subject = 'New Event' . ' - ' .  $post->post_title;
                    $message = "Hi ". $user->display_name ."! There's a new event waiting for you. Click here to confirm your attendance.";
                } elseif ($update && $post_status === 'publish') {
                    $subject = 'Event Updated' . ' - ' . $post->post_title;
                    $message = "Updates about the event! Some changes have been made, click here to check them out.";
                } elseif ($update && $post_status === 'trash') {
                    $subject = 'Event Canceled' . ' - ' . $post->post_title;
                    $message = "Hey ". $user->display_name .", how are you? We regret to inform you that this event has been cancelled.";
                } else {
                    return;
                }
                $headers = ['Content-Type: text/html; charset=UTF-8'];
                wp_mail($to, $subject, $message, $headers);
            }
        }, 25, 3);
    }
}
