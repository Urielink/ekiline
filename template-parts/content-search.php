<?php
/**
 * Template part for displaying posts in search page.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package ekiline
 */

?>

<article class="d-flex border-bottom pt-3">

	<div class="flex-grow-1">

		<?php the_title( '<h2 class="entry-title mb-0"><a href="' . get_the_permalink() . '" title="' . get_the_title() . '">', '</a></h2>' ); ?>

		<?php the_excerpt(); ?>

	</div>

	<?php if ( has_post_thumbnail() ) { ?>

		<a class="flex-shrink-0" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
			<?php the_post_thumbnail( 'thumbnail', array( 'class' => 'img-thumbnail img-fluid border-0' ) ); ?>
		</a>

	<?php } ?>

</article><!-- #post-## -->
