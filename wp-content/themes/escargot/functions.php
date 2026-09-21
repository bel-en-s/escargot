<?php
/**
 * Escargot theme bootstrap.
 *
 * @package Escargot
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ESCARGOT_VERSION', '1.0.0' );
define( 'ESCARGOT_DIR', get_template_directory() );
define( 'ESCARGOT_URI', get_template_directory_uri() );

require_once ESCARGOT_DIR . '/inc/helpers.php';
require_once ESCARGOT_DIR . '/inc/enqueue.php';
require_once ESCARGOT_DIR . '/inc/cpt.php';
require_once ESCARGOT_DIR . '/inc/meta-boxes.php';
require_once ESCARGOT_DIR . '/inc/options.php';

if ( ! function_exists( 'escargot_setup' ) ) {
	/**
	 * Theme supports.
	 */
	function escargot_setup() {
		load_theme_textdomain( 'escargot', ESCARGOT_DIR . '/languages' );

		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'custom-logo' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support(
			'html5',
			array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
		);

		register_nav_menus(
			array(
				'primary' => __( 'Primary Menu', 'escargot' ),
			)
		);

		add_image_size( 'escargot-card', 900, 1200, true );
		add_image_size( 'escargot-square', 800, 800, true );
	}
}
add_action( 'after_setup_theme', 'escargot_setup' );

/**
 * Allow SVG uploads (used for the custom logo mark).
 *
 * @param array $mimes Mime types.
 * @return array
 */
function escargot_mime_types( $mimes ) {
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
}
add_filter( 'upload_mimes', 'escargot_mime_types' );

/**
 * Add body classes for the active page template.
 *
 * @param array $classes Body classes.
 * @return array
 */
function escargot_body_class( $classes ) {
	if ( is_front_page() ) {
		$classes[] = 'is-front';
	}
	return $classes;
}
add_filter( 'body_class', 'escargot_body_class' );
