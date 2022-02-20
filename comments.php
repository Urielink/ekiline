<?php
/**
 * Plantilla de comentarios.
 *
 * @link https://developer.wordpress.org/themes/template-files-section/partial-and-miscellaneous-template-files/comment-template/
 *
 * @package ekiline
 */

if ( post_password_required() ) {
	return;
}

?>

<div id="comments" class="comments-area clearfix">

	<?php if ( have_comments() ) : ?>

		<button class="btn btn-link btn-sm text-secondary float-end" data-bs-toggle="collapse" data-bs-target="#comments-activity">
			<?php esc_html_e( 'Hide comments', 'ekiline' ); ?> <span>&dtrif;</span>
		</button>

		<p class="comments-title text-secondary mb-2 pb-2 pt-1 border-bottom">
			<?php
				/* NX https://developer.wordpress.org/reference/functions/_nx/ */
				printf(
					esc_html(
						/* translators: %1$s is replaced with comments count, %2$s is replaced with title  */
						_nx( '%1$s thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', get_comments_number(), 'comments title', 'ekiline' )
					),
					esc_html( number_format_i18n( get_comments_number() ) ),
					'<span>' . esc_html( get_the_title() ) . '</span>'
				);
			?>
		</p>

		<div id="comments-activity" class="p-2 mb-3 collapse show">

			<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>

				<nav id="comment-nav-above" class="navigation comment-navigation border-bottom">
					<h2 class="screen-reader-text"><?php echo esc_html__( 'Comment navigation', 'ekiline' ); ?></h2>
					<div class="small nav-links d-flex justify-content-end">

						<div class="nav-previous btn-sm"><?php previous_comments_link( '<span>&larr;</span> ' . esc_html__( 'Older Comments', 'ekiline' ) ); ?></div>
						<div class="nav-next btn-sm"><?php next_comments_link( esc_html__( 'Newer Comments', 'ekiline' ) . ' <span>&rarr;</span>' ); ?></div>

					</div><!-- .nav-links -->
				</nav><!-- #comment-nav-above -->

			<?php endif; ?>

			<ol class="comment-list list-unstyled m-0">
				<?php
					wp_list_comments(
						array(
							'style'       => 'ol',
							'short_ping'  => true,
							'class'       => 'border',
							'callback'    => 'ekiline_comments_extended',
							'avatar_size' => 64,
						)
					);
				?>
			</ol><!-- .comment-list -->

		</div><!-- #comments-activity -->

	<?php endif; // Check for have_comments(). ?>

	<?php
	/* Si hay comentarios pero no activos, mostrar un mensaje || If comments are closed and there are comments, show a  note */
	if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
		?>

		<p class="no-comments"><?php echo esc_html__( 'Comments are closed', 'ekiline' ); ?></p>

	<?php endif; ?>

<?php
/**
 * Personalizar el formulario general de comentarios, version simple
 * Custom comments forms
 *
 * @link https://developer.wordpress.org/themes/template-files-section/partial-and-miscellaneous-template-files/comment-template/
 *
 * @param string $comment contenido.
 * @param string $args reglas.
 * @param string $depth profundidad.
 */
function ekiline_comments_simple( $comment, $args, $depth ) {

	if ( 'div' === $args['style'] ) {
		$tag       = 'div';
		$add_below = 'comment';
	} else {
		$tag       = 'li';
		$add_below = 'div-comment';
	}
	?>

	<<?php echo esc_attr( $tag ); ?> <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?> id="comment-<?php comment_ID(); ?>">

	<?php if ( 'div' !== $args['style'] ) { ?>
		<div id="div-comment-<?php comment_ID(); ?>" class="comment-body row mb-2 px-md-3">
	<?php } ?>

			<div class="col col-md-1 col-sm-2 col-3 text-center">
			<?php
			if ( 0 !== $args['avatar_size'] ) {
				echo get_avatar( $comment, $args['avatar_size'], '', '', array( 'class' => 'rounded-circle img-fluid' ) );
			}
			?>
			</div>

			<div class="rounded bg-white col-md-11 col-sm-10 col-9 py-2">
				<?php comment_text(); ?>

				<?php
				comment_reply_link(
					array_merge(
						$args,
						array(
							'add_below' => $add_below,
							'depth'     => $depth,
							'max_depth' => $args['max_depth'],
						)
					)
				);
				?>
			</div>


		<?php
		if ( 'div' !== $args['style'] ) {
			?>

		</div>

			<?php
		}

}

/**
 * Personalizar el formulario general de comentarios, version extensa (default)
 * Custom comments forms
 *
 * @link https://developer.wordpress.org/themes/template-files-section/partial-and-miscellaneous-template-files/comment-template/
 *
 * @param string $comment contenido.
 * @param string $args reglas.
 * @param string $depth profundidad.
 */
