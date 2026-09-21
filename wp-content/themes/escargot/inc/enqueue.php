<?php
/**
 * Assets enqueueing.
 *
 * @package Escargot
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue theme styles.
 */
function escargot_enqueue_styles() {
	$css = ESCARGOT_URI . '/assets/css';

	wp_enqueue_style( 'escargot-fonts', $css . '/fonts.css', array(), ESCARGOT_VERSION );
	wp_enqueue_style( 'escargot-globals', $css . '/globals.css', array(), ESCARGOT_VERSION );
	wp_enqueue_style( 'escargot-landing', $css . '/landing.css', array( 'escargot-globals' ), ESCARGOT_VERSION );
}
add_action( 'wp_enqueue_scripts', 'escargot_enqueue_styles' );

/**
 * Enqueue theme scripts.
 */
function escargot_enqueue_scripts() {
	$js = ESCARGOT_URI . '/assets/js';

	wp_enqueue_script( 'escargot-lenis', $js . '/vendor/lenis.min.js', array(), '1.3.8', true );
	wp_enqueue_script( 'escargot-smooth', $js . '/smooth-scroll.js', array( 'escargot-lenis' ), ESCARGOT_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'escargot_enqueue_scripts' );
