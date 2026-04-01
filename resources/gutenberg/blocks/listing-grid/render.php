<?php
/**
 * Render listing block template
 *
 * @package JW Event Schedule
 */

use JWES\PostType;

// TODO: abstract this file to a template
// that can be used in more than one place.

$jwes_args = array(
	'post_type'      => 'event',
	'posts_per_page' => 4,
	'post_status'    => 'publish',
);

$jwes_event_query = new WP_Query( $jwes_args );

?>
<p <?php echo esc_attr( get_block_wrapper_attributes() ); ?>>

<div class="jwes-listing-grid">

	<?php
	while ( $jwes_event_query->have_posts() ) :
		$jwes_event_query->the_post();
		?>
	
		<a class="jwes-listing-item" href="<?php echo esc_url( get_the_permalink() ); ?>">

			<?php

			if ( get_the_post_thumbnail_url() ) {
				the_post_thumbnail();
			} else {
				?>

				<img src="<?php echo esc_url( plugins_url( 'build/images/jwes-thumbnail.webp', JWES_PLUGIN_FILE ) ); ?>">

			<?php } ?>

			<h3 class="jwes-title jwes-title-4 jwes-line-limit-1">
				<?php echo esc_html( get_the_title() ); ?>
			</h3>

			<div class="jwes-text jwes-text-3 jwes-line-limit-3 ">
				<?php

				$jwes_excerpt = get_the_excerpt() ?? __( "We're now running a handful of different meetup series in spaces around Toronto, from Etobicoke to Scarborough.", 'jw-event-schedule' );

				echo esc_html( $jwes_excerpt );

				?>

			</div>

			<div class="jwes-info-box-description jwes-text jwes-text-4">
				<span>
					<?php echo esc_html( PostType::get_event_date( get_the_ID(), true, false ) ); ?>
				</span>
				<span>
					<?php echo esc_html( PostType::get_event_location( get_the_ID() ) ); ?>
				</span>
			</div>


		</a>

		<?php
	endwhile;

	if ( ! $jwes_event_query->have_posts() ) :
		?>
		<p class="jwes-text jwes-text-2"><?php echo esc_html__( 'Nothing was found.', 'jw-event-schedule' ); ?></p>
	<?php endif; ?>


</div>

</p>
