<?php
/**
 * Notifications Class
 *
 * @package JW Event Schedule
 */

namespace JWES;

defined( 'ABSPATH' ) || exit;

/**
 * Handle the notifications functionalities.
 */
class Notifications {

	/**
	 * Start the class functions.
	 */
	public function init() {
		add_action( 'save_post', array( $this, 'jwes_notification_service' ), 25, 3 );
	}

	/**
	 * Notification Service.
	 *
	 * @param string|integer $post_id = ID from the event.
	 * @param obj            $post = Post object.
	 * @param boolean        $update = Tells if the request is for an update.
	 */
	public function jwes_notification_service( $post_id, $post, $update ) {

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE || get_post_type( $post_id ) !== 'event' ) {
			return;
		}

		$post_status = $post->post_status;
		$new_post    = $post->post_date === $post->post_modified;
		$users       = get_users();

		foreach ( $users as $user ) {
			$to = $user->user_email;

			if ( $update && $new_post && 'publish' === $post_status ) {
				$subject = __( 'New Event', 'jw-event-schedule' ) . ' - ' . $post->post_title;
				$message = __( 'Hi', 'jw-event-schedule' ) . $user->display_name . '! ' . __( "There's a new event waiting for you. Click here to confirm your attendance.", 'jw-event-schedule' );
			} elseif ( $update && 'publish' === $post_status ) {
				$subject = __( 'Event Updated', 'jw-event-schedule' ) . ' - ' . $post->post_title;
				$message = __( 'Updates about the event! Some changes have been made, click here to check them out.', 'jw-event-schedule' );
			} elseif ( $update && 'trash' === $post_status ) {
				$subject = __( 'Event Canceled', 'jw-event-schedule' ) . ' - ' . $post->post_title;
				$message = __( 'Hey', 'jw-event-schedule' ) . ' ' . $user->display_name . __( ', how are you? We regret to inform you that this event has been cancelled.', 'jw-event-schedule' );
			} else {
				return;
			}
			$headers = array( 'Content-Type: text/html; charset=UTF-8' );
			wp_mail( $to, $subject, $message, $headers );
		}
	}
}
