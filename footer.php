<?php
/**
 * Footer
 *
 * @link https://developer.wordpress.org/reference/functions/get_footer/
 *
 * @package ekiline
 */

?>

<?php if ( is_active_sidebar( 'footer-w2' ) ) { ?>
	<div class="footer-bar bg-secondary text-white">
		<div class="pt-4 pb-2 container">
			<?php
				dynamic_sidebar( 'footer-w2' );
			?>
		</div>
	</div><!-- .footer-bar -->
<?php } ?>


<footer class="site-footer pt-4 pb-2 bg-dark text-white">
	<div class="container">
		<?php
			dynamic_sidebar( 'footer-w1' );
		?>
	<p id="authorship">
		<?php
		/* translators: %1$s is replaced with date */
			printf( esc_html__( '&copy; Copyright %1$s. ', 'ekiline' ), esc_attr( date( 'Y' ) . ' ' . get_bloginfo( 'name', 'display' ) ) );
		?>
		<span class="signature">
			<?php
			if ( ! get_theme_mod( 'ekiline_custom_signature' ) ) {
				/* translators: %1$s and %2$s are replaced with link url */
				printf( esc_html__( 'Proudly powered by %1$s and %2$s. ', 'ekiline' ), '<a href="' . esc_url( 'https://wordpress.org/' ) . '" target="_blank" rel="noopener">' . esc_html__( 'WordPress', 'ekiline' ) . '</a>', '<a href="' . esc_url( 'http://ekiline.com' ) . '" target="_blank" rel="noopener">' . esc_html__( 'Ekiline', 'ekiline' ) . '</a>' );
			} else {
				echo esc_html( get_theme_mod( 'ekiline_custom_signature' ) );
			}
			?>
		</span>
		<a class="smooth float-right" href="#primary"><span>&uarr;</span><?php esc_html_e( 'Back', 'ekiline' ); ?></a>
	</p>
</div>
</footer><!-- .site-footer -->

<?php wp_footer(); ?>

</body>
</html>
