<?php
/**
 * Formulario de Búsqueda.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package ekiline
 */

?>

<?php
/**
 * Agregar un número aleatorio para diferenciar un id, necesario por la repetición de un módulo.
 * Add a random number to differentiate an id, necessary for the repetition of a module.
 */
$ekiline_uniq_id = wp_rand( 1, 99 );
?>

<form method="get" id="searchform<?php echo esc_html( $ekiline_uniq_id ); ?>" class="searchform my-2" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="search<?php echo esc_html( $ekiline_uniq_id ); ?>">
		<?php
			/* translators: %s is replaced by searchquery */
			echo esc_html__( 'Search Results for: %s', 'ekiline' );
		?>
	</label>
	<div class="input-group">
		<input class="form-control" type="search" value="<?php echo get_search_query(); ?>" id="search<?php echo esc_html( $ekiline_uniq_id ); ?>" name="s" placeholder="<?php echo esc_attr__( 'Find term', 'ekiline' ); ?>"/>
		<div class="input-group-append">
			<button class="btn btn-secondary" type="submit" id="searchsubmit<?php echo esc_html( $ekiline_uniq_id ); ?>"><span>&orarr;</span>
			<?php echo esc_html__( 'Search', 'ekiline' ); ?></button>
		</div>
	</div>
</form>
