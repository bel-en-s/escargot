<?php
/**
 * Front page (landing).
 *
 * @package Escargot
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$pid = get_queried_object_id();

$title_1 = escargot_meta( $pid, 'escargot_landing_title_1', 'Escargot' );
$title_2 = escargot_meta( $pid, 'escargot_landing_title_2', 'Adventures' );

$landing_photo = escargot_image( escargot_meta( $pid, 'escargot_landing_photo' ), 'full', 'assets/img/landing.jpg' );

$banner_word_1 = escargot_meta( $pid, 'escargot_banner_word_1', 'More' );
$banner_word_2 = escargot_meta( $pid, 'escargot_banner_word_2', 'Stay' );
$banner_logo   = escargot_image( escargot_meta( $pid, 'escargot_banner_logo' ), 'full', 'assets/img/logo.png' );
$banner_image  = escargot_image( escargot_meta( $pid, 'escargot_banner_image' ), 'full', 'assets/img/canoa.jpg' );

$about_text = escargot_meta( $pid, 'escargot_about_text', 'Escargot is a travel agency eco-friendly, focusing on cycling and exotic tourism.' );
$cta_text   = escargot_meta( $pid, 'escargot_cta_text', 'Explore trips' );
$cta_link   = escargot_meta( $pid, 'escargot_cta_link', '#' );
?>

<section class="landing">
	<h1 class="landing-title" id="landing-title"><?php echo esc_html( $title_1 ); ?> <span class="landing-title-italic"><?php echo esc_html( $title_2 ); ?></span></h1>

	<div class="landing-photo">
		<img src="<?php echo esc_url( $landing_photo ); ?>" alt="">
	</div>
</section>

<section class="about-agency">
	<div class="about-agency-text">
		<p><?php echo esc_html( $about_text ); ?></p>
	</div>
	<div class="about-agency-cta">
		<a href="<?php echo esc_url( $cta_link ); ?>"><?php echo esc_html( $cta_text ); ?></a>
	</div>
</section>

<section class="image-banner">
	<div class="image-banner-bg">
		<img src="<?php echo esc_url( $banner_image ); ?>" alt="">
	</div>

	<div class="image-banner-inner">
		<div class="image-banner-heading">
			<h4><?php echo esc_html( $banner_word_1 ); ?></h4>
			<img class="image-banner-logo" src="<?php echo esc_url( $banner_logo ); ?>" alt="">
			<h4><?php echo esc_html( $banner_word_2 ); ?></h4>
		</div>
	</div>
</section>

<?php get_footer(); ?>
