<?php
/**
 * Template Name: Essence
 *
 * @package Escargot
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$pid        = get_the_ID();
$hero_img   = escargot_image( escargot_meta( $pid, 'escargot_essence_hero_image' ), 'full' );
$banner_img = escargot_image( escargot_meta( $pid, 'escargot_essence_banner_image' ), 'full' );
?>

<section class="about-hero">
	<div class="about-hero-pin">
		<div class="hero-heading">
			<div data-copy data-copy-wrapper data-type="lines" data-animate="false" data-delay="0.85">
				<h1 class="heading-line-1"><?php echo esc_html( escargot_meta( $pid, 'escargot_essence_line_1', 'Salle' ) ); ?></h1>
				<h1 class="heading-line-2"><?php echo esc_html( escargot_meta( $pid, 'escargot_essence_line_2', 'Blanche' ) ); ?></h1>
				<h1 class="heading-line-3"><?php echo esc_html( escargot_meta( $pid, 'escargot_essence_line_3', 'Story' ) ); ?></h1>
			</div>
		</div>

		<div class="hero-image">
			<?php if ( $hero_img ) : ?>
				<img src="<?php echo esc_url( $hero_img ); ?>" alt="">
			<?php endif; ?>
		</div>
	</div>
</section>

<section class="about-info">
	<div class="container">
		<div data-copy data-copy-wrapper data-type="lines">
			<p class="mono"><?php echo esc_html( escargot_meta( $pid, 'escargot_essence_kicker', 'Defined by Balance and Restraint' ) ); ?></p>
			<h3><?php echo esc_html( escargot_meta( $pid, 'escargot_essence_paragraph_1', 'Salle Blanche is an exercise in quiet composition, where space, light, and material come together with deliberate restraint.' ) ); ?></h3>
			<h3><?php echo esc_html( escargot_meta( $pid, 'escargot_essence_paragraph_2', 'In the same spirit, the menu follows a measured approach, guided by balance, clarity, and precision.' ) ); ?></h3>
		</div>
	</div>
</section>

<?php get_template_part( 'template-parts/image-banner', null, array( 'image' => $banner_img ) ); ?>
<?php get_template_part( 'template-parts/marquee' ); ?>
<?php get_template_part( 'template-parts/sticky-cards' ); ?>
<?php get_template_part( 'template-parts/chefs' ); ?>
<?php get_template_part( 'template-parts/cta' ); ?>

<?php get_footer(); ?>
