<?php
/**
 * Template part for displaying archive posts as card.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package ekiline
 */

?>

<article <?php post_class( 'mb-3' ); ?>>

	<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
		<?php the_post_thumbnail( 'medium', array( 'class' => 'img-fluid card-img-top' ) ); ?>
	</a>

	<div class="card-body">

		<?php the_title( '<h2 class="entry-title card-title"><a href="' . get_the_permalink() . '" title="' . get_the_title() . '">', '</a></h2>' ); ?>

		<?php the_content(); ?>

	</div>

	<footer class="card-footer">
		<p class="entry-meta small my-0">
			<?php
			if ( ! is_page() || get_the_category_list() !== '' ) {
				// Post format.
				( get_post_format() ) ? printf( '<span class="badge badge-secondary">%1$s</span><br>', esc_html( get_post_format() ) ) : '';
				/* translators: %s is replaced with category title */
				printf( esc_html__( 'Categories: %s', 'ekiline' ), wp_kses_post( get_the_category_list( ', ' ) ) );
			}
			?>
		</p><!-- .entry-meta -->
	</footer><!-- .entry-footer -->

</article><!-- #post-## -->
