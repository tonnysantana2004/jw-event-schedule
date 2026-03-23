<?php

use \JWES\PostType;

$args = array(
    'post_type'      => 'event',
    'posts_per_page' => 4, 
    'post_status'    => 'publish',
);

$event_query = new WP_Query($args);

?>
<p <?php echo get_block_wrapper_attributes(); ?>>

<div class="jwes-listing-grid">

	<?php while ($event_query->have_posts()) : $event_query->the_post() ?>
		<a class="jwes-listing-item" href="<?= the_permalink() ?>">

			<?php

			if (get_the_post_thumbnail_url()) {
				the_post_thumbnail();
			} else { ?>

				<img src="<?= plugins_url('assets/images/jwes-thumbnail.webp', JWES_PLUGIN_FILE); ?>">

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

			<div class="jwes-info-box-description jwes-text jwes-text-4">
				<span>
					<?= PostType::get_the_event_date_formated(get_the_ID(), true, false); ?>
				</span>
				<span>
					<?= PostType::get_the_event_location_formated(get_the_ID()); ?>
				</span>
			</div>


		</a>

	<?php endwhile;

	if (!$event_query->have_posts()) : ?>
		<p class="jwes-text jwes-text-2"><?= esc_html__('Nothing was found.', 'jw-event-schedule') ?></p>
	<?php endif; ?>


</div>

</p>