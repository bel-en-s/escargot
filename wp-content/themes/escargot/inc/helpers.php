<?php
/**
 * Helper functions.
 *
 * @package Escargot
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get a site setting from the Escargot options page.
 *
 * @param string $key     Setting key.
 * @param string $default Fallback value.
 * @return string
 */
function escargot_option( $key, $default = '' ) {
	$options = get_option( 'escargot_settings', array() );
	$value   = isset( $options[ $key ] ) ? $options[ $key ] : '';
	return ( '' !== $value && null !== $value ) ? $value : $default;
}

/**
 * Get a post meta value with a fallback.
 *
 * @param int    $post_id Post ID.
 * @param string $key     Meta key.
 * @param string $default Fallback value.
 * @return string
 */
function escargot_meta( $post_id, $key, $default = '' ) {
	$value = get_post_meta( $post_id, $key, true );
	return ( '' !== $value && null !== $value ) ? $value : $default;
}

/**
 * Resolve an image attachment id to a URL, with a theme fallback path.
 *
 * @param int    $id       Attachment id.
 * @param string $size     Image size.
 * @param string $fallback Relative theme path (e.g. assets/img/placeholder.jpg).
 * @return string
 */
function escargot_image( $id, $size = 'full', $fallback = '' ) {
	if ( $id ) {
		$src = wp_get_attachment_image_url( (int) $id, $size );
		if ( $src ) {
			return $src;
		}
	}
	return $fallback ? esc_url( ESCARGOT_URI . '/' . ltrim( $fallback, '/' ) ) : '';
}

/**
 * Escape and print a value.
 *
 * @param string $value Value.
 */
function escargot_e( $value ) {
	echo wp_kses_post( $value );
}

/**
 * Split a string into heading lines (one per newline).
 *
 * @param string $value Raw value.
 * @return array
 */
function escargot_lines( $value ) {
	$value = trim( (string) $value );
	if ( '' === $value ) {
		return array();
	}
	return preg_split( '/\r\n|\r|\n/', $value );
}

/**
 * Find a page URL by its assigned template.
 *
 * @param string $template Template filename.
 * @return string
 */
function escargot_get_template_page_url( $template ) {
	$pages = get_pages(
		array(
			'meta_key'   => '_wp_page_template',
			'meta_value' => $template,
			'number'     => 1,
		)
	);

	if ( ! empty( $pages ) ) {
		return get_permalink( $pages[0] );
	}

	return home_url( '/' );
}

/**
 * Render the animated slide button (with per-character hover).
 *
 * @param string $href        Link url.
 * @param string $text        Button label.
 * @param string $extra_class Extra css classes.
 * @param array  $attrs       Extra attributes (key => value).
 */
function escargot_button( $href, $text, $extra_class = '', $attrs = array() ) {
	$classes = 'slide-button' . ( $extra_class ? ' ' . $extra_class : '' );

	$attr_string = '';
	foreach ( $attrs as $key => $value ) {
		$attr_string .= ' ' . $key . '="' . esc_attr( $value ) . '"';
	}

	echo '<a href="' . esc_url( $href ) . '" class="' . esc_attr( $classes ) . '"' . $attr_string . '>';
	echo '<div class="slide-button-bg"></div>';
	echo '<span class="slide-button-text">';

	$chars = preg_split( '//u', $text, -1, PREG_SPLIT_NO_EMPTY );
	foreach ( $chars as $char ) {
		$char = ( ' ' === $char ) ? '&nbsp;' : esc_html( $char );
		echo '<span class="slide-char"><span class="char-default">' . $char . '</span><span class="char-hover">' . $char . '</span></span>';
	}

	echo '</span></a>';
}

/**
 * Render a star rating.
 *
 * @param int $count Filled stars.
 */
function escargot_stars( $count ) {
	$count = max( 0, min( 5, (int) $count ) );
	echo '<div class="testimonial-stars">';
	for ( $i = 1; $i <= 5; $i++ ) {
		$class = $i <= $count ? 'star filled' : 'star';
		echo '<span class="' . esc_attr( $class ) . '">&#9733;</span>';
	}
	echo '</div>';
}

