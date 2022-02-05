<?php
/**
 * Ekiline Block Editor.
 *
 * @package ekiline
 *
 * @link https://developer.wordpress.org/block-editor/developers/themes/theme-support/
 */

/**
 * Block editor setup
 */
function ekiline_block_editor_setup() {

	// Alineaciones a todo lo ancho.
	add_theme_support( 'align-wide' );

	// Estilo de bloques.
	add_theme_support( 'wp-block-styles' );

	// Estilo de textos interlineado.
	add_theme_support( 'custom-line-height' );

	// Unidades de medida.
	add_theme_support( 'custom-units', 'rem', 'em', 'px' );

	// Nuevo soporte de bloques: responsive embeds.
	add_theme_support( 'responsive-embeds' );

	// Nuevo soporte de bloques: padding en bloques de grupo.
	add_theme_support( 'custom-spacing' );

	// Estilos de apoyo, llamar siempre al tema padre.
	add_theme_support( 'editor-styles' );
	$block_styles = array(
		get_template_directory_uri() . '/assets/css/bootstrap.min.css',
		get_template_directory_uri() . '/style.css',
		get_template_directory_uri() . '/assets/css/block-editor.css',
	);
	add_editor_style( $block_styles );

	// Colores base.
	$block_colors = array(
		array(
			'name'  => __( 'Primary', 'ekiline' ),
			'color' => get_option( 'b4_primary', '#007bff' ),
			'slug'  => 'primary',
		),
		array(
			'name'  => __( 'Secondary', 'ekiline' ),
			'color' => get_option( 'b4_secondary', '#6c757d' ),
			'slug'  => 'secondary',
		),
		array(
			'name'  => __( 'Success', 'ekiline' ),
			'color' => get_option( 'b4_success', '#28a745' ),
			'slug'  => 'success',
		),
		array(
			'name'  => __( 'Danger', 'ekiline' ),
			'color' => get_option( 'b4_danger', '#dc3545' ),
			'slug'  => 'danger',
		),
		array(
			'name'  => __( 'Warning', 'ekiline' ),
			'color' => get_option( 'b4_warning', '#ffc107' ),
			'slug'  => 'warning',
		),
		array(
			'name'  => __( 'Info', 'ekiline' ),
			'color' => get_option( 'b4_info', '#17a2b8' ),
			'slug'  => 'info',
		),
		array(
			'name'  => __( 'Light', 'ekiline' ),
			'color' => get_option( 'b4_light', '#f8f9fa' ),
			'slug'  => 'light',
		),
		array(
			'name'  => __( 'Dark', 'ekiline' ),
			'color' => get_option( 'b4_dark', '#343a40' ),
			'slug'  => 'dark',
		),
	);
	add_theme_support( 'editor-color-palette', $block_colors );

	// Colores degradados.
	$block_gradients = array(
		array(
			'name'     => __( 'Vivid cyan blue to vivid purple', 'ekiline' ),
			'gradient' => 'linear-gradient(135deg,rgba(6,147,227,1) 0%,rgb(155,81,224) 100%)',
			'slug'     => 'vivid-cyan-blue-to-vivid-purple',
		),
		array(
			'name'     => __( 'Vivid green cyan to vivid cyan blue', 'ekiline' ),
			'gradient' => 'linear-gradient(135deg,rgba(0,208,132,1) 0%,rgba(6,147,227,1) 100%)',
			'slug'     => 'vivid-green-cyan-to-vivid-cyan-blue',
		),
		array(
			'name'     => __( 'Light green cyan to vivid green cyan', 'ekiline' ),
			'gradient' => 'linear-gradient(135deg,rgb(122,220,180) 0%,rgb(0,208,130) 100%)',
			'slug'     => 'light-green-cyan-to-vivid-green-cyan',
		),
		array(
			'name'     => __( 'Luminous vivid amber to luminous vivid orange', 'ekiline' ),
			'gradient' => 'linear-gradient(135deg,rgba(252,185,0,1) 0%,rgba(255,105,0,1) 100%)',
			'slug'     => 'luminous-vivid-amber-to-luminous-vivid-orange',
		),
		array(
			'name'     => __( 'Luminous vivid orange to vivid red', 'ekiline' ),
			'gradient' => 'linear-gradient(135deg,rgba(255,105,0,1) 0%,rgb(207,46,46) 100%)',
			'slug'     => 'luminous-vivid-orange-to-vivid-red',
		),
	);
	add_theme_support( 'editor-gradient-presets', $block_gradients );

	$font_sizes = array(
		// WP Default.
		array(
			'name' => __( 'Small', 'ekiline' ),
			'size' => 12,
			'slug' => 'small',
		),
		array(
			'name' => __( 'Regular', 'ekiline' ),
			'size' => 16,
			'slug' => 'regular',
		),
		array(
			'name' => __( 'Medium', 'ekiline' ),
			'size' => 20,
			'slug' => 'medium',
		),
		array(
			'name' => __( 'Large', 'ekiline' ),
			'size' => 36,
			'slug' => 'large',
		),
		array(
			'name' => __( 'Huge', 'ekiline' ),
			'size' => 50,
			'slug' => 'huge',
		),
		// Bootstrap headings.
		array(
			'name' => __( 'H1', 'ekiline' ),
			'size' => 40,
			'slug' => 'heading-1',
		),
		array(
			'name' => __( 'H2', 'ekiline' ),
			'size' => 36,
			'slug' => 'heading-2',
		),
		array(
			'name' => __( 'H3', 'ekiline' ),
			'size' => 32,
			'slug' => 'heading-3',
		),
		array(
			'name' => __( 'H4', 'ekiline' ),
			'size' => 28,
			'slug' => 'heading-4',
		),
		array(
			'name' => __( 'H5', 'ekiline' ),
			'size' => 24,
			'slug' => 'heading-5',
		),
		array(
			'name' => __( 'H6', 'ekiline' ),
			'size' => 20,
			'slug' => 'heading-6',
		),
		// Bootstrap display.
		array(
			'name' => __( 'D1', 'ekiline' ),
			'size' => 100,
			'slug' => 'display-1',
		),
		array(
			'name' => __( 'D2', 'ekiline' ),
			'size' => 90,
			'slug' => 'display-2',
		),
		array(
			'name' => __( 'D3', 'ekiline' ),
			'size' => 80,
			'slug' => 'display-3',
		),
		array(
			'name' => __( 'D4', 'ekiline' ),
			'size' => 70,
			'slug' => 'display-4',
		),

	);
	add_theme_support( 'editor-font-sizes', $font_sizes );

}

