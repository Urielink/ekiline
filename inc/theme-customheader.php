<?php
/**
 * Custom Header feature
 *
 * @package ekiline
 */

/**
 * Customizer, controles para cada color.
 * Usar el contenido de home y frontpage
 *
 * @param string $wp_customize setup control.
 */
function ekiline_custom_header_controls( $wp_customize ) {

	// Colores, reasignar el controlador de color de texto.
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'header_textcolor',
			array(
				'label'       => __( 'Colors', 'ekiline' ),
				'description' => __( 'Text color', 'ekiline' ),
				'priority'    => 20,
				'section'     => 'header_image',
				'settings'    => 'header_textcolor',
			)
		)
	);

	// Colores en transaparencia y links.
	$colors = array(
		array(
			'slug'        => 'chdr_color',
			'default'     => '#000000',
			'label'       => '',
			'description' => __( 'Transparency color', 'ekiline' ),
			'priority'    => 20,
			'section'     => 'header_image',
		),
		array(
			'slug'        => 'chdrlks_color',
			'default'     => '#FFFFFF',
			'label'       => '',
			'description' => __( 'Links color', 'ekiline' ),
			'priority'    => 20,
			'section'     => 'header_image',
		),
	);

	/**
	 * Loop, seetings and controls
	*/
	foreach ( $colors as $color ) {
		// Add settings.
		$wp_customize->add_setting(
			$color['slug'],
			array(
				'default'           => $color['default'],
				'type'              => 'option',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		// Add controls.
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

	// Mostrar datos Home/Blog.
	$wp_customize->add_setting(
		'ekiline_headerCustomText',
		array(
			'default'           => '',
			'sanitize_callback' => 'ekiline_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'ekiline_headerCustomText',
		array(
			'label'       => __( 'If Post Page is assigned (Homepage Settings), show page title.', 'ekiline' ),
			'description' => '',
			'section'     => 'header_image',
			'settings'    => 'ekiline_headerCustomText',
			'type'        => 'checkbox',
			'priority'    => 30,
		)
	);

	// Controlar el ancho del header.
	$wp_customize->add_setting(
		'ekiline_headerCustomWidth',
		array(
			'default'           => '',
			'sanitize_callback' => 'ekiline_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'ekiline_headerCustomWidth',
		array(
			'label'       => __( 'Narrow header (jumbotron)', 'ekiline' ),
			'description' => '',
			'section'     => 'header_image',
			'settings'    => 'ekiline_headerCustomWidth',
			'type'        => 'checkbox',
			'priority'    => 30,
		)
	);

	// Altura de header.
	$wp_customize->add_setting(
		'ekiline_range_header',
		array(
			'default'           => '40',
			'sanitize_callback' => 'ekiline_sanitize_number_range',
		)
	);

	$wp_customize->add_control(
		'ekiline_range_header',
		array(
			'type'        => 'range',
			'priority'    => 40,
			'section'     => 'header_image',
			'label'       => __( 'Header image height', 'ekiline' ),
			'description' => __( 'Extend the image to fill the entire screen', 'ekiline' ),
			'input_attrs' => array(
				'min'  => 40,
				'max'  => 100,
				'step' => 10,
			),
		)
	);

	// Setup de Video.
	$wp_customize->add_setting(
		'ekiline_video',
		array(
			'default'           => '',
			'sanitize_callback' => 'ekiline_sanitize_video',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Upload_Control(
			$wp_customize,
			'ekiline_video',
			array(
				'label'       => __( 'Front Page video', 'ekiline' ),
				'description' => __( 'Recommended formats: MP4, WEBM or OGV, your header image conserves as poster', 'ekiline' ),
				'section'     => 'header_image',
				'settings'    => 'ekiline_video',
				'priority'    => 10,
			)
		)
	);

}
add_action( 'customize_register', 'ekiline_custom_header_controls' );

/**
 * Controladores con callback: ekiline_custom_header_style()
 * https://developer.wordpress.org/themes/functionality/custom-headers/
 * https://codex.wordpress.org/Custom_Headers
 * Ocupar si fuera necesario: 'wp-head-callback' => 'ekiline_custom_header_style',
 */
function ekiline_custom_header_setup() {

	$new_args = apply_filters(
		'ekiline_custom_header_args',
		array(
			'default-image'      => get_parent_theme_file_uri( '/assets/img/ekiline-media.png' ),
			'default-text-color' => 'FFFFFF',
			'width'              => '1600',
			'height'             => '900',
			'flex-width'         => true,
			'flex-height'        => true,
		)
	);
	add_theme_support( 'custom-header', $new_args );

	// Registrar una imagen default, primero se declara en los filtros.
	register_default_headers(
		array(
			'background1' => array(
				'url'           => get_parent_theme_file_uri( '/assets/img/ekiline-media.png' ),
				'thumbnail_url' => get_parent_theme_file_uri( '/assets/img/thumb-ekiline-media.png' ),
			),
			'background2' => array(
				'url'           => get_parent_theme_file_uri( '/assets/img/ekiline-patron.png' ),
				'thumbnail_url' => get_parent_theme_file_uri( '/assets/img/thumb-ekiline-patron.png' ),
			),
		)
	);
}
add_action( 'after_setup_theme', 'ekiline_custom_header_setup' );

/**
 * Cancelar callback: ekiline_custom_header_style(), aparecerá en el header.
 * Por las caracteristicas del tema, lo agrupamos en themeCustomColors.php
 */
function ekiline_custom_header_style() {

	if ( ! get_header_image() ) {
		return;
	}

	$hdr_bgc    = get_option( 'chdr_color', '#000000' );
	$hdr_lkc    = get_option( 'chdrlks_color', '#FFFFFF' );
	$range_head = get_theme_mod( 'ekiline_range_header', '40' );
	$hdr_style  = '#custom_header_module .wp-block-cover, #custom_header_module .wp-block-cover.has-background-dim::before{ background-color:' . esc_attr( $hdr_bgc ) . ';min-height:' . $range_head . 'vh; }';
	$hdr_style .= ( 'blank' !== get_header_textcolor() ) ? '#custom_header_module .headline{color:#' . esc_attr( get_header_textcolor() ) . ';}' : '';
	$hdr_style .= '#custom_header_module .headline a{color:' . esc_attr( $hdr_lkc ) . ';}';
	$hdr_style .= '@media only screen and (min-width:960px){#custom_header_module .wp-block-cover{background-image:url("' . ekiline_header_image( 'full' ) . '") !important;}}';

	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo $hdr_style;

}
add_action( 'group_inline_css', 'ekiline_custom_header_style', 4 );


/**
 * Clases CSS de apoyo en body_class().
 *
 * @link https://developer.wordpress.org/reference/functions/body_class/
 *
 * @param string $classes add new css class to body.
 */
function ekiline_custom_header_css( $classes ) {
	if ( get_header_image() ) {
		$classes[] = ( 100 === get_theme_mod( 'ekiline_range_header' ) ) ? 'has-custom-header-full-view' : 'has-custom-header';
	}
	return $classes;
}
add_filter( 'body_class', 'ekiline_custom_header_css' );

/**
 * Crear un header dinamico y personalizable.
 * the_header_image_tag() : llama la etiqueta completa.
 * header_image(); : llama la url.
 *
 * @link https://developer.wordpress.org/reference/functions/get_header_image/
 * @link https://developer.wordpress.org/reference/functions/header_image/
 * @link https://developer.wordpress.org/reference/functions/the_header_image_tag/
 *
 * @param string $size set image size.
 **/
function ekiline_header_image( $size = null ) {

	$size = ( ! $size ) ? 'medium_large' : '';
	$url  = get_header_image();

	/*
	* Multiples dimensiones de un attachment
	* https://developer.wordpress.org/reference/functions/wp_get_attachment_image_srcset/
	* $url = wp_get_attachment_image_srcset( get_custom_header()->attachment_id, 'medium', null );
	* Tamaño medio (thumbnail,medium,medium_large,large,full)
	* https://developer.wordpress.org/reference/functions/wp_get_attachment_image/
	* otra documentacion: https://premium.wpmudev.org/blog/wordpress-image-sizes/
	*/

	$theme_images = array( 'media', 'patron' );
	$header_img   = array();
	foreach ( $theme_images as $imagen ) {
		$header_img[] = get_parent_theme_file_uri( '/assets/img/ekiline-' . $imagen . '.png' );
	}

	// Condicion: en caso de ser la imagen de muestra, o ser una imagen nueva.
	if ( ! in_array( $url, $header_img, true ) ) {
		$url = wp_get_attachment_image_url( get_custom_header()->attachment_id, $size, false, '' );
	}

	// Condicion: si es una entrada o pagina.
	if ( is_singular() && ! is_front_page() || is_home() || is_front_page() ) {
		global $post;
		$url = ( has_post_thumbnail() ) ? get_the_post_thumbnail_url( $post->ID, $size ) : $url;
	}

	// Condicion: si existe woocommerce.
	if ( class_exists( 'woocommerce' ) ) {
		if ( is_shop() ) {
			$shop_page_id = wc_get_page_id( 'shop' );
			$url          = ( has_post_thumbnail( $shop_page_id ) ) ? get_the_post_thumbnail_url( $shop_page_id, $size ) : $url;
		}
		if ( is_product_category() ) {
			global $wp_query;
			$cat          = $wp_query->get_queried_object();
			$thumbnail_id = get_term_meta( $cat->term_id, 'thumbnail_id', true );
			$url          = ( $thumbnail_id ) ? wp_get_attachment_image_url( $thumbnail_id, $size, false, '' ) : $url;
		}
	}

	return $url;
}

/**
 * Crear variables de contenido para ../template-parts/custom-header.php
 *
 * @link https://developer.wordpress.org/reference/functions/body_class/
 *
 * @param string $content_type setup if is text or title.
 */
function ekiline_custom_header_content( $content_type = null ) {

	$custom_header_title = get_bloginfo( 'name' );
	$custom_header_text  = get_bloginfo( 'description' );

	$categories_list = '';
	if ( ! is_page() || get_the_category_list() !== '' ) {
		/* Caso extremo, muchas categorias  */
		$string_categories = wp_strip_all_tags( get_the_category_list( ',' ), true );
		$array_string      = explode( ',', $string_categories );
		$count_array       = count( $array_string );

		/* translators: %s is replaced with category title */
		$categories_list = sprintf( '<small>' . esc_html__( 'Categories: %s', 'ekiline' ) . '</small><br>', wp_kses_post( get_the_category_list( ', ' ) ) );

		if ( $count_array > 5 ) {
			$categories_list = sprintf(
				'<div class="dropdown">
					<button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownNavCategories" data-bs-toggle="dropdown">
						' . __( 'Categories:', 'ekiline' ) . '
					</button>
					<div class="dropdown-menu" aria-labelledby="dropdownNavCategories">
						' . get_the_category_list() . '
					</div>
				</div>'
			);
		}
	}

	$tags_list = '';
	if ( get_the_tag_list() !== '' ) {
		/* Caso extremo, muchas etiquetas  */
		$string_tags  = wp_strip_all_tags( get_the_tag_list( '', ',' ), true );
		$array_string = explode( ',', $string_tags );
		$count_array  = count( $array_string );

		/* translators: %s is replaced with tags */
		$tags_list = sprintf( '<small>' . esc_html__( 'Tags: %s', 'ekiline' ) . '</small><br>', wp_kses_post( get_the_tag_list( '', ', ' ) ) );

		if ( $count_array > 5 ) {
			$tags_list = sprintf(
				'<div class="dropdown">
					<button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownNavTags" data-bs-toggle="dropdown">
						' . __( 'Tags:', 'ekiline' ) . '
					</button>
					<div class="dropdown-menu" aria-labelledby="dropdownNavTags">
						' . get_the_tag_list( '<ul><li>', '</li><li>', '</li></ul>' ) . '
					</div>
				</div>',
				null
			);
		}
	}

	if ( is_front_page() && get_theme_mod( 'ekiline_headerCustomText' ) ) {
		global $post;
		$custom_header_title = get_the_title();
		$custom_header_text  = ekiline_content_out_the_loop() . '<br>';
	}

	if ( is_home() ) {
		if ( get_theme_mod( 'ekiline_headerCustomText' ) ) {
			$custom_header_title = get_the_title( get_option( 'page_for_posts', true ) );
			$contentfield        = get_post_field( 'post_content', get_option( 'page_for_posts' ) );
			$custom_header_text  = wp_trim_words( $contentfield, 24 );
			// Si existe un punto antes cortar.
			$punto = strpos( $custom_header_text, '.' );
			if ( $punto ) {
				$custom_header_text = substr( $custom_header_text, 0, strpos( $custom_header_text, '.' ) ) . '.';
			}
		} else {
			$custom_header_title = '<a href="' . esc_url( get_the_permalink() ) . '" title="' . get_the_title() . '">' . get_the_title() . '</a>';
			$custom_header_text  = ekiline_content_out_the_loop() . '<br>';
			$custom_header_text .= $categories_list . ( ( get_the_tag_list() ) ? $tags_list : '' );
		}
	}

	if ( is_singular() && ! is_front_page() ) {
		global $post;
		$username    = get_userdata( $post->post_author );
		$author_item = sprintf(
			/* translators: %s are replaced with author name  */
			'<small>' . esc_html_x( 'Written by %s', 'post authors', 'ekiline' ) . '</small>',
			'<a href="' . get_author_posts_url( $post->post_author ) . '" title="' . get_the_title() . '" rel="author">' . $username->display_name . '</a>'
		);

		$custom_header_title = get_the_title();
		$add_categories      = ( ! is_page() ) ? ' <br> ' . $categories_list : '';
		$custom_header_text  = $author_item . $add_categories; // O agregar etiquetas con $tags_list.
	}

	if ( is_archive() || is_category() ) {
		$custom_header_title = get_the_archive_title();
		$custom_header_text  = '';
		if ( get_the_archive_description() ) {
			$content             = get_the_archive_description();
			$custom_header_text .= wp_strip_all_tags( substr( $content, 0, strpos( $content, '.' ) ) ) . '<br>';
		}
		$custom_header_text .= $categories_list . ( ( get_the_tag_list() ) ? $tags_list : '' );
	}

	if ( is_search() ) {
		global $wp_query;
		/* translators: %1$s is replaced with number of posts */
		$custom_header_title = sprintf( esc_html__( '%s results found.', 'ekiline' ), $wp_query->found_posts );
		/* translators: %1$s is replaced with name of search post */
		$custom_header_text = sprintf( esc_html__( 'Search Results for: %s', 'ekiline' ), get_search_query() );
	}

	if ( is_404() ) {
		$custom_header_title = esc_html__( 'It looks like nothing was found at this location.', 'ekiline' );
		$custom_header_text  = esc_html__( 'Maybe try one of the links below or a search?', 'ekiline' );
	}

	// En caso de woocommerce.
	if ( class_exists( 'woocommerce' ) ) {

		if ( is_shop() ) {
			$custom_header_title = woocommerce_page_title( false );
			// Texto de pagina.
			$shop_id      = wc_get_page_id( 'shop' );
			$shop_post    = get_page( $shop_id );
			$shop_content = $shop_post->post_content;
			// Recortar texto y limpiar.
			$custom_header_text = wp_trim_words( $shop_content, 24 );
			// En caso de existir un punto, recortar.
			$punto = strpos( $custom_header_text, '.' );
			if ( $punto ) {
				$custom_header_text = substr( $custom_header_text, 0, strpos( $custom_header_text, '.' ) ) . '.';
			}
		}

		if ( is_product_category() || is_product_tag() ) {
			$custom_header_title = get_the_archive_title();
			$custom_header_text  = get_the_archive_description();
		}

		if ( is_product() ) {
			$terms   = get_the_terms( $post->ID, 'product_cat' );
			$getcats = '';
			// Obtener categorias a las que el producto pertenece.
			if ( $terms && ! is_wp_error( $terms ) ) {
				$cat_links = array();
				foreach ( $terms as $term ) {
					$cat_links[] = '<a href="' . get_site_url() . '/?product_cat=' . $term->slug . '" title="' . $term->name . '">' . $term->name . '</a>';
				}
				$getcats = join( ',', $cat_links );
			}
			$custom_header_title = get_the_title();
			$custom_header_text  = $getcats;
		}
	}

	if ( 'title' === $content_type ) {
		$content_type = $custom_header_title;
	} elseif ( 'text' === $content_type ) {
		$content_type = $custom_header_text;
	}

	return $content_type;
}

/**
 * Agregar a wp_body_open, header a las paginas, en la parte superior.
 * Add custom header at top of page.
 */
function ekiline_top_page_custom_header() {
	// En caso de woocommerce.
	if ( class_exists( 'woocommerce' ) ) {
		if ( is_cart() || is_checkout() || is_account_page() ) {
			return;
		}
	}
	get_template_part( 'template-parts/custom-header' );
}
add_action( 'wp_body_open', 'ekiline_top_page_custom_header', 1 );


/**
 * Manipular el marcado en el thumbnail, desactivar en publicación si hay header.
 * Custom thumbnail markup, replace it if has header.
 *
 * @link https://developer.wordpress.org/reference/functions/in_the_loop/
 *
 * @param string $html content.
 */
function ekiline_hide_thumbnail( $html ) {
	if ( get_header_image() && ( is_singular() && in_the_loop() ) ) {
		$html = '';
		remove_filter( 'post_thumbnail_html', 'ekiline_hide_thumbnail', 10, 3 );
	}
	return $html;
}
add_filter( 'post_thumbnail_html', 'ekiline_hide_thumbnail', 10, 3 );

/**
 * Manipular el marcado, ocultar el titulo.
 * Hide content title.
 *
 * @param string $title content.
 */
function ekiline_hide_title( $title ) {
	if ( get_header_image() && ( is_singular() && in_the_loop() ) ) {
		$title = '';
		remove_filter( 'the_title', 'ekiline_hide_title' );
	}
	return $title;
}
add_filter( 'the_title', 'ekiline_hide_title' );
