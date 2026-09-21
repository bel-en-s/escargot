<?php
/**
 * Preloader (front page only).
 *
 * @package Escargot
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$title  = escargot_option( 'preloader_title', get_bloginfo( 'name' ) );
$button = escargot_option( 'preloader_button', 'Enter Website' );
?>
<section class="preloader" id="preloader" aria-label="<?php esc_attr_e( 'Website preloader', 'escargot' ); ?>">
	<div class="preloader-inner">
		<div class="preloader-title-wrap">
			<h2 class="preloader-title preloader-title-base"><?php echo esc_html( $title ); ?></h2>
			<h2 class="preloader-title preloader-title-fill" id="preloader-fill" style="width:0%"><?php echo esc_html( $title ); ?></h2>
		</div>

		<div class="preloader-action-slot">
			<p class="preloader-loading mono" id="preloader-loading" aria-live="polite">Loading... 0%</p>
			<div class="preloader-button-wrap" id="preloader-button-wrap">
				<?php escargot_button( '#', $button, 'preloader-enter-button', array( 'id' => 'preloader-enter' ) ); ?>
			</div>
		</div>
	</div>
</section>
