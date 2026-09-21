<?php
/**
 * Chefs section (About page).
 *
 * @package Escargot
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$chefs = get_posts(
	array(
		'post_type'      => 'escargot_chef',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order date',
		'order'          => 'ASC',
	)
);

if ( empty( $chefs ) ) {
	return;
}

$post_id   = get_the_ID();
$tag_1     = escargot_meta( $post_id, 'escargot_chefs_tag_1', 'Craft & Technique' );
$tag_2     = escargot_meta( $post_id, 'escargot_chefs_tag_2', 'Fire & Form' );
$default   = __( 'The Chefs', 'escargot' );

/**
 * Render a string split into letters.
 */
$render_letters = function ( $text ) {
	$chars = preg_split( '//u', $text, -1, PREG_SPLIT_NO_EMPTY );
	foreach ( $chars as $char ) {
		echo '<span class="letter">' . ( ' ' === $char ? '&nbsp;' : esc_html( $char ) ) . '</span>';
	}
};
?>
<section class="chefs">
	<div class="chefs-avatars">
		<?php foreach ( $chefs as $chef ) : ?>
			<?php $photo = get_the_post_thumbnail_url( $chef->ID, 'thumbnail' ); ?>
			<div class="chefs-avatar">
				<?php if ( $photo ) : ?>
					<img src="<?php echo esc_url( $photo ); ?>" alt="<?php echo esc_attr( get_the_title( $chef ) ); ?>">
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>

	<div class="chefs-names">
		<div class="chefs-name chefs-name--default">
			<h1><?php $render_letters( $default ); ?></h1>
		</div>
		<?php foreach ( $chefs as $chef ) : ?>
			<div class="chefs-name">
				<h1><?php $render_letters( get_the_title( $chef ) ); ?></h1>
			</div>
		<?php endforeach; ?>
	</div>

	<div class="section-footer">
		<p class="sm"><?php echo esc_html( $tag_1 ); ?></p>
		<p class="sm"><?php echo esc_html( $tag_2 ); ?></p>
	</div>
</section>
