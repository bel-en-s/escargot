<?php
/**
 * Template Name: Book
 *
 * @package Escargot
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$pid       = get_the_ID();
$heading   = escargot_meta( $pid, 'escargot_book_heading', 'Set the Evening in Motion' );
$paragraph = escargot_meta( $pid, 'escargot_book_paragraph', 'Reserve your place for an evening of attentive service, thoughtful plates, and an atmosphere made to linger in.' );
$button    = escargot_meta( $pid, 'escargot_book_button', 'Reserve Your Evening' );

$info_cards = get_posts(
	array(
		'post_type'      => 'escargot_info_card',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order date',
		'order'          => 'ASC',
	)
);
?>

<section class="reservation-hero">
	<div class="container">
		<h2 data-copy data-type="words" data-animate="false" data-delay="0.85"><?php echo esc_html( $heading ); ?></h2>

		<p class="lg" data-copy data-type="lines" data-animate="false" data-delay="1.35"><?php echo esc_html( $paragraph ); ?></p>

		<div class="reservation-hero-button">
			<?php escargot_button( '#', $button ); ?>
		</div>

		<p class="mono" data-copy data-type="lines" data-animate="false" data-delay="1.65">( <?php echo esc_html( escargot_option( 'contact_phone', '+39 055 398 2417' ) ); ?> )</p>
	</div>
</section>

<?php if ( ! empty( $info_cards ) ) : ?>
	<section class="reservation-info">
		<div class="container">
			<?php foreach ( $info_cards as $card ) : ?>
				<?php
				$icon        = escargot_meta( $card->ID, 'escargot_info_icon', 'bolt' );
				$description = escargot_meta( $card->ID, 'escargot_info_description' );
				$items       = escargot_lines( escargot_meta( $card->ID, 'escargot_info_items' ) );
				?>
				<div class="info-panel">
					<div class="info-panel-icon">
						<?php escargot_icon( $icon, 96 ); ?>
					</div>
					<div class="info-card">
						<span class="info-card-icon"><?php escargot_icon( $icon, 40 ); ?></span>
						<h5><?php echo esc_html( get_the_title( $card ) ); ?></h5>
						<p><?php echo esc_html( $description ); ?></p>
						<div class="info-card-items">
							<?php foreach ( $items as $item ) : ?>
								<p class="mono"><?php echo esc_html( $item ); ?></p>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</section>
<?php endif; ?>

<?php get_template_part( 'template-parts/cta' ); ?>

<?php get_footer(); ?>
