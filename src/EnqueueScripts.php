<?php
/**
 * Enqueue Script Class
 *
 * @package JW Event Schedule
 */

namespace JWES;

class EnqueueScripts {

	public function init() {
		$this->frontend();

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
			),
		);
		// Gutenberg Blocks
		add_action(
			'init',
			function () {
				register_block_type( JWES_PLUGIN_DIR . '/build/gutenberg/blocks/listing-grid' );
			}
		);
	}

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

	public function enqueue_style( $related_file_path, $handle = false ) {
		wp_enqueue_style(
			$handle ? '' : str_replace( array( '/', '.' ), '-', $related_file_path ),
			plugins_url( $related_file_path, JWES_PLUGIN_FILE ),
			array(),
			filemtime( plugin_dir_path( JWES_PLUGIN_FILE ) . $related_file_path ),
			false
		);
	}


	public function enqueue_script( $related_file_path, $dependencies = '', $handle = false ) {
		wp_enqueue_script(
			$handle ? '' : str_replace( array( '/', '.' ), '-', $related_file_path ),
			plugins_url( $related_file_path, JWES_PLUGIN_FILE ),
			$dependencies,
			filemtime( plugin_dir_path( JWES_PLUGIN_FILE ) . $related_file_path ),
			true
		);
	}
}
