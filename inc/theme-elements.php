<?php
/**
 * Objetos del tema || Theme objects
 *
 * Funciones complementarias para el marcado HTML.
 * HTML Markup complementary functions.
 *
 * @package ekiline
 */

/**
 * Diccionario alternativo para traducciones.
 * Template names - Localization
 *
 * @link https://developer.wordpress.org/apis/handbook/internationalization/localization/
 */
function ekiline_localize_extra_terms() {
	$words = array();
	// Nuevas plantillas / Template Name.
	$words[] = __( 'Blank page', 'ekiline' );
	$words[] = __( 'No header', 'ekiline' );
}

/**
 * Reemplazar el marcado para el enlace de leer mas
 * Read more link, custom markup.
 */
function ekiline_override_read_more_link() {
	/* translators: screenread only %s is replaced with title */
	return '<a class="more-link" href="' . esc_url( get_permalink() ) . '" aria-label="' . sprintf( esc_attr__( 'Continue reading: %s', 'ekiline' ), wp_strip_all_tags( get_the_title() ) ) . '">' . __( 'Read more', 'ekiline' ) . '</a>';
}
add_filter( 'the_content_more_link', 'ekiline_override_read_more_link' );

/**
 * Widgets en footer
 * Footer widgets
 * 12/21/20021: Disabled.
 * affect: footer.php
 *
 * @param string $widget_area identifica que sidebar se ocupa.
 */
function ekiline_count_widgets( $widget_area ) {
	// agreagar un contenedor en caso de existir más de 2 widgets, util para usar columnas de bootstrap.
	if ( is_active_sidebar( $widget_area ) ) :

		$the_sidebars   = wp_get_sidebars_widgets();
		$count_sidebars = count( $the_sidebars[ $widget_area ] );

		if ( $count_sidebars >= '2' ) {
			echo '<div class="row">';
				dynamic_sidebar( $widget_area );
			echo '</div>';
		} else {
			dynamic_sidebar( $widget_area );
		}

	endif;
}

/**
 * Manipular el titulo de listados (archive) con filtros.
 * Custom Title markup.
 *
 * @link https://developer.wordpress.org/reference/hooks/get_the_archive_title/
 *
 * @param string $title archive page title.
 */
function ekiline_archive_title( $title ) {

	if ( is_category() ) {
		$title = single_cat_title( '', false );
	} elseif ( is_tag() ) {
		$title = single_tag_title( '', false );
	} elseif ( is_author() ) {
		$title = '<span class="vcard">' . get_the_author() . '</span>';
	} elseif ( is_post_type_archive() ) {
		$title = post_type_archive_title( '', false );
	} elseif ( is_tax() ) {
		$title = single_term_title( '', false );
	}

	if ( is_home() ) {
		$title = get_bloginfo( 'name' );
	}

	return $title;

}
add_filter( 'get_the_archive_title', 'ekiline_archive_title' );


/**
 * Manipular el contenido por medio de los Bloques.
 * Funcion para seleccionar bloques con una clase asignada en editor
 * if ( ekiline_featured_blocks() ) {
 *  foreach ( ekiline_featured_blocks() as $block ) {
 *   echo render_block( $block );
 *  }
 * }
 *
 * @link https://developer.wordpress.org/reference/functions/parse_blocks/
 * @link https://developer.wordpress.org/reference/functions/render_block/
 * @link https://awhitepixel.com/blog/wordpress-gutenberg-access-parse-blocks-with-php/
 *
 * @return array with selected blocks.
 */
function ekiline_featured_blocks() {

	$blocks        = parse_blocks( get_the_content() );
	$find_block    = ekiline_search_in_array( $blocks, 'blockName', 0 );
	$choose_blocks = array();

	foreach ( $find_block as $block ) {
		if ( ! empty( $block['attrs']['className'] ) ) {
			if ( 'ekiline-featured-blocks' === $block['attrs']['className'] ) {
				$choose_blocks[] = $block;
			}
		}
	}
	return $choose_blocks;
}

