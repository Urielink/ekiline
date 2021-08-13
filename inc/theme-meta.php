<?php
/**
 * Metaetiquetas del tema || Meta tags
 *
 * @package ekiline
 */

/**
 * Meta descripcion
 **/
function ekiline_meta_description() {

	// La descripcion general, default: is_home().
	$desc_head = get_bloginfo( 'description' );

	// Si es pagina o post.
	if ( is_singular() ) {
		global $post;
		if ( $post->post_content ) {
			$cont_trim = wp_trim_words( strip_shortcodes( $post->post_content ), 24, '...' );
			if ( $cont_trim ) {
				$desc_head = $cont_trim;
			}
		}
	}

	// Si es archivo o categoria.
	if ( is_archive() ) {
		if ( category_description() ) {
			$desc_head = wp_strip_all_tags( category_description() );
		} else {
			$cat = get_the_category();
			if ( $cat ) {
				$cat_count = $cat[0]->count;
				/* translators: %1$s is replaced with post count and %2$s is asigned to title */
				$desc_head = sprintf( __( 'There are %1$s entries related to %2$s', 'ekiline' ), $cat_count, wp_strip_all_tags( get_the_archive_title() ) );
			}
		}
	}

	return $desc_head;
}

/**
 * Meta descripcion, incorporar.
 **/
function ekiline_print_meta_description() {
	echo '<meta name="description" content="' . esc_attr( ekiline_meta_description() ) . '" />' . "\n";
}
add_action( 'wp_head', 'ekiline_print_meta_description', 0, 0 );

/**
 * Incluir todas // ensure all tags are included in queries
 *
 * @param string $wp_query setup.
 */
function ekiline_tags_support_query( $wp_query ) {
	if ( $wp_query->get( 'tag' ) ) {
		$wp_query->set( 'post_type', 'any' );
	}
}
add_action( 'pre_get_posts', 'ekiline_tags_support_query' );

/**
 * Funcion: Obtener Keywords segun el tipo de contenido.
 **/
function ekiline_meta_keywords() {

	$keywords = '';

	if ( is_single() || is_page() ) {

		global $post;
		$tags = get_the_tags( $post->ID );

		if ( $tags && ! is_wp_error( $tags ) ) {
			$keywords = ekiline_collect_tags( $tags );
		}
	} elseif ( is_tag() ) {

		$keywords = single_tag_title( '', false );

	} elseif ( is_archive() ) {

		$keywords = single_cat_title( '', false );

	} elseif ( is_home() || is_front_page() ) {

		$tags = get_tags();
		$tags = array_slice( $tags, 0, 10 );
		if ( $tags && ! is_wp_error( $tags ) ) {
			$keywords = ekiline_collect_tags( $tags );
		}
	}

	if ( $keywords ) {
		return $keywords;
	}
}

/**
 * Funcion para obtener etiquetas y ocupar como keywords
 *
 * @param array $tags obtener etiquetas de posts.
 */
function ekiline_collect_tags( $tags ) {
	$kwds = array();
	foreach ( $tags as $kwd ) {
		$kwds[] = $kwd->name;
	}
	return join( ',', $kwds );
}

/**
 * Meta Keywords, incorporar.
 **/
function ekiline_print_meta_keywords() {
	if ( ekiline_meta_keywords() ) {
		echo '<meta name="keywords" content="' . esc_attr( ekiline_meta_keywords() ) . '" />' . "\n";
	}
}
add_action( 'wp_head', 'ekiline_print_meta_keywords', 0, 0 );


/**
 * Meta Image url, extender para usar URL redes sociales.
 * meta image url, extend this to use URL in socialmedia.
 */
function ekiline_meta_image() {

	$img_url = wp_get_attachment_url( get_theme_mod( 'ekiline_logo_max' ) );
	if ( get_header_image() ) {
		$img_url = get_header_image();
	}

	if ( is_singular() && ! is_front_page() || is_home() || is_front_page() ) {
		global $post;
		$img_url = ( has_post_thumbnail() ) ? get_the_post_thumbnail_url( $post->ID, 'medium_large' ) : $img_url;
	}

	return $img_url;

}