add_action( 'after_setup_theme', 'ekiline_block_editor_setup' );


/**
 * Estilos de apoyo
 */
function ekiline_custom_colors_styles() {
	global $pagenow;
	if ( 'post.php' === $pagenow && get_theme_mod( 'ekiline_textarea_css' ) !== '' ) {
		$css      = get_theme_mod( 'ekiline_textarea_css' );
		$xchange  = str_replace( '}', '}.block-editor .editor-styles-wrapper', $css );
		$csstag   = '<style id="ekiline-from-custom-colors">' . $xchange . '</style>';
		$tagclean = str_replace( '.block-editor .editor-styles-wrapper</style>', '</style>', $csstag );
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $tagclean;
	}
}
add_action( 'admin_head', 'ekiline_custom_colors_styles', 100 );

/**
 * Scripts de apoyo: bootstrap.
 */
function ekiline_add_editor_scripts() {
	wp_enqueue_script( 'bootstrap-script-editor', get_template_directory_uri() . '/assets/js/bootstrap.bundle.min.js', array( 'jquery' ), '4', true );
	wp_enqueue_script( 'ekiline-layout-editor', get_template_directory_uri() . '/assets/js/ekiline.js', array( 'jquery' ), '20151226', true );
}
add_action( 'enqueue_block_editor_assets', 'ekiline_add_editor_scripts' );
