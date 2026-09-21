<?php
/**
 * Call to action section.
 *
 * @package Escargot
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$book_url  = escargot_get_template_page_url( 'template-book.php' );
$cta_image = (int) escargot_option( 'cta_image', 0 );
$cta_img   = $cta_image ? wp_get_attachment_image_url( $cta_image, 'full' ) : '';
?>
<section class="cta">
	<div class="container">
		<div class="cta-content">
			<h6 data-copy data-type="lines" data-trigger=".cta" data-start="top 80%"><?php echo esc_html( escargot_option( 'cta_kicker', 'A table awaits' ) ); ?></h6>

			<h5 data-copy data-type="lines" data-trigger=".cta" data-start="top 80%"><?php echo esc_html( escargot_option( 'cta_text', 'Settle into a space where the pace softens and each course arrives with quiet intention.' ) ); ?></h5>

			<div class="cta-details">
				<div class="cta-address">
					<div data-copy data-copy-wrapper data-type="lines" data-trigger=".cta" data-start="top 80%" data-delay="0.3">
						<p class="mono"><?php echo esc_html( escargot_option( 'address_line1', 'Piazza Santo Spirito 8' ) ); ?></p>
						<p class="mono"><?php echo esc_html( escargot_option( 'address_line2', 'Firenze, IT 50125' ) ); ?></p>
					</div>
				</div>
				<div class="cta-hours">
					<div data-copy data-copy-wrapper data-type="lines" data-trigger=".cta" data-start="top 80%" data-delay="0.3">
						<p class="mono"><?php echo esc_html( escargot_option( 'hours_week', 'LUN–VEN 19:00–23:00' ) ); ?></p>
						<p class="mono"><?php echo esc_html( escargot_option( 'hours_weekend', 'SAB–DOM 18:30–23:30' ) ); ?></p>
					</div>
				</div>
			</div>

			<div class="cta-circle-button">
				<a href="<?php echo esc_url( $book_url ); ?>" class="cta-button">
					<svg class="cta-button-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100">
						<path d="M50,10 A40,40 0 1,1 49.9999,10" stroke="currentColor" strokeWidth="0.75" fill="none" />
					</svg>
					<span data-copy data-type="lines" data-trigger=".cta" data-start="top 80%" data-delay="0.5"><?php echo esc_html( escargot_option( 'cta_button', 'Take a Table' ) ); ?></span>
				</a>
			</div>
		</div>

		<div class="cta-image">
			<?php if ( $cta_img ) : ?>
				<img src="<?php echo esc_url( $cta_img ); ?>" alt="">
			<?php endif; ?>
		</div>
	</div>
</section>
