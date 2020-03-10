<?php
/** 
 * Plantilla principal del Frontend.
 * 
 * Esta es la vista principal de un sitio, aqui se mostraran cada una de sus partes.
 * El consejo es que no ocupes caracteres especiales.
 * Y organices bien tus archivos.
 * Ekiline esta hecho para tener control sobre elementos de diseno.
 * 
 * @link https://developer.wordpress.org/themes/basics/template-files/
 * 
 * @package ekiline
 * 
 */ 
get_header(); ?>

<?php mainCols('open'); ?>

<main id="primary" class="<?php orderCols('main');?>">

<?php the_archive_title( '<h1 class="page-title">', '</h1>' );?>	

<?php dynamic_sidebar( 'content-w1' );?>

<?php viewCols('open'); ?>

<?php
	/** Loop https://developer.wordpress.org/themes/basics/the-loop/ **/
	if ( have_posts() ) { 
		while ( have_posts() ) : 
			the_post();

			$postFormat = get_post_format();
			
			if ( get_theme_mod('ekiline_Columns') == 4 ){
				$postFormat = ( is_archive() || is_home() ) ? 'card' : '' ;
			}

			get_template_part( 'template-parts/content', $postFormat );	

		endwhile;	
    } else {
			get_template_part( 'template-parts/content', 'none' );	
    }	
?>

<?php viewCols('close'); ?>

<?php ekiline_archive_pagination(); ?>

<?php ekiline_pages_navigation();?>


<?php dynamic_sidebar( 'content-w2' ); ?>		

<?php
	// If comments are open or we have at least one comment, load up the comment template.
	if ( is_single() || is_page() && !is_front_page() ){
		if ( comments_open() || get_comments_number() ) :
			comments_template();
		endif;
	}
?>

</main><!-- #primary -->

<?php get_sidebar(); ?>

<?php get_sidebar('right'); ?>	

<?php mainCols('close'); ?>

<?php get_footer(); ?>