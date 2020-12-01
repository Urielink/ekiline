<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package ekiline
 */

?>

<article <?php post_class(); ?>>

	<header class="border-bottom pb-2">

		<?php // Page & post title. ?>
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

		<?php if ( ( get_theme_mod( 'ekiline_show_meta' ) && is_page() ) || is_single() ) { ?>
			<p class="entry-meta small m-0">
				<?php
					/* translators: %s is replaced with author link */
					printf( esc_html_x( 'Written by %s', 'post authors', 'ekiline' ), wp_kses_post( get_the_author_posts_link() ) );
				?>
				<br>
				<?php
					/* translators: %s is replaced with post date */
					printf( esc_html_x( 'Posted on %s', 'post date', 'ekiline' ), wp_kses_post( '<a href="' . get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ) . '" rel="bookmark">' . get_the_time( __( 'F j, Y', 'ekiline' ) ) . '</a>' ) );
				?>
				<br>
				<?php
					/* translators: %s is replaced with modification date */
					printf( esc_html__( 'Updated on %s', 'ekiline' ), wp_kses_post( get_the_modified_date( __( 'F j, Y', 'ekiline' ) ) ) );
				?>
				<br>
				<?php
					printf( wp_kses_post( comments_popup_link( __( 'No comments yet', 'ekiline' ), __( '1 Comment', 'ekiline' ), __( '% Comments', 'ekiline' ), 'comments-link', __( 'Comments are closed.', 'ekiline' ) ) ) );
				?>

			</p><!-- .entry-meta -->
		<?php } ?>

	</header>

	<?php the_post_thumbnail( 'full', array( 'class' => 'img-fluid' ) ); ?>

	<?php the_content(); ?>

	<?php ekiline_link_pages(); ?>

	<?php if ( get_theme_mod( 'ekiline_show_meta' ) && is_page() || is_single() ) { ?>
		<footer class="border-top pt-2">
			<p class="entry-meta small m-0">
				<?php
				if ( ! is_page() || get_the_category_list() !== '' ) {
					/* translators: %s is replaced with category title */
					printf( esc_html__( 'Categories: %s', 'ekiline' ), wp_kses_post( get_the_category_list( ', ' ) ) );
				}
				?>
				<br>
				<?php
				if ( get_the_tag_list() ) {
					/* translators: %s is replaced with tags */
					printf( esc_html__( 'Tags: %s', 'ekiline' ), wp_kses_post( get_the_tag_list( '', ', ' ) ) );
				}
				?>
			</p><!-- .entry-meta -->
		</footer>
	<?php } ?>

</article><!-- #post-## -->
