<?php
/**
 * Sticky cards (About page).
 *
 * @package Escargot
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$stories = get_posts(
	array(
		'post_type'      => 'escargot_story',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order date',
		'order'          => 'ASC',
	)
);

if ( empty( $stories ) ) {
	return;
}
?>
<div class="sticky-cards">
	<?php foreach ( $stories as $story ) : ?>
		<?php $img = get_the_post_thumbnail_url( $story->ID, 'large' ); ?>
		<div class="sticky-card">
			<div class="sticky-card-img">
				<?php if ( $img ) : ?>
					<img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( get_the_title( $story ) ); ?>">
				<?php endif; ?>
			</div>
			<h3><?php echo esc_html( get_the_title( $story ) ); ?></h3>
			<p><?php echo esc_html( wp_strip_all_tags( get_the_content( null, false, $story->ID ) ) ); ?></p>
		</div>
	<?php endforeach; ?>
</div>
