<?php
/**
 * Template part for displaying posts in archive.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package ekiline
 */

?>

<article <?php post_class( 'd-flex flex-md-column-reverse border-bottom py-3' ); ?>>

	<header class="flex-grow-1">

		<?php // Archive, list titles. ?>
		<?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_the_permalink() ) . '" title="' . get_the_title() . '">', '</a></h2>' ); ?>

		<p class="d-none d-sm-block">
			<?php the_content(); ?>
		</p>

	</header>

	<?php if ( has_post_thumbnail() ) { ?>

	<a class="col-6 order-lg-2 ms-3 ms-md-0 col-md-12 mb-md-3" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">

		<?php
		the_post_thumbnail(
			'medium_large',
			array(
				'class' => 'w-100 img-fluid',
				'sizes' => '(max-width:576px) 150px, (max-width:768px) 250px, 1200px',
			)
		);
		?>

	</a>

	<?php } ?>

</article><!-- #post-## -->
