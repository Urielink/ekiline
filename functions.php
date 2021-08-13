<?php
/**
 * Functions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 * @link https://developer.wordpress.org/reference/functions/add_theme_support/
 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
 * @link https://developer.wordpress.org/themes/functionality/post-formats/
 *
 * @package ekiline
 */

/**
 * Theme setup
 */
function ekiline_setup() {
	// Traducciones // Translations can be filed in the /languages/ directory.
	load_theme_textdomain( 'ekiline', get_template_directory() . '/languages' );

	// Lector RSS // Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	// Metadatos: Titulo. // Let WordPress manage the document title.
	add_theme_support( 'title-tag' );

	// Permitir miniaturas // Enable support for Post Thumbnails on posts and pages.
	add_theme_support( 'post-thumbnails' );

	// Permitir uso de HTML5 en formularios y marcado simple. // Switch default core markup for search form, comment form, and comments.
	$html_def = array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'script', 'style' );
	add_theme_support( 'html5', $html_def );

	// Formatos de entradas // Enable support for Post Formats.
	$post_def = array( 'aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat' );
	add_theme_support( 'post-formats', $post_def );

	// Color e imagen de fondo // Set up the WordPress core custom background feature.
	// Reference: https://developer.wordpress.org/reference/functions/add_theme_support/?
	$back_def = array(
		'default-image'    => '',
		'default-color'    => 'ffffff',
		'wp-head-callback' => 'ekiline_custom_background_cb',
	);
	add_theme_support( 'custom-background', $back_def );

	// Actualizacion de widgets en el personalizador // Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	// Menú, Ekiline solo necesita uno: ekiline_navbar_menu( 'primary' ) // This theme uses ekiline_navbar_menu( 'primary' ) as one location.
	register_nav_menus(
		array(
			'primary' => esc_html__( 'Primary Menu', 'ekiline' ),
		)
	);
}
add_action( 'after_setup_theme', 'ekiline_setup' );


/**
 * Establecer el ancho de objetos (imagenes).
 *
 * @link https://codex.wordpress.org/Content_Width#Adding_Theme_Support
 * @link https://codex.wordpress.org/Function_Reference/get_intermediate_image_sizes
 * @link https://wycks.wordpress.com/2013/02/14/why-the-content_width-wordpress-global-kinda-sucks/
 *
 * Heredar la medida de /wp-admin/options-media.php.
 */
if ( ! isset( $content_width ) ) {
	$content_width = '';
}


/**
 * Registrar widgets y sus areas // Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function ekiline_widgets_init() {

	// Sidebar izquierdo y derecho. Default sidebar and right.
	register_sidebar(
		array(
			'name'          => esc_html__( 'Left sidebar', 'ekiline' ),
			'id'            => 'sidebar-1',
			'description'   => '',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Right sidebar', 'ekiline' ),
			'id'            => 'sidebar-2',
			'description'   => '',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);

	// Sidebar entre contenido y footer. Sidebar between content and footer.
	register_sidebar(
		array(
			'name'          => esc_html__( 'Bottom aside', 'ekiline' ),
			'id'            => 'footer-w2',
			'description'   => '',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		)
	);

	// Widgets en footer. Footer widgets.
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer', 'ekiline' ),
			'id'            => 'footer-w1',
			'description'   => '',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		)
	);

	// Widgets antes de todo el contenido. Widgets at top of content.
	register_sidebar(
		array(
			'name'          => esc_html__( 'Top before all content', 'ekiline' ),
			'id'            => 'toppage-w1',
			'description'   => '',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<p class="widget-title">',
			'after_title'   => '</p>',
		)
	);

	// Widgets antes y despues del contenido. Widgets before and after content.
	register_sidebar(
		array(
			'name'          => esc_html__( 'In page top content', 'ekiline' ),
			'id'            => 'content-w1',
			'description'   => '',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<p class="lead font-weight-bold widget-title">',
			'after_title'   => '</p>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'In page bottom content', 'ekiline' ),
			'id'            => 'content-w2',
			'description'   => '',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<p class="lead font-weight-bold widget-title">',
			'after_title'   => '</p>',
		)
	);

	// Widgets en el navbar. Widgets in navbar.
	register_sidebar(
		array(
			'name'          => esc_html__( 'In navbar', 'ekiline' ),
			'id'            => 'navbar-w1',
			'description'   => '',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<label class="widget-title screen-reader-text">',
			'after_title'   => '</label>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'In navbar menu', 'ekiline' ),
			'id'            => 'navbar-w2',
			'description'   => '',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<label class="widget-title screen-reader-text">',
			'after_title'   => '</label>',
		)
	);

}
add_action( 'widgets_init', 'ekiline_widgets_init' );

/**
 * Compatibilidad para wp_body_open
 * Compatibility wp_body_open
 */
if ( ! function_exists( 'wp_body_open' ) ) {
	/**
	 * Asegurar su uso WordPress anteriores a v 5.2.
	 */
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
}

/**
 * Agregar a wp_body_open, area de widgets superior
 * Add widget area at top of page.
 */
function ekiline_top_page_widget_area() {
	dynamic_sidebar( 'toppage-w1' );
}
add_action( 'wp_body_open', 'ekiline_top_page_widget_area', 1 );

/**
 * Funcion: Obtener contenido entre 2 strings.
 * Se ocupo en los estilos, y puede ser necesario posteriormente.
 * Ej: ekiline_get_string_between( $data, '[ekiline-atfcss-start]', '[ekiline-atfcss-end]' );
 *
 * @param string $string contenido.
 * @param string $start elemento de abertura.
 * @param string $end elemento de cierre.
 */
