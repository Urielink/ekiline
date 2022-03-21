<?php
/**
 * Template part for displaying posts.
 *
 * Nota: Divido el contenido en modulos para facilitar la personalizacion y mantenga compatibilidad con jetpack o woocommerce.
 * Note: I divide the content into modules to facilitate customization and maintain compatibility with jetpack or woocommerce.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 * @link ref https://developer.wordpress.org/files/2014/10/Screenshot-2019-01-23-00.20.04.png
 *
 * @package ekiline
 */

?>

<?php
$ekiline_post_style = 'article';

if ( is_front_page() ) {
	$ekiline_post_style = 'frontpage';
}

if ( is_page() && ! get_theme_mod( 'ekiline_show_meta' ) ) {
	$ekiline_post_style = 'nometa';
}

if ( ! is_singular() ) {

	switch ( get_theme_mod( 'ekiline_column_items' ) ) {
		case '1':
			$ekiline_post_style = 'archive-wide';
			break;
		case '2':
			$ekiline_post_style = 'archive-wide-responsive';
			break;
		case '3':
			$ekiline_post_style = 'archive-responsive';
			break;
		default:
			$ekiline_post_style = 'archive';
			break;
	}

	// Card columns grid and format card option.
	if ( get_theme_mod( 'ekiline_Columns' ) === '4' ) {
		$ekiline_post_style = ( get_theme_mod( 'ekiline_column_items' ) === '4' ) ? 'card-overlay' : 'card';
	}

	// Formato personalizado para la busqueda no se afecta por columnas.
	if ( is_search() ) {
		$ekiline_post_style = 'search';
	}
}

if ( class_exists( 'woocommerce' ) ) {
	if ( is_cart() || is_checkout() || is_account_page() ) {
		$ekiline_post_style = 'user';
	}
}

get_template_part( 'template-parts/content', $ekiline_post_style );
