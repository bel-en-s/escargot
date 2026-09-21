<?php
/**
 * Site settings (options page).
 *
 * @package Escargot
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the settings group.
 */
function escargot_register_settings() {
	register_setting( 'escargot_settings_group', 'escargot_settings', 'escargot_sanitize_settings' );
}
add_action( 'admin_init', 'escargot_register_settings' );

/**
 * Sanitize the settings array.
 *
 * @param array $input Raw input.
 * @return array
 */
function escargot_sanitize_settings( $input ) {
	$output = array();

	$text_keys = array(
		'preloader_title',
		'preloader_button',
		'hero_tagline_1',
		'hero_tagline_2',
		'footer_heading',
		'footer_button',
		'footer_copyright',
		'footer_credits',
		'social_instagram',
		'social_google',
		'social_opentable',
		'contact_phone',
		'contact_email',
		'contact_press_email',
		'address_line1',
		'address_line2',
		'hours_week',
		'hours_weekend',
		'cta_kicker',
		'cta_button',
		'dining_menu_heading',
		'testimonials_heading',
	);

	$textarea_keys = array(
		'cta_text',
		'banner_heading',
		'banner_description',
	);

	$image_keys = array(
		'logo_image',
		'cta_image',
		'dining_bg_image',
	);

	for ( $i = 1; $i <= 5; $i++ ) {
		$image_keys[] = 'footer_postcard_' . $i;
	}

	foreach ( $text_keys as $key ) {
		$output[ $key ] = isset( $input[ $key ] ) ? sanitize_text_field( $input[ $key ] ) : '';
	}

	foreach ( $textarea_keys as $key ) {
		$output[ $key ] = isset( $input[ $key ] ) ? sanitize_textarea_field( $input[ $key ] ) : '';
	}

	foreach ( $image_keys as $key ) {
		$output[ $key ] = isset( $input[ $key ] ) ? absint( $input[ $key ] ) : 0;
	}

	return $output;
}

/**
 * Add the options page.
 */
function escargot_add_options_page() {
	add_options_page(
		__( 'Ajustes del sitio', 'escargot' ),
		__( 'Ajustes del sitio', 'escargot' ),
		'manage_options',
		'escargot-settings',
		'escargot_render_options_page'
	);
}
add_action( 'admin_menu', 'escargot_add_options_page' );

/**
 * Enqueue admin script for image pickers.
 */
function escargot_admin_scripts( $hook ) {
	if ( 'settings_page_escargot-settings' !== $hook && 'post.php' !== $hook && 'post-new.php' !== $hook && 'edit-tags.php' !== $hook ) {
		return;
	}
	wp_enqueue_media();
	wp_enqueue_script( 'escargot-admin', ESCARGOT_URI . '/assets/js/admin.js', array( 'jquery' ), ESCARGOT_VERSION, true );
}
add_action( 'admin_enqueue_scripts', 'escargot_admin_scripts' );

/**
 * Render an option text field.
 */
function escargot_option_field_text( $options, $key, $label, $placeholder = '' ) {
	$value = isset( $options[ $key ] ) ? $options[ $key ] : '';
	?>
	<p>
		<label for="escargot_<?php echo esc_attr( $key ); ?>"><strong><?php echo esc_html( $label ); ?></strong></label><br>
		<input type="text" id="escargot_<?php echo esc_attr( $key ); ?>" name="escargot_settings[<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( $value ); ?>" class="large-text" placeholder="<?php echo esc_attr( $placeholder ); ?>">
	</p>
	<?php
}

/**
 * Render an option textarea field.
 */
function escargot_option_field_textarea( $options, $key, $label, $rows = 4 ) {
	$value = isset( $options[ $key ] ) ? $options[ $key ] : '';
	?>
	<p>
		<label for="escargot_<?php echo esc_attr( $key ); ?>"><strong><?php echo esc_html( $label ); ?></strong></label><br>
		<textarea id="escargot_<?php echo esc_attr( $key ); ?>" name="escargot_settings[<?php echo esc_attr( $key ); ?>]" rows="<?php echo esc_attr( $rows ); ?>" class="large-text"><?php echo esc_textarea( $value ); ?></textarea>
	</p>
	<?php
}

/**
 * Render an option image field.
 */
