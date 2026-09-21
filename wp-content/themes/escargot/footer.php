<?php
/**
 * Footer.
 *
 * @package Escargot
 */
?>
		<?php if ( ! is_front_page() ) : ?>
			<?php get_template_part( 'template-parts/footer' ); ?>
		<?php endif; ?>
	</div><!-- /.page-wrapper -->
</div><!-- /.page -->

<?php wp_footer(); ?>
</body>
</html>
