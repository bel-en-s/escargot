<?php
/**
 * Testimonials carousel.
 *
 * @package Escargot
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$testimonials = get_posts(
	array(
		'post_type'      => 'escargot_testimonial',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order date',
		'order'          => 'ASC',
	)
);

if ( empty( $testimonials ) ) {
	return;
}
?>
<section class="testimonials">
	<div class="container">
		<div class="testimonials-header">
			<h3 data-copy data-type="lines"><?php echo esc_html( escargot_option( 'testimonials_heading', 'What our clients say' ) ); ?></h3>

			<div class="testimonials-nav">
				<div class="testimonials-nav-button-wrapper">
					<button class="testimonials-nav-button" data-dir="prev" type="button"><?php escargot_icon( 'arrow-left' ); ?></button>
				</div>
				<div class="testimonials-nav-button-wrapper">
					<button class="testimonials-nav-button" data-dir="next" type="button"><?php escargot_icon( 'arrow-right' ); ?></button>
				</div>
			</div>
		</div>
	</div>

	<div class="testimonials-carousel">
		<div class="testimonials-track">
			<?php foreach ( $testimonials as $testimonial ) : ?>
				<?php
				$review = escargot_meta( $testimonial->ID, 'escargot_testimonial_review' );
				$rating = escargot_meta( $testimonial->ID, 'escargot_testimonial_rating', '5' );
				$photo  = get_the_post_thumbnail_url( $testimonial->ID, 'thumbnail' );
				?>
				<div class="testimonial-card">
					<div class="testimonial-content">
						<span class="testimonial-quote-mark">&ldquo;</span>
						<p><?php echo esc_html( $review ); ?></p>
					</div>

					<div class="testimonial-author">
						<div class="testimonial-author-img">
							<?php if ( $photo ) : ?>
								<img src="<?php echo esc_url( $photo ); ?>" alt="<?php echo esc_attr( get_the_title( $testimonial ) ); ?>">
							<?php endif; ?>
						</div>
						<div class="testimonial-author-info">
							<h6 class="testimonial-author-name"><?php echo esc_html( get_the_title( $testimonial ) ); ?></h6>
							<?php escargot_stars( $rating ); ?>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
