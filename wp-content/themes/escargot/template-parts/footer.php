<?php
/**
 * Footer.
 *
 * @package Escargot
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$book_url = escargot_get_template_page_url( 'template-book.php' );

$postcards = array();
for ( $i = 1; $i <= 5; $i++ ) {
	$id = (int) escargot_option( 'footer_postcard_' . $i, 0 );
	if ( $id ) {
		$url = wp_get_attachment_image_url( $id, 'full' );
		if ( $url ) {
			$postcards[] = $url;
		}
	}
}
?>
<footer class="footer" id="footer">
	<div class="footer-content">
		<div class="footer-heading">
			<h2 data-copy data-type="lines"><?php echo esc_html( escargot_option( 'footer_heading', 'We Look Forward To Your Visit' ) ); ?></h2>
		</div>

		<div class="footer-button-container" id="footer-button-container">
			<?php escargot_button( $book_url, escargot_option( 'footer_button', 'Plan Your Visit' ) ); ?>
		</div>
	</div>

	<div class="footer-postcards" id="footer-postcards">
		<?php foreach ( $postcards as $url ) : ?>
			<div class="footer-postcard">
				<img src="<?php echo esc_url( $url ); ?>" alt="">
			</div>
		<?php endforeach; ?>
	</div>

	<div class="footer-bar">
		<div class="footer-bar-left">
			<p class="sm" data-copy data-type="lines" data-trigger="#footer .footer-heading"><?php echo esc_html( escargot_option( 'footer_copyright', '©2025 All Rights Reserved' ) ); ?></p>
		</div>
		<div class="footer-bar-right">
			<p class="sm" data-copy data-type="lines" data-trigger="#footer .footer-heading"><?php echo esc_html( escargot_option( 'footer_credits', 'Built by Escargot' ) ); ?></p>
		</div>
	</div>
</footer>
