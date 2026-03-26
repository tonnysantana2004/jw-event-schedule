<?php
/**
 * Template Class
 *
 * @package JW Event Schedule
 */

namespace JWES;

/**
 * Handle the template import functionalities.
 */
class Template {

	/**
	 * Start the class functions.
	 */
	public function init() {
		$this->single_template();
		$this->archive_template();
	}

	/**
	 * Load the single template.
	 */
	public function single_template() {

		add_action(
			'single_template',
			function () {

				$single_template = JWES_PLUGIN_DIR . '/templates/single-event.php';

				if ( is_singular( 'event' ) ) {
					if ( file_exists( $single_template ) ) {
						return $single_template;
					}
				}
			}
		);
	}

	/**
	 * Load the archive template.
	 */
	public function archive_template() {

		add_action(
			'archive_template',
			function () {

				$archive_template = JWES_PLUGIN_DIR . '/templates/archive-event.php';

				if ( is_post_type_archive( 'event' ) || is_tax( 'event_type' ) ) {
					if ( file_exists( $archive_template ) ) {
						return $archive_template;
					}
				}
			}
		);

		add_action(
			'search_template',
			function () {

				$archive_template = JWES_PLUGIN_DIR . '/templates/archive-event.php';

				if ( is_search() && 'event' === get_query_var( 'post_type' ) ) {
					if ( file_exists( $archive_template ) ) {
						return $archive_template;
					}
				}
			}
		);
	}
}