function ekiline_get_string_between( $string, $start, $end ) {
	// Verificar la existencia de strings.
	$find_start = strpos( $string, $start );
	$find_end   = strpos( $string, $end );
	// Extraer el contenido.
	if ( false !== $find_start && false !== $find_end ) {
		$extract = explode( $start, $string );
		$extract = explode( $end, $extract[1] );
		return $extract[0];
	}
}

/**
 * Estilos css, above the fold.
 * Obtener los estilos de un css e imprimirlos en el head,
 * debe aparecer al principio de cualquier css (0).
 */
function ekiline_above_fold_styles() {
	// Estilos.
	$file = get_template_directory_uri() . '/assets/css/style-atf.css';
	$file = wp_remote_get( $file );
	$data = wp_remote_retrieve_body( $file );
	// Quitar comentarios.
	$data = preg_replace( '#/\*.*?\*/#s', '', $data );
	// Quitar saltos de linea y convertir en un string.
	$data = wp_strip_all_tags( $data, true );
	$data = ( ! $data ) ? '/*' . __( 'No styles found, check file', 'ekiline' ) . '*/' : $data;
	wp_register_style( 'ekiline-atf', false, '', '2.0', 'all' );
	wp_enqueue_style( 'ekiline-atf' );
	wp_add_inline_style( 'ekiline-atf', $data );
}
add_action( 'wp_enqueue_scripts', 'ekiline_above_fold_styles', 0 );

/**
 * Estilos CSS // Javascript // Js con argumentos
 *
 * @link https://developer.wordpress.org/reference/functions/wp_enqueue_style/
 * @link https://developer.wordpress.org/reference/functions/wp_enqueue_script/
 * @link https://developer.wordpress.org/reference/functions/wp_script_add_data/
 * @link https://developer.wordpress.org/resource/dashicons/
 */
function ekiline_scripts() {
	// Estilos.
	wp_enqueue_style( 'bootstrap-5', get_template_directory_uri() . '/assets/css/bootstrap.min.css', array(), '5', 'all' );
	wp_enqueue_style( 'ekiline-style', get_stylesheet_uri(), array(), '5', 'all' );
	wp_enqueue_style( 'dashicons' );
	// Scripts.
	wp_enqueue_script( 'bootstrap-script', get_template_directory_uri() . '/assets/js/bootstrap.bundle.min.js', array( 'jquery' ), '5', true );
	wp_enqueue_script( 'ekiline-layout', get_template_directory_uri() . '/assets/js/ekiline.js', array( 'jquery' ), '20151226', true );
	// Comentarios.
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'ekiline_scripts', 1 );


/**
 * Ekiline ocupa varios modulos con estilos en linea, se agrupan en una sola cadena
 * https://digwp.com/2009/09/wordpress-action-hooks/
 * A) Dependiente de un estilo realizado como variable
 * B) Invasivo, directo con etiqueta en head
 * cada nuevo estilo en linea se agrega con: add_action( 'group_inline_css', 'new_style', 0/100 );
 */
function ekiline_group_inline_css_stored() {
	ob_start(); // inicia captura de datos.
		do_action( 'group_inline_css' ); // accion predeterminada.
		$stored_value = ob_get_contents(); // capturar los datos en una variable.
	ob_end_clean(); // finalizar la captura.
	// Quitar comentarios.
	$stored_value = preg_replace( '#/\*.*?\*/#s', '', $stored_value );
	// Quitar saltos de linea y convertir en un string.
	$stored_value = wp_strip_all_tags( $stored_value, true );
	return $stored_value; // variable.
}

/**
 * En caso de declarar como dependencia ocupar:
 * function ekiline_inline_css_handled() {
 *   wp_add_inline_style( 'ekiline-style', ekiline_group_inline_css_stored() );
 * }
 * add_action( 'wp_enqueue_scripts', 'ekiline_inline_css_handled' );
 */
function ekiline_inline_css_tag() {
	$type_attr = current_theme_supports( 'html5', 'style' ) ? ' ' : ' type="text/css" ';
	// Se declaran como estilo unico en linea.
	printf(
		'<style%1$sid="ekiline-style-inline-css">%2$s</style>' . "\n",
		wp_kses_post( $type_attr ),
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		wp_strip_all_tags( ekiline_group_inline_css_stored() )
	);
}
add_action( 'wp_head', 'ekiline_inline_css_tag', 100 );


/* Ekiline adiciones */
require get_template_directory() . '/inc/class-ekiline-nav-menu.php';
require get_template_directory() . '/inc/class-ekiline-control-multiple-select.php';
require get_template_directory() . '/inc/class-ekiline-basic-breadcrumb.php';
require get_template_directory() . '/inc/widget-options.php';
/* Ekiline modificadores de tema */
require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/theme-customcolors.php';
require get_template_directory() . '/inc/theme-navbars.php';
require get_template_directory() . '/inc/theme-layout.php';
require get_template_directory() . '/inc/theme-elements.php';
require get_template_directory() . '/inc/theme-meta.php';
require get_template_directory() . '/inc/theme-customheader.php';
/* Ekiline optimizacion */
require get_template_directory() . '/inc/theme-optimizescripts.php';
/* Ekiline Block Editor */
require get_template_directory() . '/inc/theme-blockeditor.php';
/* Ekiline info admin */
require get_template_directory() . '/inc/info.php';
/* Ekiline Compatibilidad */
require get_template_directory() . '/inc/setup-jetpack.php';
require get_template_directory() . '/inc/setup-woocommerce.php';
