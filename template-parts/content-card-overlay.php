<?php
/**
 * Template part for displaying archive posts as card compact.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package ekiline
 */

$card_styles = ( has_post_thumbnail() ) ? 'card-img-overlay d-flex align-content-end flex-wrap' : 'card-body';
$card_text   = wp_trim_words( get_the_content(), '16' );
$card_link   = '<a href="' . esc_url( get_the_permalink() ) . '" title="' . get_the_title() . '">' . __( 'Read more', 'ekiline' ) . '</a>';
?>

<article <?php post_class( 'card bg-dark text-white mb-3' ); ?>>

	<?php the_post_thumbnail( 'medium', array( 'class' => 'card-img' ) ); ?>

	<div class="<?php echo esc_attr( $card_styles ); ?>">

		<?php the_title( '<h2 class="entry-title card-title"><a href="' . esc_url( get_the_permalink() ) . '" title="' . get_the_title() . '">', '</a></h2>' ); ?>

		<p class="card-text small">
			<?php echo esc_html( $card_text ); ?>
			<?php echo wp_kses_post( $card_link ); ?>
		</p>

	</div>

</article><!-- #post-## -->