/**
 * Render a string split into letter spans (for letter-by-letter animation).
 *
 * @param string $text Text.
 */
function escargot_letters( $text ) {
	$chars = preg_split( '//u', (string) $text, -1, PREG_SPLIT_NO_EMPTY );
	foreach ( $chars as $char ) {
		echo '<span class="landing-letter">' . ( ' ' === $char ? '&nbsp;' : esc_html( $char ) ) . '</span>';
	}
}

/**
 * Get menu categories (terms) that have dishes.
 *
 * @return array
 */
function escargot_menu_categories() {
	$terms = get_terms(
		array(
			'taxonomy'   => 'menu_category',
			'hide_empty' => true,
			'orderby'    => 'term_id',
			'order'      => 'ASC',
		)
	);
	return is_wp_error( $terms ) ? array() : $terms;
}

/**
 * Get dishes for a category term id, ordered.
 *
 * @param int $term_id Term id.
 * @return array
 */
function escargot_dishes_in( $term_id ) {
	return get_posts(
		array(
			'post_type'      => 'escargot_dish',
			'posts_per_page' => -1,
			'orderby'        => 'menu_order date',
			'order'          => 'ASC',
			'tax_query'      => array(
				array(
					'taxonomy' => 'menu_category',
					'field'    => 'term_id',
					'terms'    => (int) $term_id,
				),
			),
		)
	);
}

/**
 * Get a dish field with fallback.
 *
 * @param int    $post_id Dish post id.
 * @param string $key     Field key.
 * @param string $default Fallback.
 * @return string
 */
function escargot_dish( $post_id, $key, $default = '' ) {
	return escargot_meta( $post_id, $key, $default );
}

/**
 * Get the category image URL.
 *
 * @param int $term_id Term id.
 * @return string
 */
function escargot_category_image_url( $term_id ) {
	$id = (int) get_term_meta( $term_id, 'escargot_category_image', true );
	return $id ? wp_get_attachment_image_url( $id, 'full' ) : '';
}

/**
 * Render an inline SVG icon (heroicons outline/solid).
 *
 * @param string $name Icon name.
 * @param int    $size Icon size in px.
 */
function escargot_icon( $name, $size = 24 ) {
	$solid = array( 'bolt', 'sparkle', 'moon' );

	$paths = array(
		'arrow-up'   => array( 'outline', 'M4.5 15.75l7.5-7.5 7.5 7.5' ),
		'arrow-down' => array( 'outline', 'M19.5 8.25l-7.5 7.5-7.5-7.5' ),
		'arrow-left' => array( 'outline', 'M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18' ),
		'arrow-right' => array( 'outline', 'M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3' ),
		'bolt'       => array( 'solid', 'M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z' ),
		'sparkle'    => array( 'solid', 'M9.813 15.904L9.75 16.5l-.063-.596a4.125 4.125 0 00-3.03-3.03L6 12.75l.657-.125a4.125 4.125 0 003.03-3.03L9.75 9l.063.596a4.125 4.125 0 003.03 3.03l.657.125-.657.125a4.125 4.125 0 00-3.03 3.028zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z' ),
		'moon'       => array( 'solid', 'M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z' ),
	);

	if ( ! isset( $paths[ $name ] ) ) {
		return;
	}

	$type = $paths[ $name ][0];
	$d    = $paths[ $name ][1];

	if ( 'solid' === $type ) {
		echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="' . esc_attr( $size ) . '" height="' . esc_attr( $size ) . '" fill="currentColor"><path d="' . esc_attr( $d ) . '"/></svg>';
	} else {
		echo '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="' . esc_attr( $size ) . '" height="' . esc_attr( $size ) . '"><path stroke-linecap="round" stroke-linejoin="round" d="' . esc_attr( $d ) . '"/></svg>';
	}
}
