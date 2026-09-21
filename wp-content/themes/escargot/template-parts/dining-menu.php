<?php
/**
 * Dining menu (home page preview with category switcher).
 *
 * @package Escargot
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$categories = escargot_menu_categories();
$bg_id      = (int) escargot_option( 'dining_bg_image', 0 );
$bg_url     = $bg_id ? wp_get_attachment_image_url( $bg_id, 'full' ) : '';

if ( empty( $categories ) ) {
	return;
}
?>
<section class="dining-menu">
	<div class="dining-menu-bg">
		<?php if ( $bg_url ) : ?>
			<img src="<?php echo esc_url( $bg_url ); ?>" alt="">
		<?php endif; ?>
	</div>

	<div class="container">
		<div class="dining-menu-header">
			<h3 data-copy data-type="words"><?php echo esc_html( escargot_option( 'dining_menu_heading', 'Our Menu' ) ); ?></h3>
		</div>

		<div class="dining-menu-content">
			<div class="dining-nav">
				<div class="dining-nav-button-wrapper">
					<button class="dining-nav-button disabled" data-dir="prev" type="button">
						<span><?php escargot_icon( 'arrow-up' ); ?></span>
					</button>
				</div>
				<div class="dining-nav-button-wrapper">
					<button class="dining-nav-button" data-dir="next" type="button">
						<span><?php escargot_icon( 'arrow-down' ); ?></span>
					</button>
				</div>
			</div>

			<div class="dining-preview">
				<?php
				$index = 0;
				foreach ( $categories as $term ) :
					$dishes     = escargot_dishes_in( $term->term_id );
					$has_groups = false;
					foreach ( $dishes as $dish ) {
						if ( '' !== escargot_dish( $dish->ID, 'escargot_dish_group' ) ) {
							$has_groups = true;
							break;
						}
					}
					$is_active = ( 0 === $index ) ? ' active' : '';
					?>
					<div class="dining-preview-card<?php echo esc_attr( $is_active ); ?>">
						<h6><?php echo esc_html( $term->name ); ?></h6>

						<?php if ( $has_groups ) : ?>
							<?php
							$groups = array();
							foreach ( $dishes as $dish ) {
								$group = escargot_dish( $dish->ID, 'escargot_dish_group' );
								if ( '' === $group ) {
									$group = __( 'Otros', 'escargot' );
								}
								$groups[ $group ][] = $dish;
							}
							foreach ( $groups as $group_name => $group_dishes ) :
								?>
								<div class="dining-preview-group">
									<div class="dining-preview-group-header">
										<span></span>
										<p class="mono"><?php echo esc_html( $group_name ); ?></p>
										<span></span>
									</div>
									<?php foreach ( $group_dishes as $dish ) : ?>
										<div class="dining-preview-item-row">
											<p>
												<?php echo esc_html( get_the_title( $dish ) ); ?>
												<?php echo esc_html( escargot_dish( $dish->ID, 'escargot_dish_portion' ) ); ?>
											</p>
											<p><?php echo esc_html( escargot_dish( $dish->ID, 'escargot_dish_price' ) ); ?></p>
										</div>
									<?php endforeach; ?>
								</div>
							<?php endforeach; ?>

						<?php else : ?>
							<?php foreach ( $dishes as $dish ) : ?>
								<div class="dining-preview-item">
									<div class="dining-preview-item-row">
										<p>
											<?php echo esc_html( get_the_title( $dish ) ); ?>
											<?php echo esc_html( escargot_dish( $dish->ID, 'escargot_dish_portion' ) ); ?>
										</p>
										<p><?php echo esc_html( escargot_dish( $dish->ID, 'escargot_dish_price' ) ); ?></p>
									</div>
									<?php $description = escargot_dish( $dish->ID, 'escargot_dish_description' ); ?>
									<?php if ( '' !== $description ) : ?>
										<p class="dining-preview-item-description"><?php echo esc_html( $description ); ?></p>
									<?php endif; ?>
								</div>
							<?php endforeach; ?>
						<?php endif; ?>

						<div class="dining-preview-footer">
							<p><?php echo esc_html( get_bloginfo( 'name' ) ); ?></p>
						</div>
					</div>
					<?php
					$index++;
				endforeach;
				?>
			</div>

			<div class="dining-minimap">
				<?php
				$index = 0;
				foreach ( $categories as $term ) :
					$thumb = escargot_category_image_url( $term->term_id );
					?>
					<div class="dining-minimap-item<?php echo ( 0 === $index ) ? ' active' : ''; ?>">
						<div class="dining-minimap-img">
							<?php if ( $thumb ) : ?>
								<img src="<?php echo esc_url( $thumb ); ?>" alt="<?php echo esc_attr( $term->name ); ?>">
							<?php endif; ?>
						</div>
						<p><?php echo esc_html( $term->name ); ?></p>
					</div>
					<?php
					$index++;
				endforeach;
				?>
			</div>
		</div>
	</div>
</section>
