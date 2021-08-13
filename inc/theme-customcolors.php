<?php
/**
 * Custom Color CSS Bootstrap feature
 *
 * @package ekiline
 */

/**
 * 1. Customizer, controles para cada color.
 *
 * @param string $wp_customize setup control.
 */
function ekiline_custom_color_controls( $wp_customize ) {

	// Colores, agregar un panel con subsecciones: https://developer.wordpress.org/themes/customize-api/customizer-objects/.
	$wp_customize->add_panel(
		'ekiline_ThemeColors',
		array(
			'title'       => __( 'Colors', 'ekiline' ),
			'description' => __( 'Customize the Bootstrap color palette and use the CSS classes set in your design. Or adjust the colors per item.', 'ekiline' ),
			'priority'    => 30,
		)
	);

	$wp_customize->add_section(
		'colors',
		array(
			'title' => __( 'Customize the Bootstrap color palette', 'ekiline' ),
			'panel' => 'ekiline_ThemeColors',
		)
	);

	$wp_customize->add_section(
		'colors_extended',
		array(
			'title' => __( 'Customize colors per item', 'ekiline' ),
			'panel' => 'ekiline_ThemeColors',
		)
	);

	// Colores, reasignar el controlador de color de fondo.
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'background_color',
			array(
				'label'       => __( 'Background color and text', 'ekiline' ),
				'description' => '',
				'priority'    => 20,
				'section'     => 'colors_extended',
				'settings'    => 'background_color',
			)
		)
	);

	// Colores base.
	$colors = array(
		// Pagina.
		array(
			'slug'        => 'text_color',
			'default'     => '#333333',
			'label'       => '',
			'description' => '',
			'priority'    => 20,
			'section'     => 'colors_extended',
		),
		// Contenedor main.
		array(
			'slug'        => 'main_color',
			'default'     => '#f8f9fa',
			'label'       => '',
			'description' => __( 'Color on main container', 'ekiline' ),
			'priority'    => 20,
			'section'     => 'colors_extended',
		),
		// Navbar.
		array(
			'slug'        => 'menu_color',
			'default'     => '#343a40',
			'label'       => __( 'Navbar background', 'ekiline' ),
			'description' => '',
			'priority'    => 30,
			'section'     => 'colors_extended',
		),
		// footer-bar.
		array(
			'slug'        => 'fbar_color',
			'default'     => '#6c757d',
			'label'       => __( 'Bottom bar', 'ekiline' ),
			'description' => '',
			'priority'    => 40,
			'section'     => 'colors_extended',
		),
		array(
			'slug'        => 'fbartxt_color',
			'default'     => '#ffffff',
			'label'       => '',
			'description' => '',
			'priority'    => 40,
			'section'     => 'colors_extended',
		),
		array(
			'slug'        => 'fbarlks_color',
			'default'     => '#007bff',
			'label'       => '',
			'description' => '',
			'priority'    => 40,
			'section'     => 'colors_extended',
		),
		array(
			'slug'        => 'footer_color',
			'default'     => '#343a40',
			'label'       => __( 'Footer', 'ekiline' ),
			'description' => '',
			'priority'    => 40,
			'section'     => 'colors_extended',
		),
		array(
			'slug'        => 'ftext_color',
			'default'     => '#ffffff',
			'label'       => '',
			'description' => '',
			'priority'    => 40,
			'section'     => 'colors_extended',
		),
		array(
			'slug'        => 'flinks_color',
			'default'     => '#007bff',
			'label'       => '',
			'description' => '',
			'priority'    => 40,
			'section'     => 'colors_extended',
		),
		// Bootstrap.
		array(
			'slug'        => 'b4_primary',
			'default'     => '#007bff',
			'label'       => '',
			'description' => __( 'Change Bootstrap color palette and use default classes', 'ekiline' ) . __( '<br><code style="float: right;margin: 6px 4px 0px 0px;width: 90px;">*-primary</code>', 'ekiline' ),
			'priority'    => 10,
			'section'     => 'colors',
		),
		array(
			'slug'        => 'b4_secondary',
			'default'     => '#6c757d',
			'label'       => '',
			'description' => __( '<code style="float: right;margin: 6px 4px 0px 0px;width: 90px;">*-secondary</code>', 'ekiline' ),
			'priority'    => 10,
			'section'     => 'colors',
		),
		array(
			'slug'        => 'b4_success',
			'default'     => '#28a745',
			'label'       => '',
			'description' => __( '<code style="float: right;margin: 6px 4px 0px 0px;width: 90px;">*-success</code>', 'ekiline' ),
			'priority'    => 10,
			'section'     => 'colors',
		),
		array(
			'slug'        => 'b4_danger',
			'default'     => '#dc3545',
			'label'       => '',
			'description' => __( '<code style="float: right;margin: 6px 4px 0px 0px;width: 90px;">*-danger</code>', 'ekiline' ),
			'priority'    => 10,
			'section'     => 'colors',
		),
		array(
			'slug'        => 'b4_warning',
			'default'     => '#ffc107',
			'label'       => '',
			'description' => __( '<code style="float: right;margin: 6px 4px 0px 0px;width: 90px;">*-warning</code>', 'ekiline' ),
			'priority'    => 10,
			'section'     => 'colors',
		),
		array(
			'slug'        => 'b4_info',
			'default'     => '#17a2b8',
			'label'       => '',
			'description' => __( '<code style="float: right;margin: 6px 4px 0px 0px;width: 90px;">*-info</code>', 'ekiline' ),
			'priority'    => 10,
			'section'     => 'colors',
		),
		array(
			'slug'        => 'b4_light',
			'default'     => '#f8f9fa',
			'label'       => '',
			'description' => __( '<code style="float: right;margin: 6px 4px 0px 0px;width: 90px;">*-light</code>', 'ekiline' ),
			'priority'    => 10,
			'section'     => 'colors',
		),
		array(
			'slug'        => 'b4_dark',
			'default'     => '#343a40',
			'label'       => '',
			'description' => __( '<code style="float: right;margin: 6px 4px 0px 0px;width: 90px;">*-dark</code>', 'ekiline' ),
			'priority'    => 10,
			'section'     => 'colors',
		),
	);

	/**
	 * Loop, seetings and controls
	*/
	foreach ( $colors as $color ) {

		// add settings.
		$wp_customize->add_setting(
			$color['slug'],
			array(
				'default'           => $color['default'],
				'type'              => 'option',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'refresh',
			)
		);

		// add controls.
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$color['slug'],
				array(
					'label'       => $color['label'],
					'description' => $color['description'],
					'section'     => $color['section'],
					'settings'    => $color['slug'],
					'priority'    => $color['priority'],
				)
			)
		);
	}

	// Bootstrap inverse menu (checkbox).
	$wp_customize->add_setting(
		'ekiline_inversemenu',
		array(
			'default'           => '',
			'sanitize_callback' => 'ekiline_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'ekiline_inversemenu',
		array(
			'label'       => __( 'Use light navbar', 'ekiline' ),
			'description' => '',
			'section'     => 'colors_extended',
			'settings'    => 'ekiline_inversemenu',
			'type'        => 'checkbox',
			'priority'    => 30,
		)
	);

	// Input para guardar css con colores generados desde script. Save string and CSS info.
	$wp_customize->add_setting(
		'ekiline_textarea_css',
		array(
			'capability'        => 'edit_theme_options',
			'default'           => '',
			'sanitize_callback' => 'wp_strip_all_tags',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'ekiline_textarea_css',
		array(
			'type'    => 'hidden',
			'section' => 'colors_extended',
		)
	);

}
add_action( 'customize_register', 'ekiline_custom_color_controls' );

/**
 * 2. Regsitro de script auxiliar en personalizador.
 */
function ekiline_themecustomizer_js() {
	wp_enqueue_script( 'ekiline-themecustomizer', get_template_directory_uri() . '/assets/js/ekiline-themecustomizer.min.js', array( 'jquery' ), '1', true );
}
add_action( 'customize_controls_enqueue_scripts', 'ekiline_themecustomizer_js' );


/**
 * 3. Estilos especificos por objeto de la pagina:
 * main, menu, footerbar y footer.
 */
function ekiline_page_elements() {

	$ekiline_lmnt  = '';
	$ekiline_lmnt .= ( get_option( 'main_color' ) && get_option( 'main_color' ) !== '#f8f9fa' ) ? '#primary{background-color:' . get_option( 'main_color' ) . ';}' : '';
	$ekiline_lmnt .= ( get_option( 'menu_color' ) && get_option( 'menu_color' ) !== '#343a40' ) ? '#primarySiteNavigation.navbar{background-color:' . get_option( 'menu_color' ) . '!important;}' : '';

	if ( get_option( 'fbar_color' ) && get_option( 'fbar_color' ) !== '#6c757d' || get_option( 'fbartxt_color' ) && get_option( 'fbartxt_color' ) !== '#ffffff' ) {
		$fb_bg         = ( get_option( 'fbar_color' ) && get_option( 'fbar_color' ) !== '#6c757d' ) ? 'background-color:' . get_option( 'fbar_color' ) . '!important;' : '';
		$fb_tc         = ( get_option( 'fbartxt_color' ) && get_option( 'fbartxt_color' ) !== '#ffffff' ) ? 'color:' . get_option( 'fbartxt_color' ) . '!important;' : '';
		$ekiline_lmnt .= '.footer-bar{' . $fb_bg . $fb_tc . '}';
	}

	$ekiline_lmnt .= ( get_option( 'fbarlks_color' ) && get_option( 'fbarlks_color' ) !== '#007bff' ) ? '.footer-bar a{color:' . get_option( 'fbarlks_color' ) . '!important}' : '';

	if ( get_option( 'footer_color' ) && get_option( 'footer_color' ) !== '#343a40' || get_option( 'ftext_color' ) && get_option( 'ftext_color' ) !== '#ffffff' ) {
		$foo_bg        = ( get_option( 'footer_color' ) && get_option( 'footer_color' ) !== '#343a40' ) ? 'background-color:' . get_option( 'footer_color' ) . '!important;' : '';
		$foo_tc        = ( get_option( 'ftext_color' ) && get_option( 'ftext_color' ) !== '#ffffff' ) ? 'color:' . get_option( 'ftext_color' ) . '!important;' : '';
		$ekiline_lmnt .= '.site-footer{' . $foo_bg . $foo_tc . '}';
	}

	$ekiline_lmnt .= ( get_option( 'flinks_color' ) && get_option( 'flinks_color' ) !== '#007bff' ) ? '.site-footer a{color:' . get_option( 'flinks_color' ) . '!important}' : '';

	return $ekiline_lmnt;
}

/**
 * 4. Sobreescribir el uso de background image
 * Override background image.
 * https://codex.wordpress.org/Custom_Backgrounds
 * https://developer.wordpress.org/reference/functions/_custom_background_cb/
 */
function ekiline_custom_background_cb() {
	// Imagen de fondo.
	$background = set_url_scheme( get_background_image() );
	// Color de fondo.
	$color = get_option( 'background_color' );

	if ( get_theme_support( 'custom-background', 'default-color' ) === $color ) {
		$color = false;
	}

	$style = $color ? "background-color: $color;" : '';

	if ( $background ) {
		$image = ' background-image: url("' . esc_url_raw( $background ) . '" );';

		// Background Position.
		$position_x = get_theme_mod( 'background_position_x', get_theme_support( 'custom-background', 'default-position-x' ) );
		$position_y = get_theme_mod( 'background_position_y', get_theme_support( 'custom-background', 'default-position-y' ) );

		if ( ! in_array( $position_x, array( 'left', 'center', 'right' ), true ) ) {
			$position_x = 'left';
		}

		if ( ! in_array( $position_y, array( 'top', 'center', 'bottom' ), true ) ) {
			$position_y = 'top';
		}

		$position = " background-position: $position_x $position_y;";

		// Background Size.
		$size = get_theme_mod( 'background_size', get_theme_support( 'custom-background', 'default-size' ) );

		if ( ! in_array( $size, array( 'auto', 'contain', 'cover' ), true ) ) {
			$size = 'auto';
		}

		$size = " background-size: $size;";

		// Background Repeat.
		$repeat = get_theme_mod( 'background_repeat', get_theme_support( 'custom-background', 'default-repeat' ) );

		if ( ! in_array( $repeat, array( 'repeat-x', 'repeat-y', 'repeat', 'no-repeat' ), true ) ) {
			$repeat = 'repeat';
		}

		$repeat = " background-repeat: $repeat;";

		// Background Scroll.
		$attachment = get_theme_mod( 'background_attachment', get_theme_support( 'custom-background', 'default-attachment' ) );

		if ( 'fixed' !== $attachment ) {
			$attachment = 'scroll';
		}

		$attachment = " background-attachment: $attachment;";

		$style .= $image . $position . $size . $repeat . $attachment;

	}

	return 'body.custom-background{' . trim( $style ) . '}';
}

/**
 * 5. incluir los estilos CSS de Customizer
 * https://developer.wordpress.org/reference/functions/wp_custom_css_cb/
 * https://codex.wordpress.org/Function_Reference/is_customize_preview
 * En caso de ocuparlo, se cancela el uso de la etiqueta.
 */
function ekiline_custom_css_cb() {
	// 1) remover los estilos css que se modifican en customizer para agruparlos en una sola cadena.
	remove_action( 'wp_head', 'wp_custom_css_cb', 101 );
	// 2) y permitir su ejecución solo en el preview.
	if ( is_customize_preview() ) {
		add_action( 'wp_head', 'wp_custom_css_cb', 101 );
	}
	// 3) Obtener los estilos de customizer e imprimir.
	$custom_styles = wp_get_custom_css();
	if ( $custom_styles && ! is_customize_preview() ) :
		return $custom_styles;
	endif;
}


/**
 * Integrar todos los estilos css en wp_head.
 * https://developer.wordpress.org/reference/functions/wp_add_inline_style/
 */
function ekiline_get_all_styles() {
	$group_styles = '';
	// de mi script js (1&2).
	$group_styles .= get_theme_mod( 'ekiline_textarea_css' );
	// de los elementos (3).
	$group_styles .= ekiline_page_elements();
	// de custom background (4).
	$group_styles .= ekiline_custom_background_cb();
	// de custom CSS (5).
	$group_styles .= ekiline_custom_css_cb();
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo $group_styles;
}
add_action( 'group_inline_css', 'ekiline_get_all_styles', 5 );
