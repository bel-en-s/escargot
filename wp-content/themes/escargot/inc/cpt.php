<?php
/**
 * Custom post types and taxonomies.
 *
 * @package Escargot
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register custom post types.
 */
function escargot_register_post_types() {

	// Menu items (dishes).
	register_post_type(
		'escargot_dish',
		array(
			'labels'       => array(
				'name'          => __( 'Platos', 'escargot' ),
				'singular_name' => __( 'Plato', 'escargot' ),
				'add_new_item'  => __( 'Agregar plato', 'escargot' ),
				'edit_item'     => __( 'Editar plato', 'escargot' ),
			),
			'public'       => true,
			'show_in_rest' => true,
			'menu_icon'    => 'dashicons-carrot',
			'supports'     => array( 'title', 'page-attributes' ),
			'has_archive'  => false,
			'rewrite'      => array( 'slug' => 'dish' ),
		)
	);

	// Testimonials.
	register_post_type(
		'escargot_testimonial',
		array(
			'labels'       => array(
				'name'          => __( 'Testimonios', 'escargot' ),
				'singular_name' => __( 'Testimonio', 'escargot' ),
				'add_new_item'  => __( 'Agregar testimonio', 'escargot' ),
				'edit_item'     => __( 'Editar testimonio', 'escargot' ),
			),
			'public'       => true,
			'show_in_rest' => true,
			'menu_icon'    => 'dashicons-format-quote',
			'supports'     => array( 'title', 'thumbnail' ),
			'has_archive'  => false,
		)
	);

	// Chefs.
	register_post_type(
		'escargot_chef',
		array(
			'labels'       => array(
				'name'          => __( 'Chefs', 'escargot' ),
				'singular_name' => __( 'Chef', 'escargot' ),
				'add_new_item'  => __( 'Agregar chef', 'escargot' ),
				'edit_item'     => __( 'Editar chef', 'escargot' ),
			),
			'public'       => true,
			'show_in_rest' => true,
			'menu_icon'    => 'dashicons-businessperson',
			'supports'     => array( 'title', 'thumbnail', 'page-attributes' ),
			'has_archive'  => false,
		)
	);

	// Story cards (About page sticky cards).
	register_post_type(
		'escargot_story',
		array(
			'labels'       => array(
				'name'          => __( 'Historia (tarjetas)', 'escargot' ),
				'singular_name' => __( 'Tarjeta', 'escargot' ),
				'add_new_item'  => __( 'Agregar tarjeta', 'escargot' ),
				'edit_item'     => __( 'Editar tarjeta', 'escargot' ),
			),
			'public'       => true,
			'show_in_rest' => true,
			'menu_icon'    => 'dashicons-images-alt2',
			'supports'     => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
			'has_archive'  => false,
		)
	);

	// Info cards (Reservation page).
	register_post_type(
		'escargot_info_card',
		array(
			'labels'       => array(
				'name'          => __( 'Tarjetas de info', 'escargot' ),
				'singular_name' => __( 'Tarjeta de info', 'escargot' ),
				'add_new_item'  => __( 'Agregar tarjeta', 'escargot' ),
				'edit_item'     => __( 'Editar tarjeta', 'escargot' ),
			),
			'public'       => true,
			'show_in_rest' => true,
			'menu_icon'    => 'dashicons-info',
			'supports'     => array( 'title', 'page-attributes' ),
			'has_archive'  => false,
		)
	);
}
add_action( 'init', 'escargot_register_post_types' );

/**
 * Register taxonomies.
 */
function escargot_register_taxonomies() {
	register_taxonomy(
		'menu_category',
		array( 'escargot_dish' ),
		array(
			'labels'            => array(
				'name'          => __( 'Categorías del menú', 'escargot' ),
				'singular_name' => __( 'Categoría', 'escargot' ),
			),
			'hierarchical'      => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => array( 'slug' => 'menu-category' ),
		)
	);
}
add_action( 'init', 'escargot_register_taxonomies' );
