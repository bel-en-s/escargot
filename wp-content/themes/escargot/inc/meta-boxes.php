<?php
/**
 * Meta boxes for editable content.
 *
 * @package Escargot
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Current page template slug (for page editors).
 *
 * @return string
 */
function escargot_current_template() {
	global $post;
	if ( ! $post ) {
		return '';
	}
	return (string) get_page_template_slug( $post );
}

/**
 * Whether the current editor is the static front page.
 *
 * @return bool
 */
function escargot_is_front_page_edit() {
	global $post;
	if ( ! $post || 'page' !== get_post_type( $post ) ) {
		return false;
	}
	return (int) get_option( 'page_on_front' ) === (int) $post->ID;
}

/**
 * Print the meta nonce.
 */
function escargot_meta_nonce() {
	wp_nonce_field( 'escargot_save_meta', 'escargot_meta_nonce' );
}

/**
 * Render a text field.
 */
function escargot_field_text( $post, $key, $label, $placeholder = '' ) {
	$value = get_post_meta( $post->ID, $key, true );
	?>
	<p>
		<label for="<?php echo esc_attr( $key ); ?>"><strong><?php echo esc_html( $label ); ?></strong></label><br>
		<input type="text" id="<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( $value ); ?>" class="large-text" placeholder="<?php echo esc_attr( $placeholder ); ?>">
	</p>
	<?php
}

/**
 * Render a textarea field.
 */
function escargot_field_textarea( $post, $key, $label, $rows = 4, $placeholder = '' ) {
	$value = get_post_meta( $post->ID, $key, true );
	?>
	<p>
		<label for="<?php echo esc_attr( $key ); ?>"><strong><?php echo esc_html( $label ); ?></strong></label><br>
		<textarea id="<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $key ); ?>" rows="<?php echo esc_attr( $rows ); ?>" class="large-text" placeholder="<?php echo esc_attr( $placeholder ); ?>"><?php echo esc_textarea( $value ); ?></textarea>
	</p>
	<?php
}

/**
 * Render a select field.
 */
function escargot_field_select( $post, $key, $label, $options ) {
	$value = get_post_meta( $post->ID, $key, true );
	?>
	<p>
		<label for="<?php echo esc_attr( $key ); ?>"><strong><?php echo esc_html( $label ); ?></strong></label><br>
		<select id="<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $key ); ?>">
			<?php foreach ( $options as $opt_value => $opt_label ) : ?>
				<option value="<?php echo esc_attr( $opt_value ); ?>" <?php selected( $value, $opt_value ); ?>><?php echo esc_html( $opt_label ); ?></option>
			<?php endforeach; ?>
		</select>
	</p>
	<?php
}

/**
 * Render an image field (media library picker).
 */
function escargot_field_image( $post, $key, $label ) {
	$value = (int) get_post_meta( $post->ID, $key, true );
	$url   = $value ? wp_get_attachment_image_url( $value, 'medium' ) : '';
	?>
	<p>
		<label><strong><?php echo esc_html( $label ); ?></strong></label><br>
		<span class="escargot-image-field" data-input="<?php echo esc_attr( $key ); ?>">
			<input type="hidden" id="<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( $value ); ?>">
			<span class="escargot-image-preview" style="display:<?php echo $url ? 'block' : 'none'; ?>;max-width:240px;">
				<img src="<?php echo esc_url( $url ); ?>" style="max-width:100%;height:auto;">
			</span>
			<button type="button" class="button escargot-image-choose"><?php esc_html_e( 'Elegir imagen', 'escargot' ); ?></button>
			<button type="button" class="button escargot-image-remove" style="display:<?php echo $value ? 'inline-block' : 'none'; ?>;"><?php esc_html_e( 'Quitar', 'escargot' ); ?></button>
		</span>
	</p>
	<?php
}

/* ---------------------------------------------------------------------------
 * Meta box registration
 * ------------------------------------------------------------------------- */

