<?php
/**
 * Image banner (full-bleed image with large heading).
 *
 * @package Escargot
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$image       = isset( $args['image'] ) ? $args['image'] : '';
$heading     = escargot_lines( escargot_option( 'banner_heading', "More\nThan\nStay" ) );
$description = escargot_option( 'banner_description', 'Every detail is designed to make you feel at home.' );
?>
<section class="image-banner">
	<div class="image-banner-bg">
		<?php if ( $image ) : ?>
			<img src="<?php echo esc_url( $image ); ?>" alt="">
		<?php endif; ?>
	</div>

	<div class="container">
		<div data-copy data-copy-wrapper data-type="lines">
			<?php foreach ( $heading as $word ) : ?>
				<h4><?php echo esc_html( $word ); ?></h4>
			<?php endforeach; ?>
		</div>

		<div class="image-banner-footer">
			<p data-copy data-type="lines" data-trigger=".image-banner" data-start="top 50%" data-delay="0.5"><?php echo esc_html( $description ); ?></p>
		</div>
	</div>
</section>