/**
 * Funcion para buscar en una array multidimensional
 *
 * @link ref: https://www.php.net/manual/en/function.array-search.php
 * @param array  $array conjunto donde se va a buscar.
 * @param string $key referencia a encontrar.
 * @param string $value apuntador.
 */
function ekiline_search_in_array( $array, $key, $value ) {
	$results = array();
	if ( is_array( $array ) ) {
		// phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
		if ( isset( $array[ $key ] ) && $array[ $key ] == $value ) {
			$results[] = $array;
		}
		foreach ( $array as $subarray ) {
			$results = array_merge( $results, ekiline_search_in_array( $subarray, $key, $value ) );
		}
	}
	return $results;
}



/**
 * 1) Manipular el contenido con filtros. Markup content, add filters.
 *
 * @link https://developer.wordpress.org/reference/hooks/the_content/
 * @link https://developer.wordpress.org/reference/functions/the_excerpt/
 * @link https://wordpress.stackexchange.com/questions/38030/is-there-a-has-more-tag-method-or-equivalent
 *
 * @param string $content data.
 */
function ekiline_content_additions( $content ) {

	if ( is_singular() ) {
		return $content;
	}

	if ( is_home() || is_front_page() || is_archive() || is_search() ) {
		global $post;
		$css_class = 'more-link';
		if ( get_theme_mod( 'ekiline_Columns' ) === '4' ) {
			$css_class = 'more-link btn btn-primary btn-block mt-2';
		}
		/* translators: screenread only %s is replaced with title */
		$tittle = sprintf( esc_attr__( 'Continue reading: %s', 'ekiline' ), wp_strip_all_tags( get_the_title() ) );
		$link   = '... <a class="' . $css_class . '" href="' . esc_url( get_permalink() ) . '" aria-label="' . $tittle . '">' . $tittle . '</a>';

		if ( strpos( $post->post_content, '<!--more-->' ) ) {
			$content = $content;
		} elseif ( '' !== $post->post_excerpt ) {
			$content = $post->post_excerpt . $link;
		} else {
			$content = wp_trim_words( $content, 55, $link );
		}
	}
	return $content;

}
add_filter( 'the_content', 'ekiline_content_additions' );

/**
 * 1B) Obtener un fragmento del contenido fuera del loop
 * Get content fragment out the loop
 *
 * @link https://wordpress.stackexchange.com/questions/38030/is-there-a-has-more-tag-method-or-equivalent
 * @link https://wordpress.stackexchange.com/questions/149099/only-show-content-before-more-tag
 */
function ekiline_content_out_the_loop() {
	global $post;
	$content = $post->post_content;
	$more    = strpos( $content, '<!--more-->' );
	$excerpt = $post->post_excerpt;

	if ( $more ) {
		$content_parts = get_extended( $content );
		$content       = $content_parts['main'];
	} elseif ( $excerpt ) {
		$content = $excerpt;
	} else {
		// toma el primer parrafo solo 24 palabras y cortar.
		$content = wp_trim_words( $content, 24 );
		// Si existe un punto antes cortar.
		$punto = strpos( $content, '.' );
		if ( $punto ) {
			$content = substr( $content, 0, strpos( $content, '.' ) ) . '.';
		}
	}

	$content = strip_shortcodes( $content );

	return wp_strip_all_tags( $content );
}

/**
 * 2) agregar paginado en publicaciones paginadas
 * Add pagination to paginated content.
 *
 * @link https://developer.wordpress.org/reference/functions/wp_link_pages/
 */
function ekiline_link_pages() {

	if ( ! is_singular() ) {
		return;
	}

	$args = array(
		'before'         => '<p class="page-links border-bottom p-2 text-right"><i class="float-start">' . esc_html__( 'Continue reading: ', 'ekiline' ) . '</i>',
		'after'          => '</p>',
		'link_before'    => '<span class="btn btn-sm btn-primary">',
		'link_after'     => '</span>',
		'next_or_number' => 'number',
	);

	wp_link_pages( $args );
}

