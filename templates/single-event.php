<?php

use \JWES\PostType;

include('header.php');

?>

<main>

	<section class="jwes-container jwes-event-header">

		<?php

		if (get_the_post_thumbnail_url()) {
			the_post_thumbnail();
		} else { ?>

			<img src="<?= plugins_url('build/images/jwes-thumbnail.webp', JWES_PLUGIN_FILE); ?> ?>">

		<?php }; ?>

		<div class="jwes-event-intro">

			<h1 class="jwes-title jwes-title-1"><?php the_title() ?></h1>
			<p class="jwes-text jwes-text-1"><?php $excerpt =  get_the_excerpt();
												echo $excerpt ? $excerpt : esc_html__("We're now running a handful of different meetup series in spaces around Toronto, from Etobicoke to Scarborough.", 'jw-event-schedule'); ?></p>

			<div class="jwes-info-boxes">

				<div class="jwes-info-box">

					<div class="jwes-info-box-header">

						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="jwes-info-box-icon">
							<path fill-rule="evenodd" d="M6.75 2.25A.75.75 0 0 1 7.5 3v1.5h9V3A.75.75 0 0 1 18 3v1.5h.75a3 3 0 0 1 3 3v11.25a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3V7.5a3 3 0 0 1 3-3H6V3a.75.75 0 0 1 .75-.75Zm13.5 9a1.5 1.5 0 0 0-1.5-1.5H5.25a1.5 1.5 0 0 0-1.5 1.5v7.5a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5v-7.5Z" clip-rule="evenodd" />
						</svg>

						<h3 class="jwes-title jwes-title-3"><?= esc_html__('Event Date', 'jw-event-schedule') ?></h3>

					</div>

					<div class="jwes-info-box-description jwes-text jwes-text-2">
						<span>
							<?= PostType::get_the_event_date_formated(get_the_ID(), true, false); ?>
						</span>
						<span><?= PostType::get_the_event_date_formated(get_the_ID(), false, true); ?></span>
					</div>

				</div>

				<div class="jwes-info-box">

					<div class="jwes-info-box-header">

						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="jwes-info-box-icon">
							<path fill-rule="evenodd" d="m11.54 22.351.07.04.028.016a.76.76 0 0 0 .723 0l.028-.015.071-.041a16.975 16.975 0 0 0 1.144-.742 19.58 19.58 0 0 0 2.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 0 0-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 0 0 2.682 2.282 16.975 16.975 0 0 0 1.145.742ZM12 13.5a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" clip-rule="evenodd" />
						</svg>


						<h3 class="jwes-title jwes-title-3"><?= esc_html__('The Location', 'jw-event-schedule') ?></h3>

					</div>

					<div class="jwes-info-box-description jwes-text jwes-text-2">
						<span>
							<?= PostType::get_the_event_location_formated(get_the_ID()); ?>
						</span>
					</div>

				</div>

			</div>

		</div>
	</section>

	<hr>

	<section class="jwes-container jwes-text jwes-text-1">
		<div class="jwes-content">
			<?php

			$content =  get_the_content();

			echo $content ? $content : esc_html__("We're now running a handful of different meetup series in spaces around Toronto, from Etobicoke to Scarborough.", 'jw-event-schedule');

			?>

		</div>

		<?php if (is_user_logged_in()) :

			$current_attendance_list = get_post_meta(get_the_ID(), 'attendance_list',true) ?: [];
			if (!in_array(get_current_user_id(), $current_attendance_list)) {
		?>

				<form action="/wp-json/jwes/v1/attendance"  id="attendance-form">
					<input type="hidden" name="post_id" value="<?= get_the_ID() ?>">
					<input type="hidden" name="user_id" value="<?= get_current_user_id() ?>">
					<button class="jwes-btn jwes-btn-primary" type="submit" style="width:fit-content" id="confirm-attendance"><?= esc_html__('Confirm Attendance', 'jw-event-schedule') ?></button>
				</form>

			<?php
			} else {
			?>
				<form action="/wp-json/jwes/v1/attendance" id="cancel-attendance-form">
					<input type="hidden" name="post_id" value="<?= get_the_ID() ?>">
					<input type="hidden" name="user_id" value="<?= get_current_user_id() ?>">
					<button class="jwes-btn jwes-btn-danger" type="submit" style="width:fit-content" id="cancel-attendance"><?= esc_html__('Cancel Attendance', 'jw-event-schedule') ?></button>
				</form>


			<?php } ?>

		<?php else :  ?>
			<a class="jwes-btn jwes-btn-primary" href="/wp-login.php?redirect_to=/events/wordpress-convention/#confirm-attendance" style="width:fit-content"><?= esc_html__('Login to confirm attendance', 'jw-event-schedule') ?></a>
		<?php endif; ?>

	</section>

	<hr>

	<section class="jwes-container">
		<h2 class="jwes-title jwes-title-2"><?= esc_html__('About Us', 'jw-event-schedule') ?></h2>
		<p class="jwes-text jwes-text-2"><?= esc_html__('The team at Jack Westin is dedicated to a single goal: Giving you the highest quality learning resources, bar none. We understand that you can’t crush the MCAT® without the perfect blend of critical thinking and fundamental science knowledge. To this end, we are dedicated to providing you with only the cutting edge of comprehensive tools, courses, and practice materials. Our MCAT® science and CARS courses, taught by the world’s best and most engaging MCAT® instructors, are designed to do more than just teach you the MCAT®—our aim is to supercharge your studying and encourage lifelong learning.', 'jw-event-schedule') ?></p>
	</section>

</main>

<?php include('footer.php'); ?>