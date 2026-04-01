<?php
/**
 * Helper Class
 *
 * @package JW Event Schedule
 */

namespace JWES;

defined( 'ABSPATH' ) || exit;

/**
 * Useful static functions to use on the code.
 */
class Helper {

	/**
	 * Return $_GET fields sanitized.
	 * Make sure to verify the nonce before using this function.
	 *
	 * @param string $field = the to be sanitized.
	 */
	public static function sanitize_setted_get_field( $field ): string {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return isset( $_GET[ $field ] ) ? sanitize_text_field( wp_unslash( $_GET[ $field ] ) ) : '';
	}
}
