<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package ekiline
 */

/**
 * Mejora, breadcrumb como widgtet.
 * Referencia: https://codex.wordpress.org/Widgets_API
 * Referencia: https://www.cssigniter.com/extending-wordpress-core-3rd-party-widgets/
 */
class Ekiline_Basic_Breadcrumb extends WP_Widget {

	/**
	 * Setup de widget
	 */
	public function __construct() {
		$widget_ops = array(
			'classname'   => 'Ekiline_Basic_Breadcrumb',
			'description' => __( 'Show breadcrumb module (Ekiline)', 'ekiline' ),
		);
		parent::__construct( 'Ekiline_Basic_Breadcrumb', __( 'Breadcrumb Nav', 'ekiline' ), $widget_ops );
	}

	/**
	 * Contenido de widget
	 *
	 * @param array $args envelope of item.
	 * @param array $instance the widget.
	 */
	public function widget( $args, $instance ) {

		/**
		 * Para sobrescribir un widget, en este caso ekiline, agrega opciones css y tipo de mestreo,
		 * entonces es necesario llamar un widget, obteniendo su id y llamando el dato que necesitamos.
		 */
		if ( isset( $args['widget_id'] ) ) {
			global $wp_registered_widgets;
			$widget_id  = $args['widget_id'];
			$widget_obj = $wp_registered_widgets[ $widget_id ];
			$widget_opt = get_option( $widget_obj['callback'][0]->option_name );
			$widget_num = $widget_obj['params'][0]['number'];
			$css_style  = ( ! isset( $widget_opt[ $widget_num ]['css_style'] ) ) ? '' : $widget_opt[ $widget_num ]['css_style'];
			$args       = array(
				'before_widget' => '<nav id="' . $widget_id . '" class="' . $css_style . ' widget ' . $widget_id . '">',
				'after_widget'  => '</nav>',
			);
		}
		// Widget con nuevos datos.
		echo wp_kses_post( $args['before_widget'] );
		echo wp_kses_post( ekiline_create_breadcrumb() );
		echo wp_kses_post( $args['after_widget'] );
	}

	/**
	 * Opciones en admin
	 *
	 * @param array $instance The widget options.
	 */
	public function form( $instance ) {
		// outputs the options form on admin.
		return $instance;
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options.
	 * @param array $old_instance The previous options.
	 *
	 * @return array function has no return statement.
	 */
	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved.
		return $new_instance;
	}
}

/**
 * Register widget
 */
function ekiline_breadcrumb_register_widgets() {
	register_widget( 'Ekiline_Basic_Breadcrumb' );
}
add_action( 'widgets_init', 'ekiline_breadcrumb_register_widgets' );

/**
 * Breadcrumb elements
 */
