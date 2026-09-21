<?php
/**
 * Marquee section.
 *
 * @package Escargot
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_id = get_the_ID();
$heading = escargot_meta( $post_id, 'escargot_marquee_heading', 'Since 1984' );
$sub     = escargot_meta( $post_id, 'escargot_marquee_sub', 'Art of Stillness' );
$pill    = escargot_meta( $post_id, 'escargot_marquee_pill', 'Enduring Taste' );
?>
<section class="marquee">
	<div class="container">
		<div class="marquee-content">
			<h1><?php echo esc_html( $heading ); ?></h1>
			<h3><?php echo esc_html( $sub ); ?></h3>
		</div>

		<div class="marquee-pill">
			<div class="marquee-track">
				<?php for ( $i = 0; $i < 12; $i++ ) : ?>
					<span class="marquee-item"><?php echo esc_html( $pill ); ?></span>
				<?php endfor; ?>
			</div>
		</div>
	</div>
</section>
