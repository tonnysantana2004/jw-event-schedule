<?php
/**
 * Enqueue Script Class
 *
 * @package JW Event Schedule
 */

namespace JWES;

/**
 * Handle the core scripts functionalities.
 */
class EnqueueScripts {

	/**
	 * Start the class functions.
	 */
	public function init() {
		$this->frontend();
		$this->gutenberg_editor();
	}

	/**
	 * Load the frontend assets.
	 */
	public function frontend() {
		add_action(
			'wp_enqueue_scripts',
			function () {

				if ( ! is_admin() ) {
					$this->enqueue_style( 'build/frontend/css/style.css' );
				}

				if ( is_singular( 'event' ) ) {
					$this->enqueue_style( 'build/frontend/css/single-event.css' );
					$this->enqueue_script( 'build/frontend/js/single-event.js', array( 'wp-api-fetch' ) );
				}

				if ( is_post_type_archive( 'event' ) || is_tax( 'event_type' ) || is_search() && 'event' === get_query_var( 'post_type' ) ) {
					$this->enqueue_style( 'build/frontend/css/archive-event.css' );
					$this->enqueue_script( 'build/frontend/js/archive-event.js' );
				}
			}
		);
	}

	/**
	 * Load the gutenberg blocks and assets.
	 */
	public function gutenberg_editor() {
		add_action(
			'init',
			function () {
				register_block_type( JWES_PLUGIN_DIR . '/build/gutenberg/blocks/listing-grid' );
			}
		);
		add_action(
			'wp_enqueue_scripts',
			function () {
				$this->enqueue_style( 'build/gutenberg/editor/style.css' );

				$this->enqueue_script(
					'build/gutenberg/editor/index.js',
					array(
						'wp-blocks',
						'wp-i18n',
						'wp-element',
						'wp-components',
						'wp-editor',
						'wp-plugins',
						'wp-data',
						'wp-edit-post',
					)
				);
			}
		);
	}

	/**
	 * Enqueue style function.
	 *
	 * @param string      $related_file_path = Full URL of the stylesheet, or path of the stylesheet relative to the WordPress root directory. Default empty.
	 * @param bool|string $handle =  Optional. Name of the stylesheet. Should be unique.
	 */
	public function enqueue_style( $related_file_path, $handle = false ) {
		wp_enqueue_style(
			$handle ? '' : str_replace( array( '/', '.' ), '-', $related_file_path ),
			plugins_url( $related_file_path, JWES_PLUGIN_FILE ),
			array(),
			filemtime( plugin_dir_path( JWES_PLUGIN_FILE ) . $related_file_path ),
			false
		);
	}

	/**
	 * Enqueue script function.
	 *
	 * @param string      $related_file_path = Full URL of the stylesheet, or path of the stylesheet relative to the WordPress root directory. Default empty.
	 * @param array       $dependencies =  Optional. An array of registered script handles this script depends on. Default empty array.
	 * @param bool|string $handle =  Optional. Name of the stylesheet. Should be unique.
	 */
	public function enqueue_script( $related_file_path, $dependencies = array(), $handle = false ) {
		wp_enqueue_script(
			$handle ? '' : str_replace( array( '/', '.' ), '-', $related_file_path ),
			plugins_url( $related_file_path, JWES_PLUGIN_FILE ),
			$dependencies,
			filemtime( plugin_dir_path( JWES_PLUGIN_FILE ) . $related_file_path ),
			true
		);
	}
}
