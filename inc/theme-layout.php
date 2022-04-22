<?php
/**
 * Orden del tema || Theme Layout
 *
 * Funciones complementarias para el personalizador.
 * Customizer complementary functions.
 *
 * @package ekiline
 */

/**
 * Ancho de layout | Layout width
 */
function ekiline_width_control() {
	$container = 'container';
	$fluid     = '-fluid';

	if ( is_front_page() || is_home() ) {
		$container .= ( true === get_theme_mod( 'ekiline_anchoHome' ) ) ? $fluid : '';
	}
	if ( is_archive() || is_category() ) {
		$container .= ( true === get_theme_mod( 'ekiline_anchoArchive' ) ) ? $fluid : '';
	}
	if ( is_singular() && ! is_front_page() ) {
		$container .= ( true === get_theme_mod( 'ekiline_anchoSingle' ) ) ? $fluid : '';
	}
	// En caso de woocommerce.
	if ( class_exists( 'woocommerce' ) ) {
		// Reset CSS "container" class.
		if ( is_woocommerce() || is_account_page() || is_cart() || is_checkout() ) {
			$container = 'container';
		}
		// Todos los tipos de contenido de woocommerce.
		if ( is_woocommerce() || is_account_page() || is_cart() || is_checkout() ) {
			$container .= ( true === get_theme_mod( 'ekiline_anchoShop' ) ) ? $fluid : '';
		}
		// En caso de que la tienda sea home.
		if ( is_woocommerce() && ( is_front_page() || is_home() ) ) {
			$container .= ( true === get_theme_mod( 'ekiline_anchoHome' ) ) ? $fluid : '';
		}
	}

	return $container;
}

/**
 * Layout sin sidebars | No sidebars layout
 *
 * @param string $width_sb_ctrl setip sidebar view.
 */
function ekiline_view_sidebar_filter( $width_sb_ctrl ) {

	// No ejecutar en admin.
	if ( is_admin() ) {
		return $width_sb_ctrl;
	}

	$opt = '';

	if ( is_front_page() || is_home() ) {
		$opt = get_theme_mod( 'ekiline_disableSbHome' );
	}

	if ( is_archive() || is_category() ) {
		$opt = get_theme_mod( 'ekiline_disableSbArchive' );
	}

	if ( is_singular() && ! is_front_page() ) {
		$opt = get_theme_mod( 'ekiline_disableSbSingle' );
	}
	// En caso de woocommerce.
	if ( class_exists( 'woocommerce' ) ) {
		// Todos los tipos de contenido de woocommerce.
		if ( is_woocommerce() || is_account_page() || is_cart() || is_checkout() ) {
			$opt = get_theme_mod( 'ekiline_disableSbShop' );
		}
		// En caso de que la tienda sea home.
		if ( is_woocommerce() && ( is_front_page() || is_home() ) ) {
			$opt = get_theme_mod( 'ekiline_disableSbHome' );
		}
	}

	switch ( $opt ) {
		case 1:
			unset( $width_sb_ctrl['sidebar-1'] );
			break;
		case 2:
			unset( $width_sb_ctrl['sidebar-2'] );
			break;
		case 3:
			unset( $width_sb_ctrl['sidebar-1'] );
			unset( $width_sb_ctrl['sidebar-2'] );
			break;
	}

	return $width_sb_ctrl;

}
add_filter( 'sidebars_widgets', 'ekiline_view_sidebar_filter' );


/**
 * 1) Orden de columnas | Columns order
 * agregar contenedor a index.php
 *
 * @param string $tag setup open/close tag.
 */
function ekiline_main_columns( $tag ) {
	if ( ! is_active_sidebar( 'sidebar-1' ) && ! is_active_sidebar( 'sidebar-2' ) ) {
		return;
	}
	// En caso de existir barras laterales agregar envoltorio.
	if ( 'open' === $tag ) {
		echo '<div id="maincolumns" class="row mx-0 ' . esc_attr( ekiline_width_control() ) . ' mx-auto px-0">';
	}
	if ( 'close' === $tag ) {
		echo '</div><!-- #maincolumns -->';
	}
}

/**
 * 2) Agregar clases CSS a cada columna index.php y sidebar.php
 *
 * @param string $css retrieve css width each column.
 */
function ekiline_sort_cols( $css ) {

	$css_main = ekiline_width_control();

	if ( is_active_sidebar( 'sidebar-1' ) || is_active_sidebar( 'sidebar-2' ) ) {

		// Sidebars.
		$sblft = is_active_sidebar( 'sidebar-1' );
		$sbrgt = is_active_sidebar( 'sidebar-2' );

		// Orden de columnas.
		$css_main  = 'col-md-6 order-md-2';
		$css_left  = ' col-md-3 order-md-1';
		$css_right = ' col-md-3 order-md-3';

		// Aparicion de columnas.
		if ( $sblft && ! $sbrgt ) {
			$css_main = 'col-md-9 order-md-2';
			$css_left = ' col-md-3 order-md-1';
		} elseif ( ! $sblft && $sbrgt ) {
			$css_main  = 'col-md-9';
			$css_right = ' col-md-3';
		}
	}
	// Imprimir.
	if ( 'main' === $css ) {
		echo esc_attr( $css_main );
	}
	if ( 'left' === $css ) {
		echo esc_attr( $css_left );
	}
	if ( 'right' === $css ) {
		echo esc_attr( $css_right );
	}
}

/**
 * 1) Vista de columnas | Columns view
 * loop_start, loop_end, podria romper la vista.
 *
 * @param string $tag setup open/close tag.
 * @param string $css if user need new css class.
 */
function ekiline_show_columns( $tag = null, $css = null ) {
	if ( is_singular() ) {
		return;
	}

	$colset     = get_theme_mod( 'ekiline_Columns' );
	$colcontain = ( '4' === $colset ) ? 'card-columns' : 'row';

	// En caso de woocommerce.
	if ( class_exists( 'woocommerce' ) ) {
		if ( is_shop() || is_product_category() || is_product_tag() ) {
			$colcontain = 'product-list-container';
		}
	}
	// En caso de agregar una clases css.
	if ( $css ) {
		$colcontain .= ' ' . $css;
	}

	if ( $colset > '0' ) {
		if ( 'open' === $tag ) {
			echo '<div id="viewcolumns" class="' . esc_attr( $colcontain ) . '">';
		}
		if ( 'close' === $tag ) {
			echo '</div><!-- #viewcolumns -->';
		}
	}
}

/**
 * 2) Agregar clase a cada post para complementar la vista.
 *
 * @param string $classes add container post class.
 */
function ekiline_show_columns_item( $classes ) {

	if ( is_singular() ) {
		return $classes;
	}

	$colset  = get_theme_mod( 'ekiline_Columns' );
	$colview = '';

	switch ( $colset ) {
		case '1':
			$colview = 'col-md-6';
			break;
		case '2':
			$colview = 'col-md-4';
			break;
		case '3':
			$colview = 'col-md-3';
			break;
		case '4':
			$colview = 'card';
			break;
	}

	$classes[] = $colview;
	return $classes;
}

add_filter( 'post_class', 'ekiline_show_columns_item' );
