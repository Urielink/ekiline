<?php
/**
 * Navegacion principal || Default menu
 *
 * @package ekiline
 */

/**
 * Agregar logotipo a menu
 * Adding logo image to navbar-brand:
 */
function ekiline_logo_theme() {
	// Variables de logotipo.
	$brand_icon = get_theme_mod( 'ekiline_minilogo' );
	$brand_hor  = wp_get_attachment_image_url( get_theme_mod( 'ekiline_logo_max' ), 'medium' );

	if ( $brand_hor && ! $brand_icon ) {
		echo '<img class="img-fluid" src="' . esc_url( $brand_hor ) . '" alt="' . esc_html( get_bloginfo( 'name' ) ) . '" loading="lazy"/>';
	} elseif ( ! $brand_hor && $brand_icon ) {
		echo '<img class="brand-icon" src="' . esc_url( get_site_icon_url() ) . '" alt="' . esc_html( get_bloginfo( 'name' ) ) . '" height="32" width="32" loading="lazy"/>
			' . esc_html( get_bloginfo( 'name' ) );
	} elseif ( $brand_hor && $brand_icon ) {
		echo '
		<img class="img-fluid d-none d-md-block" src="' . esc_url( $brand_hor ) . '" alt="' . esc_html( get_bloginfo( 'name' ) ) . '" loading="lazy"/>
		<span class="d-block d-md-none">
			<img class="brand-icon" src="' . esc_url( get_site_icon_url( '150' ) ) . '" alt="' . esc_html( get_bloginfo( 'name' ) ) . '" height="32" width="32" loading="lazy"/>
			' . esc_html( get_bloginfo( 'name' ) ) . '
		</span>';
	} else {
		echo esc_html( get_bloginfo( 'name' ) );
	}
}

/**
 * Todos los menus
 * Se complementa con acciones preestablecidas en customizer.php
 * Works with customizer.php
 *
 * @param string $nav_position Elegir posicion del menu.
 */
