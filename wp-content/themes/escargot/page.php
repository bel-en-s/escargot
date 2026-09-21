<?php
/**
 * Default page template.
 *
 * @package Escargot
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<main class="default-content" style="min-height:60vh;padding:10rem 2.5rem;">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<h1><?php the_title(); ?></h1>
		<div style="margin-top:2rem;"><?php the_content(); ?></div>
		<?php
	endwhile;
	?>
</main>
<?php get_footer(); ?>
