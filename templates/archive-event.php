<?php
/**
 * Event Archive Template
 *
 * @package JW Event Schedule
 */

use JWES\PostType;

wp_head();

?>

<?php require 'header.php'; ?>

<main>

	<section class="jwes-container">

		<form method="get" class="jwes-form">
			
			<?php wp_nonce_field( 'search_action', 'search_nonce' ); ?>

			<div class="jwes-field-control jwes-text jwes-text-3">

				<label for="search-by-title"><?php echo esc_html__( 'Search by title', 'jw-event-schedule' ); ?></label>

				<?php

				// Since we are not updating but reading, we dont need nonce verification.
				// phpcs:disable WordPress.Security.NonceVerification.Recommended
				$jwes_search_value = isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '';
				$jwes_date_range   = isset( $_GET['date_range'] ) ? sanitize_text_field( wp_unslash( $_GET['date_range'] ) ) : '';
				$jwes_event_type   = isset( $_GET['event_type'] ) ? sanitize_text_field( wp_unslash( $_GET['event_type'] ) ) : '';
				// phpcs:enable WordPress.Security.NonceVerification.Recommended

				?>

				<input type="text" id="search-by-title" name="s" placeholder="<?php echo esc_html__( 'Start typing...', 'jw-event-schedule' ); ?>" value="<?php echo esc_attr( $jwes_search_value ); ?>">

			</div>

			<div class="jwes-field-control jwes-text jwes-text-3">
				
				<label for="event-type"><?php echo esc_html__( 'Event Type', 'jw-event-schedule' ); ?></label>
				<select name="event_type" id="event-type">

					<option value=""><?php echo esc_html__( 'Select an option', 'jw-event-schedule' ); ?></option>

					<?php

					// TODO: abstract this file to a template that can be used in more than one place.
					// Optmizing the term query.
					$jwes_terms = get_terms(
						array(
							'taxonomy'   => 'event_type',
							'hide_empty' => false,
							'fields'     => 'ids',
						)
					);

					$jwes_terms = array_map(
						fn( $id ) => array(
							'id'   => $id,
							'slug' => get_term_field( 'slug', $id ),
							'name' => get_term_field( 'name', $id ),
						),
						$jwes_terms
					);

					foreach ( $jwes_terms as $jwes_term ) :
						?>

						<option
							value="<?php echo esc_attr( $jwes_term['slug'] ); ?>"

								<?php
								$jwes_term_selected = false;

								if ( $jwes_event_type === $jwes_term['slug'] ) {
									$jwes_term_selected = true;
								}

								if ( is_tax( 'event_type', $jwes_term['slug'] ) ) {
									$jwes_term_selected = true;
								}

								echo $jwes_term_selected ? esc_attr( 'selected' ) : '';
								?>
							>

							<?php echo esc_html( $jwes_term['name'] ); ?>

						</option>

					<?php endforeach; ?>
				</select>
			</div>

			<div class="jwes-field-control jwes-text jwes-text-3" style="flex-grow:1">
				<label for="date-range"><?php echo esc_html__( 'Date Range', 'jw-event-schedule' ); ?></label>
				<input type="text" id="date-range" name="date_range" value="<?php echo esc_attr( $jwes_date_range ); ?>" placeholder="<?php echo esc_html__( 'Select a date', 'jw-event-schedule' ); ?>">
			</div>

			<a href="/events">
				<button type="button" class="jwes-btn jwes-btn-danger"><?php echo esc_html__( 'Reset', 'jw-event-schedule' ); ?></button>
			</a>
			<button type="submit" class="jwes-btn jwes-btn-primary"><?php echo esc_html__( 'Filter', 'jw-event-schedule' ); ?></button>

		</form>
	</section>

	<hr>

	<section class="jwes-container">
		<div class="jwes-listing-grid">

			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<a class="jwes-listing-item" href="<?php echo esc_url( get_the_permalink() ); ?>">

					<?php

					// Post Thumbnail.
					if ( get_the_post_thumbnail_url() ) {
						$jwes_img_tag = get_the_post_thumbnail();
					} else {
						$jwes_post_thumbnail_url = plugins_url( 'build/images/jwes-thumbnail.webp', JWES_PLUGIN_FILE );
						$jwes_img_tag            = '<img src=' . esc_url( $jwes_post_thumbnail_url ) . '>';
					}

					echo wp_kses_post( $jwes_img_tag );

					?>

					<h3 class="jwes-title jwes-title-4 jwes-line-limit-1">
						<?php echo esc_html( get_the_title() ); ?>
					</h3>

					<div class="jwes-text jwes-text-3 jwes-line-limit-3 ">
						<?php

						$jwes_excerpt = get_the_excerpt();

						$jwes_excerpt = $jwes_excerpt ? $jwes_excerpt : __( "We're now running a handful of different meetup series in spaces around Toronto, from Etobicoke to Scarborough.", 'jw-event-schedule' );

						echo wp_kses_post( $jwes_excerpt );

						?>

					</div>

					<div class="jwes-info-box-description jwes-text jwes-text-3">
						<span>
							<?php echo esc_html( PostType::get_event_date( get_the_ID(), true, false ) ); ?>

						</span>
						<span>
							<?php echo esc_html( PostType::get_event_date( get_the_ID(), false, true ) ); ?>
						</span>
					</div>


				</a>

				<?php
			endwhile;

			if ( ! have_posts() ) :
				?>
				<p class="jwes-text jwes-text-2"><?php echo esc_html__( 'Nothing was found.', 'jw-event-schedule' ); ?></p>
			<?php endif; ?>


		</div>

		<?php the_posts_pagination(); ?>
		
	</section>

</main>

<?php require 'footer.php'; ?>

<?php wp_footer(); ?>