function ekiline_create_breadcrumb() {

	if ( is_home() || is_front_page() ) {
		return;
	}
	// Comienzo de breadcrumb. Breadcrumb start.
	$breadcrumb = '<li class="breadcrumb-item home"><a href="' . home_url() . '"> ' . __( 'Home', 'ekiline' ) . ' </a></li><!--.home-->';

	if ( is_singular() ) {

		if ( is_page() ) {
			/**
			 * Si es pagina y tiene herencia, padres.
			 * info: https://wordpress.stackexchange.com/questions/140362/wordpress-breadcrumb-depth
			 */

			// 1) Se llama la variable global para hacer un loop de paginas.
			global $post;
			// 2) confirmamos si tiene herencia, si no brinca al final (3).
			if ( $post->post_parent ) {
				// Se llama al padre de esta página.
				$parent_id = $post->post_parent;
				// Establezco mi variable para crear una lista de las paginas superirores.
				$breadcrumbs = array();
				// Doy formato los items de este array.
				while ( $parent_id ) {
					$page          = get_page( $parent_id );
					$breadcrumbs[] = '<li class="breadcrumb-item post-parent"><a href="' . get_permalink( $page->ID ) . '">' . get_the_title( $page->ID ) . '</a></li><!--.post-parent-->';
					$parent_id     = $page->post_parent;
				}
				// Organizo el array para que el orden sea inverso.
				$breadcrumbs = array_reverse( $breadcrumbs );
				// Creo un loop por cada loop.
				foreach ( $breadcrumbs as $crumb ) {
					$breadcrumb .= $crumb;
				}
				// Cierro el HTML. Continua breadcrumb.
				$breadcrumb .= '<li class="breadcrumb-item post-childB">';
			} else {
				// 3) Final de loop. Continua breadcrumb.
				$breadcrumb .= '<li class="breadcrumb-item post-childA">';
			}
		}

		if ( is_single() && ! is_attachment() ) {

			global $post;
			// En caso de woocommerce: product_cat.
			$find_type = 'category';
			if ( class_exists( 'woocommerce' ) ) {
				if ( is_product() ) {
					$find_type = 'product_cat';
				}
			}
			$terms   = get_the_terms( $post->ID, $find_type );
			$getcats = '';

			if ( $terms && ! is_wp_error( $terms ) ) {
				$cat_links = array();
				foreach ( $terms as $term ) {
					// Link por medio de id.
					$category_link = get_term_link( $term->term_id, $find_type );
					// Link para categorias.
					$cat_links[] = '<a href="' . esc_url( $category_link ) . '">' . $term->name . '</a>';
				}
				$getcats = join( ',', $cat_links );
			}

			$count_cat = count( $terms );
			$more_cats = $terms[0]->name . ' +' . ( $count_cat - '1' );

			if ( $count_cat > '1' ) {
				// Continua breadcrumb. Agregar popover.
				$addcats = '<a href="#has-categories" data-bs-toggle="popover" data-bs-html="true" data-bs-content="' . esc_html( $getcats ) . '">' . $more_cats . '</a>';
			} else {
				$addcats = $getcats;
			}

			// Finaliza breadcrumb.
			$breadcrumb .= '<li class="breadcrumb-item single-category">' . $addcats . '</li><!--.single-category--><li class="breadcrumb-item single-category">';
		}

		if ( is_attachment() ) {
			$attach_post = get_post( get_the_ID() );
			$attach_name = get_the_title( $attach_post->post_parent );
			$find_this   = get_search_link( $attach_name );
			// Continua breadcrumb.
			$breadcrumb .= '<li class="breadcrumb-item single-attachment"><a href="' . $find_this . '" rel="search">' . $attach_name . '</a></li><!--.single-attachment--><li class="breadcrumb-item single-category-child">';
		}

		// en caso de no tener titulo.
		if ( ! get_the_title() || is_attachment() ) {
			$breadcrumb .= __( '&not;&not;', 'ekiline' ) . '<!--no-title--></li><!--.single-category-child.post-child-->';
		} else {
			$breadcrumb .= the_title( '', '</li><!--.single-category-child.post-child-->', false );
		}
	}

	if ( is_archive() ) {
		// Referencia: https://developer.wordpress.org/reference/functions/get_the_archive_title/.
		$title = '';
		if ( is_category() ) {

			$catname = single_term_title( '', false );
			$catid   = get_cat_ID( $catname );
			// diferenciar si hay categorías padre.
			// Referencia: https://wordpress.stackexchange.com/questions/11267/check-is-category-parent-or-not-from-its-id/.
			$catobj = get_category( $catid );
			// auxiliar para mostrar solo el padre.
			$parentobj = $catobj->parent;

			if ( $catobj->category_parent > 0 ) {
				// este muestra toda una lista.
				$breadcrumb .= '<li class="breadcrumb-item category-parent">' . get_category_parents( $parentobj, true, '</li><!--.category-parent--><li class="breadcrumb-item category-child">' ) . $catname;
			} else {
				$breadcrumb .= '<li class="breadcrumb-item category-child">' . get_category_parents( $catid, false, '' );
			}
		} elseif ( is_tag() ) {
			$title = '<li class="breadcrumb-item tag">' . single_tag_title( '', false );
		} elseif ( is_author() ) {
			$title = '<li class="breadcrumb-item author"><span class="vcard">' . get_the_author() . '</span>';
		} elseif ( is_year() ) {
			$title = '<li class="breadcrumb-item year">' . get_the_date( 'Y' );
		} elseif ( is_month() ) {
			$title = '<li class="breadcrumb-item month">' . get_the_date( 'F Y' );
		} elseif ( is_day() ) {
			$title = '<li class="breadcrumb-item day">' . get_the_date( 'F j, Y' );
		} elseif ( is_post_type_archive() ) {
			$title = '<li class="breadcrumb-item ptArchive">' . post_type_archive_title( '', false );
		} elseif ( is_tax() ) {
			$tax   = get_taxonomy( get_queried_object()->taxonomy );
			$title = '<li class="breadcrumb-item tax">' . single_term_title( '', false );
		}

		$breadcrumb .= $title . '</li><!--.category-child.tag.author.year.month.day.ptArchive.tax-->';

	}

	if ( is_search() ) {
		$breadcrumb .= '<li class="breadcrumb-item search">' . __( 'Search ', 'ekiline' ) . '</li><!--.search-->';
	}

	if ( is_404() ) {
		$breadcrumb .= '<li class="breadcrumb-item 404">' . __( 'Not found ', 'ekiline' ) . '</li><!--.404-->';
	}

	echo wp_kses_post( '<ul class="breadcrumb">' . $breadcrumb . '</ul>' );
}