function escargot_register_meta_boxes() {

	// Dish details.
	add_meta_box( 'escargot_dish_details', __( 'Detalles del plato', 'escargot' ), 'escargot_render_dish', 'escargot_dish', 'normal', 'high' );

	// Testimonial details.
	add_meta_box( 'escargot_testimonial_details', __( 'Contenido del testimonio', 'escargot' ), 'escargot_render_testimonial', 'escargot_testimonial', 'normal', 'high' );

	// Info card details.
	add_meta_box( 'escargot_info_card_details', __( 'Contenido de la tarjeta', 'escargot' ), 'escargot_render_info_card', 'escargot_info_card', 'normal', 'high' );

	// Page templates.
	if ( escargot_is_front_page_edit() ) {
		add_meta_box( 'escargot_home_fields', __( 'Contenido de la portada', 'escargot' ), 'escargot_render_home', 'page', 'normal', 'high' );
	}

	$template = escargot_current_template();

	if ( 'template-essence.php' === $template ) {
		add_meta_box( 'escargot_essence_fields', __( 'Contenido de Essence', 'escargot' ), 'escargot_render_essence', 'page', 'normal', 'high' );
	}

	if ( 'template-carte.php' === $template ) {
		add_meta_box( 'escargot_carte_fields', __( 'Contenido de Carte', 'escargot' ), 'escargot_render_carte', 'page', 'normal', 'high' );
	}

	if ( 'template-book.php' === $template ) {
		add_meta_box( 'escargot_book_fields', __( 'Contenido de Book', 'escargot' ), 'escargot_render_book', 'page', 'normal', 'high' );
	}
}
add_action( 'add_meta_boxes', 'escargot_register_meta_boxes' );

/* ---------------------------------------------------------------------------
 * Meta box renderers
 * ------------------------------------------------------------------------- */

function escargot_render_dish( $post ) {
	escargot_meta_nonce();
	escargot_field_text( $post, 'escargot_dish_price', __( 'Precio', 'escargot' ), '€6.50' );
	escargot_field_text( $post, 'escargot_dish_portion', __( 'Porción / tamaño', 'escargot' ), '250 g / 38 ml' );
	escargot_field_text( $post, 'escargot_dish_group', __( 'Subgrupo (opcional, solo bebidas)', 'escargot' ), 'Coffee' );
	escargot_field_textarea( $post, 'escargot_dish_description', __( 'Descripción', 'escargot' ), 3 );
	echo '<p class="description">' . esc_html__( 'Asigná la categoría del plato en el panel "Categorías del menú".', 'escargot' ) . '</p>';
}

function escargot_render_testimonial( $post ) {
	escargot_meta_nonce();
	escargot_field_textarea( $post, 'escargot_testimonial_review', __( 'Opinión', 'escargot' ), 5 );
	escargot_field_select(
		$post,
		'escargot_testimonial_rating',
		__( 'Puntuación', 'escargot' ),
		array( '5' => '5', '4' => '4', '3' => '3', '2' => '2', '1' => '1' )
	);
	echo '<p class="description">' . esc_html__( 'Usá la "Imagen destacada" para la foto de perfil.', 'escargot' ) . '</p>';
}

function escargot_render_info_card( $post ) {
	escargot_meta_nonce();
	escargot_field_select(
		$post,
		'escargot_info_icon',
		__( 'Ícono', 'escargot' ),
		array(
			'bolt'    => __( 'Rayo', 'escargot' ),
			'sparkle' => __( 'Destellos', 'escargot' ),
			'moon'    => __( 'Luna', 'escargot' ),
		)
	);
	escargot_field_textarea( $post, 'escargot_info_description', __( 'Descripción', 'escargot' ), 4 );
	escargot_field_textarea( $post, 'escargot_info_items', __( 'Ítems (uno por línea)', 'escargot' ), 5 );
}

function escargot_render_home( $post ) {
	escargot_meta_nonce();
	echo '<h3>' . esc_html__( 'Título', 'escargot' ) . '</h3>';
	escargot_field_text( $post, 'escargot_landing_title_1', __( 'Primera palabra', 'escargot' ), 'Escargot' );
	escargot_field_text( $post, 'escargot_landing_title_2', __( 'Segunda palabra (en cursiva)', 'escargot' ), 'Adventures' );

	echo '<h3>' . esc_html__( 'Foto principal', 'escargot' ) . '</h3>';
	escargot_field_image( $post, 'escargot_landing_photo', __( 'Foto (debajo del título)', 'escargot' ) );

	echo '<h3>' . esc_html__( 'Banner', 'escargot' ) . '</h3>';
	escargot_field_text( $post, 'escargot_banner_word_1', __( 'Palabra izquierda', 'escargot' ), 'More' );
	escargot_field_text( $post, 'escargot_banner_word_2', __( 'Palabra derecha', 'escargot' ), 'Stay' );
	escargot_field_image( $post, 'escargot_banner_logo', __( 'Logo (centro del banner)', 'escargot' ) );
	escargot_field_image( $post, 'escargot_banner_image', __( 'Foto de fondo del banner', 'escargot' ) );

	echo '<h3>' . esc_html__( 'Texto y CTA', 'escargot' ) . '</h3>';
	escargot_field_textarea( $post, 'escargot_about_text', __( 'Texto descriptivo', 'escargot' ), 3 );
	escargot_field_text( $post, 'escargot_cta_text', __( 'Texto del botón', 'escargot' ), 'Explore trips' );
	escargot_field_text( $post, 'escargot_cta_link', __( 'Link del botón', 'escargot' ), '#' );
}

