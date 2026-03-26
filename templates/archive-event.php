<?php

use JWES\PostType;

wp_head();

?>

<?php require 'header.php'; ?>

<main>

	<section class="jwes-container">

		<!-- 4. Search and Filtering Functionality -->
		<form method="get" class="jwes-form">
			<div class="jwes-field-control jwes-text jwes-text-3">
				<label for="search-by-title"><?php echo esc_html__( 'Search by title', 'jw-event-schedule' ); ?></label>
				<input type="text" id="search-by-title" name="s" placeholder="<?php echo esc_html__( 'Start typing...', 'jw-event-schedule' ); ?>" value="<?php echo isset( $_GET['s'] ) ? $_GET['s'] : ''; ?>">
			</div>

			<div class="jwes-field-control jwes-text jwes-text-3">
				
				<label for="event-type"><?php echo esc_html__( 'Event Type', 'jw-event-schedule' ); ?></label>
				<select name="event_type" id="event-type">

					<option value=""><?php echo esc_html__( 'Select an option', 'jw-event-schedule' ); ?></option>

					<?php

					// Optmizing the term query: get only the needed fields
					$terms = get_terms(
						array(
							'taxonomy'   => 'event_type',
							'hide_empty' => false,
							'fields'     => 'ids',
						)
					);

					$terms = array_map(
						fn( $id ) => array(
							'id'   => $id,
							'slug' => get_term_field( 'slug', $id ),
							'name' => get_term_field( 'name', $id ),
						),
						$terms
					);

					foreach ( $terms as $term ) :
						?>

						<option
							value="<?php echo $term['slug']; ?>"

							<?php
							$selected = false;

							if ( isset( $_GET['event_type'] ) && $_GET['event_type'] === $term['slug'] ) {
								$selected = true;
							}

							if ( is_tax( 'event_type', $term['slug'] ) ) {
								$selected = true;
							}

							echo $selected ? 'selected' : '';
							?>
							>

							<?php echo esc_html__( $term['name'], 'jw-event-schedule' ); ?>

						</option>

					<?php endforeach; ?>
				</select>
			</div>

			<div class="jwes-field-control jwes-text jwes-text-3" style="flex-grow:1">
				<label for="date-range"><?php echo esc_html__( 'Date Range', 'jw-event-schedule' ); ?></label>
				<input type="text" id="date-range" name="date_range" value="<?php echo isset( $_GET['date_range'] ) ? $_GET['date_range'] : ''; ?>" placeholder="<?php echo esc_html__( 'Select a date', 'jw-event-schedule' ); ?>">
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
				<a class="jwes-listing-item" href="<?php echo the_permalink(); ?>">

					<?php

					if ( get_the_post_thumbnail_url() ) {
						the_post_thumbnail();
					} else {
						?>

						<img src="<?php echo plugins_url( 'build/images/jwes-thumbnail.webp', JWES_PLUGIN_FILE ); ?> ?>">

					<?php } ?>

					<h3 class="jwes-title jwes-title-4 jwes-line-limit-1">
						<?php echo the_title(); ?>
					</h3>

					<div class="jwes-text jwes-text-3 jwes-line-limit-3 ">
						<?php

						$excerpt = get_the_excerpt();

						echo $excerpt ? $excerpt : esc_html( "We're now running a handful of different meetup series in spaces around Toronto, from Etobicoke to Scarborough." );

						?>

					</div>

					<div class="jwes-info-box-description jwes-text jwes-text-3">
						<span>
							<?php echo PostType::get_event_date( get_the_ID(), true, false ); ?>

						</span>
						<span>
							<?php echo PostType::get_event_location( get_the_ID() ); ?>
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
