<?php
/**
 * Custom header.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ekiline
 */

?>

<?php if ( get_header_image() ) { ?>

<div id="custom_header_module" class="custom-header mb-3<?php echo ( ! get_theme_mod( 'ekiline_headerCustomWidth' ) ) ? '' : ' container px-0'; ?>">

	<div class="wp-block-cover has-background-dim-20 has-background-dim has-parallax bg-deep<?php echo ( ! get_theme_mod( 'ekiline_headerCustomWidth' ) ) ? '' : ' jumbotron'; ?>" style="background-image:url( '<?php echo esc_url( ekiline_header_image() ); ?>' );">

	<?php if ( get_theme_mod( 'ekiline_video' ) && is_front_page() ) { ?>
		<video class="wp-block-cover__video-background intrinsic-ignore" autoplay="" muted="" loop="" playsinline="" src="<?php echo esc_url( get_theme_mod( 'ekiline_video' ) ); ?>" data-object-fit="cover"></video>
	<?php } ?>

		<div class="headline<?php echo ( ! get_theme_mod( 'ekiline_headerCustomWidth' ) ) ? ' container' : ' w-100'; ?><?php echo esc_html( ekiline_header_text_position_css() ); ?>">

			<?php
			if ( ! get_theme_mod( 'ekiline_headerHideLogo' ) ) {
				the_custom_logo();
			}
			?>

			<div class="title display-3">
				<?php echo wp_kses_post( ekiline_custom_header_content( 'title' ) ); ?>
				<a class="skip-link smooth blink btn btn-lg btn-outline-light px-2" href="#primary" title="<?php esc_attr_e( 'Skip to content', 'ekiline' ); ?>" aria-label="Skip">&#8595;</a>
			</div>

			<?php if ( ! get_theme_mod( 'ekiline_headerHideText' ) ) { ?>
				<div class="lead">
					<?php echo wp_kses_post( ekiline_custom_header_content( 'text' ) ); ?>
				</div>
			<?php } ?>

		</div>
	</div>

</div>

<?php } ?>
