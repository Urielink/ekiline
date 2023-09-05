<?php
/**
 * Jetpack Compatibilidad / Compatibility.
 *
 * @link https://themeshaper.com/2016/01/29/jetpack-dependency-script/
 * @link https://jetpack.com/support/jetpack-for-developers/
 * @link https://jetpack.com/support/infinite-scroll/
 *
 * @package ekiline
 */

/**
 * Jetpack setup.
 */
function ekiline_jetpack_setup() {
	// Scroll infinito.
	$args_infinite_scroll = array(
		'container'      => ekiline_infinite_scroll_wrapper(),
		'render'         => 'ekiline_infinite_scroll_render',
		'footer'         => false,
		'wrapper'        => false,
		'footer_widgets' => array( 'footer-w1', 'footer-w2' ),
	);
	add_theme_support( 'infinite-scroll', $args_infinite_scroll );
}
add_action( 'after_setup_theme', 'ekiline_jetpack_setup' );

/**
 * Asignar contenedor para cada post creado.
 * Asign container to add each new post.
 */
function ekiline_infinite_scroll_wrapper() {
	$wrapper = ( get_theme_mod( 'ekiline_Columns' ) > '0' ) ? 'viewcolumns' : 'primary';
	return $wrapper;
}

/**
 * Crear el loop de publicaciones y asignar plantilla según su uso.
 * Custom render function for Infinite Scroll.
 */
function ekiline_infinite_scroll_render() {
	// en caso de woocommerce ocupar loop original de plugin.
	if ( class_exists( 'woocommerce' ) ) {
		if ( is_shop() ) {
			return;
		}
	}
	// Loop.
	while ( have_posts() ) {
		the_post();
		get_template_part( 'template-parts/content', get_post_type() );
	}
}

/**
 * Deshabilitar funciones de ekiline
 */

if ( class_exists( 'jetpack' ) ) {
	remove_action( 'wp_head', 'ekiline_meta_social', 1 );
}
