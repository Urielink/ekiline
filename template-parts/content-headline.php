<?php
/**
 * Template part for displaying archive, category, author or search headline data.
 * Mostrar diversos formatos en listados, segun el tipo (pagina de entradas, categoria, entradas de autor o búsqueda).
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package ekiline
 */

?>

<?php if ( ! is_singular() && ! is_search() ) { ?>

		<h1 class="archive-title headline">
			<?php echo wp_kses_post( ( is_home() && ! is_front_page() ) ? get_the_title( get_option( 'page_for_posts', true ) ) : get_the_archive_title() ); ?>
		</h1>

		<?php if ( is_home() && ! is_front_page() ) { ?>

			<div class="headline-description">
				<?php echo wp_kses_post( get_post_field( 'post_content', get_option( 'page_for_posts' ) ) ); ?>
			</div>

		<?php } elseif ( is_category() ) { ?>

			<div class="headline-description">
				<?php echo category_description(); ?>
			</div>

		<?php } elseif ( is_author() ) { ?>
			<?php
			/**
			 * Obtener los datos del autor.
			 * https://developer.wordpress.org/reference/functions/get_the_author_meta/
			 * Nombre seleccionado, correo y url.
			 **/
			?>
			<div class="headline-description">

				<p>
					<?php echo wp_kses_post( nl2br( get_the_author_meta( 'description' ) ) ); ?>
				</p>

				<p>
					<?php echo wp_kses_post( __( 'User: ', 'ekiline' ) . get_the_author_meta( 'display_name' ) ); ?>
					<br> <?php echo wp_kses_post( __( 'Email: ', 'ekiline' ) . get_the_author_meta( 'email' ) ); ?>
					<br> <?php echo wp_kses_post( __( 'Web: ', 'ekiline' ) . get_the_author_meta( 'url' ) ); ?>
				</p>

			</div>

		<?php } ?>

<?php } ?>


<?php if ( is_search() && have_posts() ) { ?>

	<header class="entry-header">
		<h1 class="page-title">
			<?php
				/* translators: %s is replaced with search query */
				printf( esc_html__( 'Search Results for: %s', 'ekiline' ), '<span>' . get_search_query() . '</span>' );
			?>
		</h1>
		<p>
			<?php
				/* translators: %s is replaced with results */
				printf( esc_html__( '%s results found.', 'ekiline' ), esc_attr( $wp_query->found_posts ) );
			?>
		</p>
	</header><!-- .entry-header -->

	<?php get_search_form(); ?>

<?php } ?>
