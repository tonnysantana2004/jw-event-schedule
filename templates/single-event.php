<?php

use \JWES\PostType;

wp_head();

?>

	<main>

		<section class="container event-header">
			<?php the_post_thumbnail() ?>

			<div class="event-intro">

				<h1 class="title title-1"><?php the_title() ?></h1>
				<p class="text text-1">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque fermentum at lorem nec pretium. Vestibulum quam leo, fermentum et nisl ut, porta aliquam quam.</p>

				<div class="info-boxes">


					<?php if (get_post_meta(get_the_ID(), 'event_date', true)) : ?>

						<div class="info-box">

							<div class="info-box-header">

								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="info-box-icon">
									<path fill-rule="evenodd" d="M6.75 2.25A.75.75 0 0 1 7.5 3v1.5h9V3A.75.75 0 0 1 18 3v1.5h.75a3 3 0 0 1 3 3v11.25a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3V7.5a3 3 0 0 1 3-3H6V3a.75.75 0 0 1 .75-.75Zm13.5 9a1.5 1.5 0 0 0-1.5-1.5H5.25a1.5 1.5 0 0 0-1.5 1.5v7.5a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5v-7.5Z" clip-rule="evenodd" />
								</svg>

								<h3 class="title title-3">Event Date</h3>

							</div>

							<div class="info-box-description text text-2">
								<span>
									<?= PostType::get_the_event_date_formated(get_the_ID(), true, false); ?>
								</span>
								<span><?= PostType::get_the_event_date_formated(get_the_ID(), false, true); ?></span>
							</div>

						</div>

					<?php endif; ?>

					<?php if (get_post_meta(get_the_ID(), 'event_location', true)) : ?>

						<div class="info-box">

							<div class="info-box-header">

								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="info-box-icon">
									<path fill-rule="evenodd" d="m11.54 22.351.07.04.028.016a.76.76 0 0 0 .723 0l.028-.015.071-.041a16.975 16.975 0 0 0 1.144-.742 19.58 19.58 0 0 0 2.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 0 0-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 0 0 2.682 2.282 16.975 16.975 0 0 0 1.145.742ZM12 13.5a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" clip-rule="evenodd" />
								</svg>


								<h3 class="title title-3">Event location</h3>

							</div>

							<div class="info-box-description text text-2">
								<span>
									<?= PostType::get_the_event_location_formated(get_the_ID()); ?>
								</span>
							</div>

						</div>

					<?php endif; ?>

				</div>

			</div>
		</section>

		<hr>

		<section class="container text text-1">
			<div class="content">
				<?php the_content() ?>
			</div>
		</section>

		<hr>

		<section class="container">
			<h2 class="title title-2">About Us</h2>
			<p class="text text-2">The team at Jack Westin is dedicated to a single goal: Giving you the highest quality learning resources, bar none. We understand that you can’t crush the MCAT® without the perfect blend of critical thinking and fundamental science knowledge. To this end, we are dedicated to providing you with only the cutting edge of comprehensive tools, courses, and practice materials. Our MCAT® science and CARS courses, taught by the world’s best and most engaging MCAT® instructors, are designed to do more than just teach you the MCAT®—our aim is to supercharge your studying and encourage lifelong learning.</p>
		</section>

	</main>

	<footer>
		<section class="container">
			<p class="text text-2">Jack Westin Events © 2026. All Rights Reserved.</p>
		</section>
	</footer>

<?php wp_footer(); ?>