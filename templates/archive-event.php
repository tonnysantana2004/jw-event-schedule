<?php

use \JWES\PostType;

wp_head();

$terms = get_terms([
	'taxonomy' => 'event_type',
	'hide_empty' => 'false'
]);

?>

<?php include('header.php'); ?>

<main>

	<section class="container">
		<form method="get">
			<input type="text" name="search" placeholder="Start typing...">

			<select name="event_type" id="event_type">
				<option value="">Select an option</option>

				<?php

				foreach ($terms as $term) : ?>

					<option
						value="<?= $term->slug ?>"
						<?= ($_GET['event_type'] === $term->slug) ? 'selected' : '' ?>>
						<?= $term->name ?>
					</option>

				<?php endforeach; ?>
			</select>

			<input type="submit" value="Filter">

		</form>
	</section>

	<section class="container">
		<div class="listing-grid">

			<?php while (have_posts()) : the_post() ?>
				<a class="listing-item" href="<?= the_permalink() ?>">

					<?php echo the_post_thumbnail(); ?>
					<h3 class="title title-4">
						<?php echo the_title(); ?>
					</h3>
					<div class="text text-3 line-limit-3 ">
						<?php echo the_excerpt_embed() ?>
					</div>

					<?php if (get_post_meta(get_the_ID(), 'event_date', true)) : ?>

						<div class="info-box">

							<div class="info-box-header">

								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="info-box-icon">
									<path fill-rule="evenodd" d="M6.75 2.25A.75.75 0 0 1 7.5 3v1.5h9V3A.75.75 0 0 1 18 3v1.5h.75a3 3 0 0 1 3 3v11.25a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3V7.5a3 3 0 0 1 3-3H6V3a.75.75 0 0 1 .75-.75Zm13.5 9a1.5 1.5 0 0 0-1.5-1.5H5.25a1.5 1.5 0 0 0-1.5 1.5v7.5a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5v-7.5Z" clip-rule="evenodd" />
								</svg>

								<h3 class="title title-5">Event Date</h3>

							</div>

							<div class="info-box-description text text-3">
								<span>
									<?= PostType::get_the_event_date_formated(get_the_ID(), true, false); ?>
								</span>
								<span><?= PostType::get_the_event_date_formated(get_the_ID(), false, true); ?></span>
							</div>

						</div>

					<?php endif; ?>

				</a>

			<?php endwhile; ?>

		</div>
	</section>

</main>

<?php include('footer.php'); ?>

<?php wp_footer(); ?>