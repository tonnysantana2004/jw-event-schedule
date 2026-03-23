<?php

use \JWES\PostType;

wp_head();

?>

<?php include('header.php'); ?>

<main>

	<section class="jwes-container">

		<form method="get" class="jwes-form">
			<div class="jwes-field-control jwes-text jwes-text-3">
				<label for="search-by-title">Search by title</label>
				<input type="text" id="search-by-title" name="s" placeholder="Start typing..." value="<?= isset($_GET['s']) ? $_GET['s'] : '' ?>">
			</div>

			<div class="jwes-field-control jwes-text jwes-text-3">
				<label for="event-type">Event Type</label>
				<select name="event_type" id="event-type">

					<option value="">Select an option</option>

					<?php

					// Optmizing the term query: get only the needed fields
					$terms = get_terms([
						'taxonomy' => 'event_type',
						'hide_empty' => false,
						'fields' => 'ids'
					]);

					$terms = array_map(fn($id) => [
						'id' => $id,
						'slug' => get_term_field('slug', $id),
						'name' => get_term_field('name', $id)
					], $terms);

					foreach ($terms as $term) : ?>

						<option
							value="<?= $term['slug'] ?>"

							<?php
							$selected = false;

							if (isset($_GET['event_type']) && $_GET['event_type'] === $term['slug']) {
								$selected = true;
							}

							if (is_tax('event_type', $term['name'])) {
								$selected = true;
							}

							echo $selected ? 'selected' : '';
							?>>
							<?= $term['name'] ?>
						</option>

					<?php endforeach; ?>
				</select>
			</div>

			<div class="jwes-field-control jwes-text jwes-text-3" style="flex-grow:1">
				<label for="date-range">Date Range</label>
				<input type="text" id="date-range" name="date_range" value="<?= isset($_GET['date_range']) ? $_GET['date_range'] : '' ?>" placeholder="Select a date">
			</div>

			<a href="/events">
				<button type="button" class="jwes-btn jwes-btn-danger">Reset</button>
			</a>
			<button type="submit" class="jwes-btn jwes-btn-primary">Filter</button>

		</form>
	</section>

	<hr>

	<section class="jwes-container">
		<div class="jwes-listing-grid">

			<?php while (have_posts()) : the_post() ?>
				<a class="jwes-listing-item" href="<?= the_permalink() ?>">

					<?php

					if (get_the_post_thumbnail_url()) {
						the_post_thumbnail();
					} else { ?>

						<img src="<?= plugins_url('assets/images/jwes-thumbnail.webp', JWES_PLUGIN_FILE); ?> ?>">

					<?php }; ?>

					<h3 class="jwes-title jwes-title-4 jwes-line-limit-1">
						<?php echo the_title(); ?>
					</h3>

					<div class="jwes-text jwes-text-3 jwes-line-limit-3 ">
						<?php

						$excerpt =  get_the_excerpt();

						echo $excerpt ? $excerpt : esc_html("We're now running a handful of different meetup series in spaces around Toronto, from Etobicoke to Scarborough.");

						?>

					</div>

					<div class="jwes-info-box-description jwes-text jwes-text-3">
						<span>
							<?= PostType::get_the_event_date_formated(get_the_ID(), true, false); ?>

						</span>
						<span>
							<?= PostType::get_the_event_location_formated(get_the_ID()); ?>
						</span>
					</div>


				</a>

			<?php endwhile;

			if (!have_posts()) : ?>
				<p class="jwes-text jwes-text-2">Nothing was found.</p>
			<?php endif; ?>


		</div>

		<?php the_posts_pagination(); ?>
		
	</section>

</main>

<?php include('footer.php'); ?>

<?php wp_footer(); ?>