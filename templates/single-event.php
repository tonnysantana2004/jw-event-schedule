<?php

use \JWES\PostType;

wp_head();

?>

<div class="content">

	<main>

		<section class="container event-header">
			<?php the_post_thumbnail() ?>

			<div class="event-intro">

				<h1 class="title title-1"><?php the_title() ?></h1>
				<p class="text text-1">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque fermentum at lorem nec pretium. Vestibulum quam leo, fermentum et nisl ut, porta aliquam quam.</p>

				<div class="info-boxes">

					<div class="info-box">
						<div class="info-box-header">

							<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="info-box-icon">
								<path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
							</svg>

							<p>When will happen</p>

						</div>
						<p class="info-box-description">
							<?= PostType::get_the_event_date_formated(get_the_ID()); ?>
						</p>
					</div>

				</div>

			</div>
		</section>

		<hr>

		<section class="container text text-1">
			<?php the_content() ?>
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

</div>

<?php wp_footer(); ?>