/**
 * Obtener de un menu la direccion url de una meta.
 * Get url from nav
 *
 * @link https://developer.wordpress.org/reference/functions/get_term_by/
 * @link https://developer.wordpress.org/reference/functions/wp_get_nav_menu_items/
 *
 * @param string $find_string direccion o dominio a verificar.
 */
function ekiline_find_in_nav( $find_string ) {
	$array_menu = wp_get_nav_menu_items( 'social' );
	$item       = $find_string;
	if ( $array_menu ) {
		foreach ( $array_menu as $m ) {
			$url = $m->url;
			if ( ! empty( $find_string ) && strpos( $url, $find_string ) !== false ) {
				$item = $url;
			}
		}
	}
	return $item;
}


/**
 * Obtener el username de una url de twitter
 *
 * @param string $url dominio a extraer.
 */
function ekiline_twitter_username( $url ) {
	if ( 'twitter.com' === $url ) {
		$url = str_replace( ' ', '', get_bloginfo( 'name' ) );
	}
	preg_match( '/[^\/]+$/', $url, $matches );
	$last_word = $matches[0];
	if ( strpos( $last_word, '@' ) !== false ) {
		return $last_word;
	} else {
		return '@' . $last_word;
	}
}


/**
 * Meta social, itemprop, twitter y facebook.
 *
 * @link https://developer.wordpress.org/reference/hooks/wp_title/;
 */
function ekiline_meta_social() {
	global $wp;
	$meta_social = '';
	$find_url    = ekiline_find_in_nav( 'twitter.com' );
	$meta_title  = wp_get_document_title();
	$meta_desc   = ekiline_meta_description();
	$meta_imgs   = ekiline_meta_image();
	$ttr_link    = ekiline_twitter_username( $find_url );
	$meta_type   = 'website';
	$current_url = home_url( add_query_arg( array(), $wp->request ) );
	$meta_locale = get_locale();

	// Google, Browsers.
	$meta_social .= '<meta itemprop="name" content="' . $meta_title . '">' . "\n";
	$meta_social .= '<meta itemprop="description" content="' . $meta_desc . '">' . "\n";
	$meta_social .= '<meta itemprop="image" content="' . $meta_imgs . '">' . "\n";
	// Twitter.
	$meta_social .= '<meta name="twitter:card" content="summary">' . "\n";
	$meta_social .= '<meta name="twitter:site" content="' . $ttr_link . '">' . "\n";
	$meta_social .= '<meta name="twitter:title" content="' . $meta_title . '">' . "\n";
	$meta_social .= '<meta name="twitter:description" content="' . $meta_desc . '">' . "\n";
	$meta_social .= '<meta name="twitter:creator" content="' . $ttr_link . '">' . "\n";
	$meta_social .= '<meta name="twitter:image" content="' . $meta_imgs . '">' . "\n";
	// Facebook, OG.
	$meta_social .= '<meta property="og:title" content="' . $meta_title . '"/>' . "\n";
	$meta_social .= '<meta property="og:type" content="' . $meta_type . '"/>' . "\n";
	$meta_social .= '<meta property="og:url" content="' . $current_url . '"/>' . "\n";
	$meta_social .= '<meta property="og:image" content="' . $meta_imgs . '"/>' . "\n";
	$meta_social .= '<meta property="og:description" content="' . $meta_desc . '"/>' . "\n";
	$meta_social .= '<meta property="og:site_name" content="' . get_bloginfo( 'name' ) . '"/>' . "\n";
	$meta_social .= '<meta property="og:locale" content="' . $meta_locale . '"/>' . "\n";

	$allowed_html = array(
		'meta' => array(
			'itemprop' => array(),
			'content'  => array(),
			'name'     => array(),
			'property' => array(),
		),
	);
	echo wp_kses( $meta_social, $allowed_html );

}

add_action( 'wp_head', 'ekiline_meta_social', 1 );
