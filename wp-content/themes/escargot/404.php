<?php
/**
 * 404 template.
 *
 * @package Escargot
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<main class="default-content" style="min-height:60vh;padding:12rem 2.5rem;text-align:center;">
	<h1><?php esc_html_e( '404', 'escargot' ); ?></h1>
	<p style="margin-top:2rem;"><?php esc_html_e( 'La página que buscás no existe.', 'escargot' ); ?></p>
</main>
<?php get_footer(); ?>
