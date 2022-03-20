<?php
/**
 * Template part for displaying posts in archive.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package ekiline
 */

?>

<article <?php post_class( 'd-flex flex-column-reverse flex-md-row-reverse border-bottom py-3' ); ?>>

	<header class="flex-grow-1 pt-3 pt-md-0">

		<?php // Archive, list titles. ?>
		<?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_the_permalink() ) . '" title="' . get_the_title() . '">', '</a></h2>' ); ?>

		<p>
			<?php the_content(); ?>
		</p>

	</header>

	<?php if ( has_post_thumbnail() ) { ?>

		<a class="col-md-5 me-md-3" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">

			<?php the_post_thumbnail( 'medium_large', array( 'sizes' => '(max-width:576px) 230px, (max-width:768px) 310px, 800px' ) ); ?>

		</a>

	<?php } ?>

</article><!-- #post-## -->
