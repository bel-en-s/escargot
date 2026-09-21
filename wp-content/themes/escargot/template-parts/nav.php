<?php
/**
 * Navigation (fixed bar + fullscreen overlay menu).
 *
 * @package Escargot
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$locations = get_nav_menu_locations();
$menu_id   = isset( $locations['primary'] ) ? $locations['primary'] : 0;
$items     = $menu_id ? wp_get_nav_menu_items( $menu_id ) : array();

$logo_id  = (int) escargot_option( 'logo_image', 0 );
$logo_url = $logo_id ? wp_get_attachment_image_url( $logo_id, 'full' ) : '';

$socials = array(
	array(
		'label' => __( 'Instagram', 'escargot' ),
		'url'   => escargot_option( 'social_instagram', '#' ),
	),
	array(
		'label' => __( 'Google', 'escargot' ),
		'url'   => escargot_option( 'social_google', '#' ),
	),
	array(
		'label' => __( 'OpenTable', 'escargot' ),
		'url'   => escargot_option( 'social_opentable', '#' ),
	),
);
?>
<nav class="nav-bar">
	<div class="nav-logo">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<?php if ( $logo_url ) : ?>
				<img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php bloginfo( 'name' ); ?>">
			<?php else : ?>
				<span class="nav-logo-text"><?php bloginfo( 'name' ); ?></span>
			<?php endif; ?>
		</a>
	</div>

	<div class="nav-toggle" id="nav-toggle">
		<p id="nav-toggle-open"><?php esc_html_e( 'Menu', 'escargot' ); ?></p>
		<p id="nav-toggle-close"><?php esc_html_e( 'Close', 'escargot' ); ?></p>
	</div>
</nav>

<div class="nav-menu-overlay">
	<div class="nav-menu-content">
		<div class="nav-menu-items">
			<div class="nav-col-lg">
				<div class="nav-preview-img" id="nav-preview-img">
					<?php if ( ! empty( $items ) ) : ?>
						<?php $first_img = get_the_post_thumbnail_url( $items[0]->object_id, 'full' ); ?>
						<?php if ( $first_img ) : ?>
							<img src="<?php echo esc_url( $first_img ); ?>" alt="">
						<?php endif; ?>
					<?php endif; ?>
				</div>
			</div>

			<div class="nav-col-sm">
				<div class="nav-menu-links">
					<?php foreach ( $items as $item ) : ?>
						<?php $preview = get_the_post_thumbnail_url( $item->object_id, 'full' ); ?>
						<div class="nav-link">
							<a href="<?php echo esc_url( $item->url ); ?>" data-img="<?php echo esc_url( $preview ); ?>"><?php echo esc_html( $item->title ); ?></a>
						</div>
					<?php endforeach; ?>
				</div>

				<div class="nav-menu-socials">
					<?php foreach ( $socials as $social ) : ?>
						<div class="nav-social">
							<a href="<?php echo esc_url( $social['url'] ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $social['label'] ); ?></a>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>

		<div class="nav-menu-footer">
			<p class="sm"><span><?php echo esc_html( escargot_option( 'hero_tagline_1', 'Since 1984' ) ); ?></span></p>
			<p class="sm"><span><?php echo esc_html( escargot_option( 'hero_tagline_2', 'Florence, IT' ) ); ?></span></p>
		</div>
	</div>
</div>
