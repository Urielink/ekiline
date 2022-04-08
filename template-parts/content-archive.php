<?php
/**
 * Template part for displaying posts in archive.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package ekiline
 */

?>

<article <?php post_class( 'border-bottom pb-3 mb-3' ); ?>>

	<header>

		<?php // Archive, featured image with link. ?>
		<a class="d-block mb-3"  href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
			<?php the_post_thumbnail( 'medium', array( 'class' => 'w-100 img-fluid' ) ); ?>
		</a>

		<?php // Archive, list titles. ?>
		<?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_the_permalink() ) . '" title="' . get_the_title() . '">', '</a></h2>' ); ?>

	</header>

	<?php the_content(); ?>

</article><!-- #post-## -->