function ekiline_comments_extended( $comment, $args, $depth ) {

	if ( 'div' === $args['style'] ) {
		$tag       = 'div';
		$add_below = 'comment';
	} else {
		$tag       = 'li';
		$add_below = 'div-comment';
	}
	?>

	<<?php echo esc_attr( $tag ); ?> <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?> id="comment-<?php comment_ID(); ?>">

	<?php if ( 'div' !== $args['style'] ) { ?>
		<div id="div-comment-<?php comment_ID(); ?>" class="comment-body">
	<?php } ?>

		<div class="comment-author vcard row">

			<div class="col col-md-1 col-sm-2 col-3 text-center">
				<?php
				if ( 0 !== $args['avatar_size'] ) {
					echo get_avatar( $comment, $args['avatar_size'], '', '', array( 'class' => 'img-fluid mt-2' ) );
				}
				?>
			</div>

			<div class="rounded bg-white col-md-11 col-sm-10 col-9 py-2">
				<?php
					/* translators: %s is replaced with author link  */
					printf( wp_kses_post( __( '<cite class="fn">%s</cite> <span class="says">says:</span>', 'ekiline' ) ), get_comment_author_link() );
				?>

				<?php if ( 0 === $comment->comment_approved ) { ?>
					<em class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'ekiline' ); ?></em><br/>
				<?php } ?>

				<?php comment_text(); ?>

			</div>

		</div>

		<div class="d-flex justify-content-between small">
		<div class="comment-meta commentmetadata">
			<a href="<?php echo esc_url( htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ); ?>">
				<?php
				printf(
					/* translators: 1: date, 2: time */
					esc_html( __( '%1$s at %2$s', 'ekiline' ) ),
					esc_html( get_comment_date() ),
					esc_html( get_comment_time() )
				);
				?>
			</a>
			<?php edit_comment_link( __( '(Edit)', 'ekiline' ), '  ', '' ); ?>
		</div>

		<div class="reply text-right">
			<?php
			comment_reply_link(
				array_merge(
					$args,
					array(
						'add_below' => $add_below,
						'depth'     => $depth,
						'max_depth' => $args['max_depth'],
					)
				)
			);
			?>
		</div>
		</div>

		<?php
		if ( 'div' !== $args['style'] ) {
			?>

		</div>

			<?php
		}

}

/**
 * Personalizar los campos del formulario.
 * Custom form fields
 *
 * @link https://developer.wordpress.org/reference/functions/comment_form/
 * @link https://premium.wpmudev.org/blog/customizing-wordpress-comment-form/
 */
$comment_notes_required_indicator = ' <span class="required" aria-hidden="true">*</span>';
/* translators: %s: Asterisk symbol (*). */
$comment_notes_required_text = sprintf( ' <span class="required-field-message" aria-hidden="true">' . __( 'Required fields are marked %s', 'ekiline' ) . '</span>', trim( $comment_notes_required_indicator ) );
$comment_notes_text_before   = sprintf( '<p class="comment-notes small">%s%s</p>', sprintf( '<span id="email-notes">%s</span>', __( 'Your email address will not be published.', 'ekiline' ) ), $comment_notes_required_text );

$args = array(
	'title_reply_before'   => '<h4 id="reply-title" class="comment-reply-title">',
	'title_reply_after'    => '</h4>',
	'comment_notes_before' => $comment_notes_text_before,
	'title_reply'          => __( 'Write a comment', 'ekiline' ),
	'comment_field'        => '<div class="form-group">' .
								'<label for="comment" class="form-label">' . __( 'Comment', 'ekiline' ) . '</label>' .
								'<textarea id="comment" name="comment" class="form-control"></textarea>' .
							'</div>',
	'fields'               => apply_filters(
		'comment_form_default_fields',
		array(
			'author'  => '<div class="form-group">' .
							'<label for="author" class="form-label">' . __( 'Name', 'ekiline' ) . '*</label> ' .
							'<input id="author" name="author" class="form-control" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"/>' .
						'</div>',
			'email'   => '<div class="form-group">' .
							'<label for="email" class="form-label">' . __( 'Email', 'ekiline' ) . '*</label> ' .
							'<input id="email" name="email" type="text" class="form-control" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30"/>' .
						'</div>',
			'url'     => '<div class="form-group">' .
							'<label for="url" class="form-label">' . __( 'Website', 'ekiline' ) . '</label>' .
							'<input id="url" name="url" type="text" class="form-control" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30"/>' .
						'</div>',
			'cookies' => '<div class="form-check py-2">' .
							'<input class="form-check-input" type="checkbox" id="agree" required>' .
							'<label class="form-check-label" for="agree">' . __( 'By commenting you accept the', 'ekiline' ) .
								'<a href="' . get_privacy_policy_url() . '"> ' . __( 'Privacy Policy', 'ekiline' ) . '</a>' .
							'</label>' .
						'</div>',

		)
	),
	/* las clases de manera independiente: */
	'class_form'           => 'comment-form form',
	'class_submit'         => 'submit btn btn-primary',
	'submit_field'         => '<p class="form-submit text-end d-grid gap-2 d-md-block">%1$s %2$s</p>',
);

	comment_form( $args );

?>
</div><!-- #comments -->