function ekiline_navbar_menu( $nav_position ) {

	// No mostrar un menu 'if(!has_nav_menu($nav_position)){return;}'.
	// No mostrar un menu vacio.
	// phpcs:ignore WordPress.Arrays.ArrayDeclarationSpacing.AssociativeArrayFound
	if ( false === wp_nav_menu( array( 'theme_location' => $nav_position, 'echo' => false ) ) ) {
		return;
	}

	// Invertir color (class css).
	$nav_inverse = ( true === get_theme_mod( 'ekiline_inversemenu' ) ) ? 'navbar-light bg-light ' : 'navbar-dark bg-dark ';

	// Clase CSS auxiliar alineación de items, transformar a header.
	$nav_align  = '';
	$nav_head   = '';
	$nav_help   = '';
	$modal_css  = '';
	$nav_action = '';

	// Variables para boton modal.
	$datatoggle = 'collapse';
	$datatarget = $nav_position . 'NavMenu';
	$expand     = 'navbar-expand-md ';
	$toggle_btn = 'navbar-toggler collapsed';

	// Variables por cada tipo de menu: configurcion y distribucion de menu.
	$actions = get_theme_mod( 'ekiline_' . $nav_position . 'menuSettings' );
	$styles  = get_theme_mod( 'ekiline_' . $nav_position . 'menuStyles' );

	// Clases css por configuración de menu.
	if ( '0' === $actions ) {
		$nav_action = 'static-top';
	} elseif ( '1' === $actions ) {
		$nav_action = 'fixed-top';
	} elseif ( '2' === $actions ) {
		$nav_action = 'fixed-bottom';
	} elseif ( '3' === $actions ) {
		$nav_action = 'fixed-top navbar-sticky';
	}

	// Clases css por estilo de menu.
	switch ( $styles ) {
		case 0:
			$nav_align = ' mr-auto';
			break;
		case 1:
			$nav_align = ' ml-auto';
			break;
		case 2:
			$nav_help  = ' justify-content-md-center';
			$nav_align = ' justify-content-md-center';
			$nav_head  = ' flex-md-column';
			break;
		case 3:
			$nav_help  = ' justify-content-md-between w-100';
			$nav_align = ' justify-content-md-between w-100';
			$nav_head  = ' flex-md-column';
			break;
		case 4:
			$nav_help  = ' nav-scroller show';
			$nav_align = ' nav flex-row';
			$nav_head  = ' flex-md-column';
			break;
		case 5:
			$nav_help  = ' offcanvas ' . $nav_inverse;
			$nav_align = ' ml-auto';
			break;
		case 6:
			$nav_help = ' order-first';
			$expand   = ' ';
			break;
		case 7:
			$modal_css = 'modal fade';
			break;
		case 8:
			$modal_css = 'modal fade move-from-bottom';
			break;
		case 9:
			$modal_css = 'modal fade left-aside';
			break;
		case 10:
			$modal_css = 'modal fade right-aside';
			break;
	}

	// Clases css para mostrar el boton del modal.
	if ( $styles >= '7' ) {
		$expand     = ' ';
		$datatoggle = 'modal';
		$datatarget = $nav_position . 'NavModal';
		$toggle_btn = 'modal-toggler navbar-toggler collapsed';
	}

	// Clases reunidas para <nav>.
	$nav_class_css = 'navbar ' . $nav_inverse . $nav_position . '-navbar ' . $expand . $nav_action;

	// Clases reunidas para .navbar-collapse.
	$collapse_css = 'collapse navbar-collapse ' . $nav_help;

	?>

	<header id="<?php echo esc_attr( $nav_position ); ?>SiteNavigation"  class="<?php echo esc_attr( $nav_class_css ); ?>">

		<div class="container<?php echo esc_attr( $nav_head ); ?>">

			<a class="navbar-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>"><?php ekiline_logo_theme(); ?></a>

			<?php if ( get_bloginfo( 'description' ) ) { ?>
			<span class="navbar-text d-none d-md-block site-description"><?php echo esc_html( get_bloginfo( 'description' ) ); ?></span>
			<?php } ?>

			<?php dynamic_sidebar( 'navbar-w1' ); ?>

			<button class="<?php echo esc_attr( $toggle_btn ); ?>" type="button" data-toggle="<?php echo esc_attr( $datatoggle ); ?>" data-target="#<?php echo esc_attr( $datatarget ); ?>" aria-label="<?php esc_attr_e( 'Toggle navigation' , 'ekiline' ); ?>">
				<span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
			</button>

		<?php if ( $styles <= '6' ) { ?>

			<div id="<?php echo esc_attr( $datatarget ); ?>" class="<?php echo esc_attr( $collapse_css ); ?>">

			<?php if ( '5' === $styles ) { ?>
				<button class="<?php echo esc_attr( $toggle_btn ); ?>" type="button" data-toggle="<?php echo esc_attr( $datatoggle ); ?>" data-target="#<?php echo esc_attr( $datatarget ); ?>" aria-label="<?php esc_attr_e( 'Toggle navigation' , 'ekiline' ); ?>">
					<span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
				</button>
			<?php } ?>

			<?php
			/* AUXILIAR, en caso de restringir la profundidad: 'depth' => 2 */
			wp_nav_menu(
				array(
					'menu'            => $nav_position,
					'theme_location'  => $nav_position,
					'container'       => '',
					'container_class' => '',
					'container_id'    => '',
					'menu_class'      => 'navbar-nav' . $nav_align,
					'menu_id'         => $nav_position . 'MenuLinks',
					'fallback_cb'     => 'ekiline_nav_fallback',
					'walker'          => new Ekiline_Nav_Menu(),
				)
			);

			dynamic_sidebar( 'navbar-w2' );

			?>

			</div>

		<?php } ?>


		</div><!-- .container -->

	</header><!-- .site-navigation -->

	<?php
	if ( $styles >= '7' ) {
			ekiline_modal_menu_bottom( $nav_position );
	}
	?>

	<?php

}

/**
 * Fragmento para crear un menu con madal
 *
 * @param string $nav_position Elegir posicion del menu.
 */
