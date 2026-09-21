<?php
/**
 * Header.
 *
 * @package Escargot
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<noscript><style>.preloader{display:none !important}</style></noscript>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php if ( ! is_front_page() ) : ?>
	<?php get_template_part( 'template-parts/nav' ); ?>
<?php endif; ?>

<div class="page" id="page">
	<div class="page-wrapper" id="page-wrapper">