/**
 * Personalizar el formulario de busqueda.
 * Override search form markup.
 *
 * @link https://developer.wordpress.org/reference/functions/locate_template/
 */
function ekiline_search_form() {
	return locate_template( get_template_part( 'template-parts/searchform' ) );
}
add_filter( 'get_search_form', 'ekiline_search_form' );

/**
 * 1) Personalizar el formulario de proteccion de lectura.
 * Override protected content form.
 */
function ekiline_password_form() {
	global $post;
	$label   = 'pwbox-' . ( empty( $post->ID ) ? wp_rand() : $post->ID );
	$output  = '<form action="' . esc_url( home_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" class="post-password-form form-inline col-sm-8 p-4 mx-auto text-center" method="post">';
	$output .= '<p>' . __( 'This content is password protected. To view it please enter your password below:', 'ekiline' ) . '</p>';
	$output .= '<div class="form-inline mx-auto"><label for="' . $label . '">' . __( 'Password:', 'ekiline' ) . ' </label>';
	$output .= '<div class="input-group pl-md-5"><input class="form-control" name="post_password" id="' . $label . '" type="password" size="20" />';
	$output .= '<span class="input-group-append"><input class="btn btn-dark" type="submit" name="Submit" value="' . esc_attr_x( 'Enter', 'post password form', 'ekiline' ) . '" /></span></div></div></form>';
	return $output;
}
add_filter( 'the_password_form', 'ekiline_password_form' );

/**
 * Paginacion para page & single & archive
 *
 * @link https://codex.wordpress.org/Next_and_Previous_Links
 */
function ekiline_pagination() {

	if ( is_front_page() && ! is_home() || is_page() && ! get_theme_mod( 'ekiline_show_pagination' ) ) {
		return;
	}

	// En caso de woocommerce no aplica.
	if ( class_exists( 'WooCommerce' ) ) {
		if ( is_cart() || is_checkout() || is_account_page() ) {
			return;
		}
	}

	$the_pages = '';
	$prev_link = '';
	$next_link = '';
	$nav_class = '';

	if ( is_page() ) {

		$pagelist = get_pages( 'sort_column=menu_order&sort_order=asc' );
		$pages    = array();

		foreach ( $pagelist as $page ) {
			$pages[] += $page->ID;
		}

		$current = array_search( get_the_ID(), $pages, true );
		$prev_id = ( isset( $pages[ $current - 1 ] ) ) ? $pages[ $current - 1 ] : '';
		$nexr_id = ( isset( $pages[ $current + 1 ] ) ) ? $pages[ $current + 1 ] : '';

		if ( ! empty( $prev_id ) ) {
			$prev_link .= '<li class="page-item page-link"><a href="' . esc_url( get_permalink( $prev_id ) ) . '" title="' . esc_attr( get_the_title( $prev_id ) ) . '"><span>&larr;</span> ' . esc_attr( get_the_title( $prev_id ) ) . '</a></li>';
		}
		if ( ! empty( $nexr_id ) ) {
			$next_link .= '<li class="page-item page-link text-end"><a href="' . esc_url( get_permalink( $nexr_id ) ) . '" title="' . esc_attr( get_the_title( $nexr_id ) ) . '">' . esc_attr( get_the_title( $nexr_id ) ) . ' <span>&rarr;</span></a></li>';
		}
	}

	if ( is_single() ) {
		$prev_link = get_previous_post_link( '<li class="page-item page-link">%link</li>', '<span>&larr;</span> %title', true );
		$next_link = get_next_post_link( '<li class="page-item page-link text-end">%link</li>', '%title <span>&rarr;</span>', true );
	}

	// Paginacion para listados: https://codex.wordpress.org/Function_Reference/paginate_links.

	if ( is_archive() || is_home() || is_search() ) {

		$nav_class = ' d-flex justify-content-center';

		global $wp_query;
		$big = 999999999;

		$pages = paginate_links(
			array(
				'base'      => str_replace( $big, '%#%', get_pagenum_link( $big ) ),
				'format'    => '?page=%#%',
				'current'   => max( 1, get_query_var( 'paged' ) ),
				'total'     => $wp_query->max_num_pages,
				'type'      => 'array',
				'prev_next' => true,
				'prev_text' => __( '&larr; Previous', 'ekiline' ),
				'next_text' => __( 'Next &rarr;', 'ekiline' ),
			)
		);

		if ( is_array( $pages ) ) {

			$current_page = ( get_query_var( 'paged' ) === 0 ) ? 1 : get_query_var( 'paged' );

			foreach ( $pages as $i => $page ) {

				$page = str_replace( 'page-numbers', 'page-link', $page );

				if ( 1 === $current_page && 0 === $i ) {
					$prev_link .= "<li class='page-item active'>$page</li>";
				} else {
					if ( 1 !== $current_page && $i === $current_page ) {
						$prev_link .= "<li class='page-item active'>$page</li>";
					} else {
						$prev_link .= "<li class='page-item'>$page</li>";
					}
				}
			}
		}
	}

	$the_pages .= '<nav id="page-navigation" class="' . $nav_class . '" aria-label="' . esc_attr( 'Page navigation', 'ekiline' ) . '">';
	$the_pages .= '<ul class="pagination justify-content-between mb-3">';
	$the_pages .= $prev_link;
	$the_pages .= $next_link;
	$the_pages .= '</ul>';
	$the_pages .= '</nav>';

	echo wp_kses_post( $the_pages );
}

/**
 * Obtener categorias para pagina 404
 */
function ekiline_categorized_blog() {
	$all_categories = get_transient( 'ekiline_categories' );
	if ( false === $all_categories ) {
		// Array de categorias a las que pertenece cada post.
		$all_categories = get_categories(
			array(
				'fields'     => 'ids',
				'hide_empty' => 1,
				// Verificar si hay mas de una.
				'number'     => 2,
			)
		);

		// Contar el numero de categorias relacionadas en cada post.
		$all_categories = count( $all_categories );

		set_transient( 'ekiline_categories', $all_categories );
	}

	if ( $all_categories > 1 ) {
		// Mas de una categoria en ekiline_categorized_blog = true.
		return true;
	} else {
		// Solo 1 categoria en ekiline_categorized_blog = false.
		return false;
	}
}

/**
 * Eliminar los transitorios utilizados en ekiline_categorized_blog.
 */
function ekiline_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	delete_transient( 'ekiline_categories' );
}
add_action( 'edit_category', 'ekiline_category_transient_flusher' );
add_action( 'save_post', 'ekiline_category_transient_flusher' );

/**
 * Extension servir contenido sin formato por medio de url (1).
 *
 * @link https://developer.wordpress.org/reference/hooks/query_vars/.
 * @param string $vars url variables.
 */
function ekiline_themeslug_query_vars( $vars ) {
	return array( 'show' ) + $vars;
}
add_filter( 'query_vars', 'ekiline_themeslug_query_vars' );

/**
 * Extension servir contenido sin formato por medio de url (2).
 * Usar: page-or-post-url/?show=blank
 *
 * @link https://codex.wordpress.org/Plugin_API/Filter_Reference/single_template
 * @link https://codex.wordpress.org/Plugin_API/Filter_Reference/page_template
 * @param string $template cambia la plantilla pre establecida.
 */
function ekiline_serve_template( $template ) {
	global $wp;
	if ( isset( $wp->query_vars['show'] ) ) {
		if ( 'blank' === $wp->query_vars['show'] ) {
			return get_template_directory() . '/singular-blank.php';
		}
	} else {
		return $template;
	}
}
add_filter( 'single_template', 'ekiline_serve_template' );
add_filter( 'page_template', 'ekiline_serve_template' );