function escargot_render_essence( $post ) {
	escargot_meta_nonce();
	echo '<h3>' . esc_html__( 'Hero', 'escargot' ) . '</h3>';
	escargot_field_text( $post, 'escargot_essence_line_1', __( 'Línea 1', 'escargot' ), 'Salle' );
	escargot_field_text( $post, 'escargot_essence_line_2', __( 'Línea 2', 'escargot' ), 'Blanche' );
	escargot_field_text( $post, 'escargot_essence_line_3', __( 'Línea 3', 'escargot' ), 'Story' );
	escargot_field_image( $post, 'escargot_essence_hero_image', __( 'Imagen del hero', 'escargot' ) );

	echo '<h3>' . esc_html__( 'Texto introductorio', 'escargot' ) . '</h3>';
	escargot_field_text( $post, 'escargot_essence_kicker', __( 'Kicker', 'escargot' ), 'Defined by Balance and Restraint' );
	escargot_field_textarea( $post, 'escargot_essence_paragraph_1', __( 'Párrafo 1', 'escargot' ), 3 );
	escargot_field_textarea( $post, 'escargot_essence_paragraph_2', __( 'Párrafo 2', 'escargot' ), 3 );

	echo '<h3>' . esc_html__( 'Marquee', 'escargot' ) . '</h3>';
	escargot_field_text( $post, 'escargot_marquee_heading', __( 'Título', 'escargot' ), 'Since 1984' );
	escargot_field_text( $post, 'escargot_marquee_sub', __( 'Subtítulo', 'escargot' ), 'Art of Stillness' );
	escargot_field_text( $post, 'escargot_marquee_pill', __( 'Texto de la píldora', 'escargot' ), 'Enduring Taste' );

	echo '<h3>' . esc_html__( 'Chefs', 'escargot' ) . '</h3>';
	escargot_field_text( $post, 'escargot_chefs_tag_1', __( 'Etiqueta 1', 'escargot' ), 'Craft & Technique' );
	escargot_field_text( $post, 'escargot_chefs_tag_2', __( 'Etiqueta 2', 'escargot' ), 'Fire & Form' );

	echo '<h3>' . esc_html__( 'Banner de imagen', 'escargot' ) . '</h3>';
	escargot_field_image( $post, 'escargot_essence_banner_image', __( 'Imagen del banner', 'escargot' ) );
}

function escargot_render_carte( $post ) {
	escargot_meta_nonce();
	escargot_field_text( $post, 'escargot_carte_heading', __( 'Título', 'escargot' ), 'A Menu Guided by Taste and Time' );
	escargot_field_text( $post, 'escargot_carte_tag_1', __( 'Etiqueta 1', 'escargot' ), 'The Carte' );
	escargot_field_text( $post, 'escargot_carte_tag_2', __( 'Etiqueta 2', 'escargot' ), 'Selected Courses' );
}

function escargot_render_book( $post ) {
	escargot_meta_nonce();
	escargot_field_text( $post, 'escargot_book_heading', __( 'Título', 'escargot' ), 'Set the Evening in Motion' );
	escargot_field_textarea( $post, 'escargot_book_paragraph', __( 'Párrafo', 'escargot' ), 3 );
	escargot_field_text( $post, 'escargot_book_button', __( 'Texto del botón', 'escargot' ), 'Reserve Your Evening' );
}

/* ---------------------------------------------------------------------------
 * Save handler
 * ------------------------------------------------------------------------- */

