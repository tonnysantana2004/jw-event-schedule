<?php
/**
 * Website Footer.
 *
 * @package JW Event Schedule
 */

$jwes_footer_text = __( 'Jack Westin Events © 2026. All Rights Reserved.', 'jw-event-schedule' );

?>

<footer>
	<section class="jwes-container">
		<p class="jwes-text jwes-text-2"><?php echo esc_html( $jwes_footer_text ); ?></p>
	</section>
</footer>


<?php wp_footer(); ?>

</body>
</html>