function ekiline_modal_menu_bottom( $nav_position ) {
	/* tipos de animacion: .zoom, .newspaper, .move-horizontal, .move-from-bottom, .unfold-3d, .zoom-out, .left-aside, .right-aside */
	$modal_id  = $nav_position . 'NavModal';
	$modal_css = '';
	switch ( get_theme_mod( 'ekiline_' . $nav_position . 'menuStyles' ) ) {
		case 7:
			$modal_css = 'modal fade modal-nav';
			break;
		case 8:
			$modal_css = 'modal fade move-from-bottom modal-nav';
			break;
		case 9:
			$modal_css = 'modal fade left-aside modal-nav';
			break;
		case 10:
			$modal_css = 'modal fade right-aside modal-nav';
			break;
	}
	?>

<div id="<?php echo esc_attr( $modal_id ); ?>" class="<?php echo esc_attr( $modal_css ); ?>" tabindex="-1" role="dialog" aria-labelledby="navModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header d-none">
				<strong class="modal-title" id="navModalLabel"><?php echo esc_html( get_bloginfo( 'name', 'display' ) ); ?></strong>
				<button type="button" class="close" data-dismiss="modal" aria-label="<?php esc_attr_e( 'Close' , 'ekiline' ); ?>">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body navbar-light bg-light">

				<div class="btn-group float-right">
					<button type="button" class="modal-resize btn btn-sm btn-outline-secondary" aria-label="<?php esc_attr_e( 'Modal size' , 'ekiline' ); ?>">
						<span>&leftarrow;</span>
						<span>&rightarrow;</span>
					</button>
					<button type="button" class="navbar-toggler m-0 btn btn-sm btn-outline-secondary" data-dismiss="modal" aria-label="<?php esc_attr_e( 'Close' , 'ekiline' ); ?>">
						<span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
					</button>
				</div>

				<div class="navbar p-0 w-100">

				<?php if ( get_bloginfo( 'description' ) ) { ?>
					<span class="navbar-text site-description"><?php echo esc_html( get_bloginfo( 'description' ) ); ?></span>
				<?php } ?>

				<?php
				/* AUXILIAR, en caso de restringir la profundidad: 'depth' => 2 */
				wp_nav_menu(
					array(
						'menu'            => $nav_position,
						'theme_location'  => $nav_position,
						'container'       => 'div',
						'container_class' => 'navbar-collapse collapse show',
						'container_id'    => '',
						'menu_class'      => 'navbar-nav',
						'menu_id'         => 'modal-menu',
						'fallback_cb'     => 'ekiline_nav_fallback',
						'walker'          => new Ekiline_Nav_Menu(),
					)
				);

				dynamic_sidebar( 'navbar-w1' );

				?>
				</div>

			</div>
			<!-- <div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div> -->
		</div>
	</div>
</div><!-- #<?php echo esc_attr( $modal_id ); ?> -->

	<?php
}
/**
 * Se invoca debajo de nav, pero puede necesitarse en la parte baja del sitio
 * add_action( 'wp_footer', 'ekiline_modal_menu_bottom', 0, 1 );
 */

/**
 * En caso de no existir un menu, default.
 */
function ekiline_nav_fallback() {
	if ( is_user_logged_in() ) {
		$link = '/wp-admin/nav-menus.php';
		$text = __( 'Assign a menu!', 'ekiline' );
	} else {
		$link = '/wp-login.php';
		$text = __( 'Login', 'ekiline' );
	}
	$link = home_url( $link );
	?>

	<ul id="SetNavMenu" class="navbar-nav">
		<li class="nav-item">
			<a class="nav-link" href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $text ); ?></a>
		</li>
	</ul>
	<div class="navbar-text ml-auto">
		<a target="_blank" class="btn btn-sm btn-outline-warning" href="<?php echo esc_url( 'https://ekiline.com/docs/' ); ?>"><?php esc_html_e( 'Theme help', 'ekiline' ); ?></a>
	</div>

	<?php
} // ekiline_nav_fallback


/**
 * Agregar a wp_body_open, menu a la pagina, en la parte superior.
 * Add nav at top of page.
 */
function ekiline_top_navbar() {
	ekiline_navbar_menu( 'primary' );
}
add_action( 'wp_body_open', 'ekiline_top_navbar', 0 );