function escargot_save_meta( $post_id ) {
	if ( ! isset( $_POST['escargot_meta_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['escargot_meta_nonce'] ), 'escargot_save_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$text_keys = array(
		'escargot_dish_price',
		'escargot_dish_portion',
		'escargot_dish_group',
		'escargot_info_icon',
		'escargot_testimonial_rating',
		'escargot_landing_title_1',
		'escargot_landing_title_2',
		'escargot_banner_word_1',
		'escargot_banner_word_2',
		'escargot_cta_text',
		'escargot_cta_link',
		'escargot_essence_line_1',
		'escargot_essence_line_2',
		'escargot_essence_line_3',
		'escargot_essence_kicker',
		'escargot_marquee_heading',
		'escargot_marquee_sub',
		'escargot_marquee_pill',
		'escargot_chefs_tag_1',
		'escargot_chefs_tag_2',
		'escargot_carte_heading',
		'escargot_carte_tag_1',
		'escargot_carte_tag_2',
		'escargot_book_heading',
		'escargot_book_button',
	);

	$textarea_keys = array(
		'escargot_dish_description',
		'escargot_testimonial_review',
		'escargot_info_description',
		'escargot_info_items',
		'escargot_about_text',
		'escargot_essence_paragraph_1',
		'escargot_essence_paragraph_2',
		'escargot_book_paragraph',
	);

	$image_keys = array(
		'escargot_landing_photo',
		'escargot_banner_logo',
		'escargot_banner_image',
		'escargot_essence_hero_image',
		'escargot_essence_banner_image',
	);

	foreach ( $text_keys as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) );
		} else {
			delete_post_meta( $post_id, $key );
		}
	}

	foreach ( $textarea_keys as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $key, sanitize_textarea_field( wp_unslash( $_POST[ $key ] ) ) );
		} else {
			delete_post_meta( $post_id, $key );
		}
	}

	foreach ( $image_keys as $key ) {
		if ( isset( $_POST[ $key ] ) && '' !== $_POST[ $key ] ) {
			update_post_meta( $post_id, $key, absint( $_POST[ $key ] ) );
		} else {
			delete_post_meta( $post_id, $key );
		}
	}
}
add_action( 'save_post', 'escargot_save_meta' );

/* ---------------------------------------------------------------------------
 * Category image (term meta for menu categories)
 * ------------------------------------------------------------------------- */

function escargot_category_image_field( $term ) {
	$term_id = is_object( $term ) ? $term->term_id : 0;
	$value   = $term_id ? (int) get_term_meta( $term_id, 'escargot_category_image', true ) : 0;
	$url     = $value ? wp_get_attachment_image_url( $value, 'medium' ) : '';

	$is_edit = is_object( $term );
	?>
	<?php if ( $is_edit ) : ?>
		<tr class="form-field term-image-wrap">
			<th scope="row"><label><?php esc_html_e( 'Imagen de la categoría', 'escargot' ); ?></label></th>
			<td>
	<?php else : ?>
		<div class="form-field term-image-wrap">
			<label><?php esc_html_e( 'Imagen de la categoría', 'escargot' ); ?></label>
	<?php endif; ?>

			<span class="escargot-image-field">
				<input type="hidden" name="escargot_category_image" value="<?php echo esc_attr( $value ); ?>">
				<span class="escargot-image-preview" style="display:<?php echo $url ? 'block' : 'none'; ?>;max-width:240px;">
					<img src="<?php echo esc_url( $url ); ?>" style="max-width:100%;height:auto;">
				</span>
				<button type="button" class="button escargot-image-choose"><?php esc_html_e( 'Elegir imagen', 'escargot' ); ?></button>
				<button type="button" class="button escargot-image-remove" style="display:<?php echo $value ? 'inline-block' : 'none'; ?>;"><?php esc_html_e( 'Quitar', 'escargot' ); ?></button>
			</span>

	<?php if ( $is_edit ) : ?>
			</td>
		</tr>
	<?php else : ?>
		</div>
	<?php endif; ?>
	<?php
}

function escargot_category_image_hooks() {
	add_action( 'menu_category_edit_form_fields', 'escargot_category_image_field' );
	add_action( 'menu_category_add_form_fields', 'escargot_category_image_field' );
}
add_action( 'admin_init', 'escargot_category_image_hooks' );

function escargot_save_category_image( $term_id ) {
	if ( isset( $_POST['escargot_category_image'] ) ) {
		update_term_meta( $term_id, 'escargot_category_image', absint( $_POST['escargot_category_image'] ) );
	}
}
add_action( 'edited_menu_category', 'escargot_save_category_image' );
add_action( 'created_menu_category', 'escargot_save_category_image' );
