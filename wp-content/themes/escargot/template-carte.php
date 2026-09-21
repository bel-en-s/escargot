<?php
/**
 * Template Name: Carte
 *
 * @package Escargot
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$pid     = get_the_ID();
$heading = escargot_meta( $pid, 'escargot_carte_heading', 'A Menu Guided by Taste and Time' );
$tag_1   = escargot_meta( $pid, 'escargot_carte_tag_1', 'The Carte' );
$tag_2   = escargot_meta( $pid, 'escargot_carte_tag_2', 'Selected Courses' );

$categories = escargot_menu_categories();
?>

<section class="menu-hero">
	<div class="container">
		<h2 data-copy data-type="words" data-animate="false" data-delay="0.85"><?php echo esc_html( $heading ); ?></h2>
	</div>

	<div class="section-footer">
		<p class="sm" data-copy data-type="lines" data-animate="false" data-delay="1.1"><?php echo esc_html( $tag_1 ); ?></p>
		<p class="sm" data-copy data-type="lines" data-animate="false" data-delay="1.2"><?php echo esc_html( $tag_2 ); ?></p>
	</div>
</section>

<section class="menu-list">
	<div class="container">
		<?php foreach ( $categories as $term ) : ?>
			<?php
			$dishes   = escargot_dishes_in( $term->term_id );
			$tagline  = trim( (string) $term->description );
			?>
			<div class="menu-category">
				<div class="menu-category-header">
					<h3 data-copy data-type="words"><?php echo esc_html( $term->name ); ?></h3>
					<?php if ( '' !== $tagline ) : ?>
						<p class="md" data-copy data-type="lines"><?php echo esc_html( $tagline ); ?></p>
					<?php endif; ?>
				</div>

				<div class="menu-grid">
					<?php
					$item_index = 0;
					foreach ( $dishes as $dish ) :
						$portion = escargot_dish( $dish->ID, 'escargot_dish_portion' );
						$group   = escargot_dish( $dish->ID, 'escargot_dish_group' );
						$desc    = escargot_dish( $dish->ID, 'escargot_dish_description' );
						$price   = escargot_dish( $dish->ID, 'escargot_dish_price' );
						$alt     = ( 0 !== $item_index % 2 ) ? ' alt' : '';

						$middle = ( '' !== $desc ) ? $desc : ( ( '' !== $group && '' !== $portion ) ? $portion : '' );
						?>
						<div class="menu-grid-card<?php echo esc_attr( $alt ); ?>">
							<div class="menu-grid-card-top">
								<h6><?php echo esc_html( get_the_title( $dish ) ); ?></h6>
								<?php if ( '' !== $portion && '' === $group ) : ?>
									<p class="mono"><?php echo esc_html( $portion ); ?></p>
								<?php endif; ?>
							</div>

							<?php if ( '' !== $middle ) : ?>
								<p><?php echo esc_html( $middle ); ?></p>
							<?php endif; ?>

							<p class="menu-grid-card-price"><?php echo esc_html( $price ); ?></p>
						</div>
						<?php
						$item_index++;
					endforeach;
					?>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</section>

<?php get_template_part( 'template-parts/testimonials' ); ?>

<?php get_footer(); ?>
