<?php
/**
 * Template part for displaying archive posts as card compact.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package ekiline
 */

$card_color   = ' bg-' . array_rand( array_flip( [ 'primary', 'secondary', 'success', 'warning', 'danger', 'info', 'dark' ] ), 1 );
$card_style   = 'card text-white border-0 wp-block-cover' . $card_color;
$card_height  = ( has_post_thumbnail() ) ? wp_rand( 250, 600 ) : 10;
$card_overlay = ( has_post_thumbnail() ) ? 'card-img-overlay d-flex align-content-end flex-wrap' : 'card-body';
$card_text    = wp_trim_words( get_the_content(), '16' );
$card_link    = '<a href="' . esc_url( get_the_permalink() ) . '" title="' . get_the_title() . '">' . __( 'Read more', 'ekiline' ) . '</a>';
?>

<article <?php post_class( $card_style ); ?> style="min-height:<?php echo esc_attr( $card_height ); ?>px">

	<?php the_post_thumbnail( 'medium', array( 'class' => 'card-img wp-block-cover__image-background' ) ); ?>

	<div class="<?php echo esc_attr( $card_overlay ); ?>">

		<?php the_title( '<h2 class="entry-title card-title"><a class="text-light" href="' . esc_url( get_the_permalink() ) . '" title="' . get_the_title() . '">', '</a></h2>' ); ?>

		<p class="card-text small">
			<?php echo esc_html( $card_text ); ?>
			<?php echo wp_kses_post( $card_link ); ?>
		</p>

	</div>

</article><!-- #post-## -->