function escargot_option_field_image( $options, $key, $label ) {
	$value = isset( $options[ $key ] ) ? (int) $options[ $key ] : 0;
	$url   = $value ? wp_get_attachment_image_url( $value, 'medium' ) : '';
	?>
	<p>
		<label><strong><?php echo esc_html( $label ); ?></strong></label><br>
		<span class="escargot-image-field">
			<input type="hidden" name="escargot_settings[<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( $value ); ?>">
			<span class="escargot-image-preview" style="display:<?php echo $url ? 'block' : 'none'; ?>;max-width:240px;">
				<img src="<?php echo esc_url( $url ); ?>" style="max-width:100%;height:auto;">
			</span>
			<button type="button" class="button escargot-image-choose"><?php esc_html_e( 'Elegir imagen', 'escargot' ); ?></button>
			<button type="button" class="button escargot-image-remove" style="display:<?php echo $value ? 'inline-block' : 'none'; ?>;"><?php esc_html_e( 'Quitar', 'escargot' ); ?></button>
		</span>
	</p>
	<?php
}

/**
 * Render the options page.
 */
function escargot_render_options_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$options = get_option( 'escargot_settings', array() );
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Ajustes del sitio', 'escargot' ); ?></h1>
		<form method="post" action="options.php">
			<?php settings_fields( 'escargot_settings_group' ); ?>

			<h2><?php esc_html_e( 'Marca / Preloader', 'escargot' ); ?></h2>
			<?php escargot_option_field_image( $options, 'logo_image', __( 'Logo (opcional)', 'escargot' ) ); ?>
			<table class="form-table" role="presentation">
				<tbody>
					<tr>
						<th scope="row"><?php esc_html_e( 'Título del preloader', 'escargot' ); ?></th>
						<td><?php escargot_option_field_text( $options, 'preloader_title', '', 'Salle Blanche' ); ?></td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Botón del preloader', 'escargot' ); ?></th>
						<td><?php escargot_option_field_text( $options, 'preloader_button', '', 'Enter Website' ); ?></td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Etiqueta hero 1', 'escargot' ); ?></th>
						<td><?php escargot_option_field_text( $options, 'hero_tagline_1', '', 'Since 1984' ); ?></td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Etiqueta hero 2', 'escargot' ); ?></th>
						<td><?php escargot_option_field_text( $options, 'hero_tagline_2', '', 'Florence, IT' ); ?></td>
					</tr>
				</tbody>
			</table>

			<h2><?php esc_html_e( 'Footer', 'escargot' ); ?></h2>
			<table class="form-table" role="presentation">
				<tbody>
					<tr>
						<th scope="row"><?php esc_html_e( 'Título', 'escargot' ); ?></th>
						<td><?php escargot_option_field_text( $options, 'footer_heading', '', 'We Look Forward To Your Visit' ); ?></td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Texto del botón', 'escargot' ); ?></th>
						<td><?php escargot_option_field_text( $options, 'footer_button', '', 'Plan Your Visit' ); ?></td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Copyright', 'escargot' ); ?></th>
						<td><?php escargot_option_field_text( $options, 'footer_copyright', '', '©2025 All Rights Reserved' ); ?></td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Créditos', 'escargot' ); ?></th>
						<td><?php escargot_option_field_text( $options, 'footer_credits', '', 'Built by Escargot' ); ?></td>
					</tr>
				</tbody>
			</table>

			<h3><?php esc_html_e( 'Postales del footer (5 imágenes)', 'escargot' ); ?></h3>
			<?php for ( $i = 1; $i <= 5; $i++ ) : ?>
				<?php escargot_option_field_image( $options, 'footer_postcard_' . $i, sprintf( __( 'Postal %d', 'escargot' ), $i ) ); ?>
			<?php endfor; ?>

			<h2><?php esc_html_e( 'Redes sociales', 'escargot' ); ?></h2>
			<table class="form-table" role="presentation">
				<tbody>
					<tr>
						<th scope="row">Instagram</th>
						<td><?php escargot_option_field_text( $options, 'social_instagram', '', 'https://instagram.com' ); ?></td>
					</tr>
					<tr>
						<th scope="row">Google</th>
						<td><?php escargot_option_field_text( $options, 'social_google', '', 'https://google.com' ); ?></td>
					</tr>
					<tr>
						<th scope="row">OpenTable</th>
						<td><?php escargot_option_field_text( $options, 'social_opentable', '', 'https://opentable.com' ); ?></td>
					</tr>
				</tbody>
			</table>

			<h2><?php esc_html_e( 'Contacto', 'escargot' ); ?></h2>
			<table class="form-table" role="presentation">
				<tbody>
					<tr>
						<th scope="row"><?php esc_html_e( 'Teléfono', 'escargot' ); ?></th>
						<td><?php escargot_option_field_text( $options, 'contact_phone', '', '+39 055 398 2417' ); ?></td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Email', 'escargot' ); ?></th>
						<td><?php escargot_option_field_text( $options, 'contact_email', '', 'hello@example.com' ); ?></td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Email prensa', 'escargot' ); ?></th>
						<td><?php escargot_option_field_text( $options, 'contact_press_email', '', 'press@example.com' ); ?></td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Dirección (línea 1)', 'escargot' ); ?></th>
						<td><?php escargot_option_field_text( $options, 'address_line1', '', 'Piazza Santo Spirito 8' ); ?></td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Dirección (línea 2)', 'escargot' ); ?></th>
						<td><?php escargot_option_field_text( $options, 'address_line2', '', 'Firenze, IT 50125' ); ?></td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Horarios (semana)', 'escargot' ); ?></th>
						<td><?php escargot_option_field_text( $options, 'hours_week', '', 'LUN–VEN 19:00–23:00' ); ?></td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Horarios (finde)', 'escargot' ); ?></th>
						<td><?php escargot_option_field_text( $options, 'hours_weekend', '', 'SAB–DOM 18:30–23:30' ); ?></td>
					</tr>
				</tbody>
			</table>

			<h2><?php esc_html_e( 'CTA (llamado a la acción)', 'escargot' ); ?></h2>
			<table class="form-table" role="presentation">
				<tbody>
					<tr>
						<th scope="row"><?php esc_html_e( 'Kicker', 'escargot' ); ?></th>
						<td><?php escargot_option_field_text( $options, 'cta_kicker', '', 'A table awaits' ); ?></td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Texto', 'escargot' ); ?></th>
						<td><?php escargot_option_field_textarea( $options, 'cta_text', '', 3 ); ?></td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Botón', 'escargot' ); ?></th>
						<td><?php escargot_option_field_text( $options, 'cta_button', '', 'Take a Table' ); ?></td>
					</tr>
				</tbody>
			</table>
			<?php escargot_option_field_image( $options, 'cta_image', __( 'Imagen del CTA', 'escargot' ) ); ?>

			<h2><?php esc_html_e( 'Menú (vista previa)', 'escargot' ); ?></h2>
			<table class="form-table" role="presentation">
				<tbody>
					<tr>
						<th scope="row"><?php esc_html_e( 'Título', 'escargot' ); ?></th>
						<td><?php escargot_option_field_text( $options, 'dining_menu_heading', '', 'Our Menu' ); ?></td>
					</tr>
				</tbody>
			</table>
			<?php escargot_option_field_image( $options, 'dining_bg_image', __( 'Imagen de fondo de la sección menú', 'escargot' ) ); ?>

			<h2><?php esc_html_e( 'Testimonios', 'escargot' ); ?></h2>
			<table class="form-table" role="presentation">
				<tbody>
					<tr>
						<th scope="row"><?php esc_html_e( 'Título de la sección', 'escargot' ); ?></th>
						<td><?php escargot_option_field_text( $options, 'testimonials_heading', '', 'What our clients say' ); ?></td>
					</tr>
				</tbody>
			</table>

			<h2><?php esc_html_e( 'Banner de imagen', 'escargot' ); ?></h2>
			<table class="form-table" role="presentation">
				<tbody>
					<tr>
						<th scope="row"><?php esc_html_e( 'Título (una palabra por línea)', 'escargot' ); ?></th>
						<td><?php escargot_option_field_textarea( $options, 'banner_heading', '', 3 ); ?></td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Descripción', 'escargot' ); ?></th>
						<td><?php escargot_option_field_textarea( $options, 'banner_description', '', 2 ); ?></td>
					</tr>
				</tbody>
			</table>

			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}
