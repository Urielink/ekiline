<?php
/**
 * Plantilla de comentarios.
 *
 * https://developer.wordpress.org/themes/template-files-section/partial-and-miscellaneous-template-files/comment-template/
 *
 * @package ekiline
 */

/*
 * Restringir la vista de comentarios 
 * si el articulo necesita password
 * Don't show comments if post have password.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area clearfix">
    
	<?php if ( have_comments() ) : ?>

		<button class="btn btn-link btn-sm text-secondary float-right" data-toggle="collapse" data-target="#comments-activity">
			<?php echo __('Hide comments','ekiline'); ?> <span>&dtrif;</span>
		</button>    
		<p class="comments-title text-secondary mb-2 pb-2 pt-1 border-bottom">
			<?php
				printf( 
					esc_html( _nx( 'One thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', get_comments_number(), 'comments title', 'ekiline' ) ),
					number_format_i18n( get_comments_number() ),
					'<span>' . get_the_title() . '</span>'
				);
			?>
		</p>
    
		<div id="comments-activity" class="p-2 mb-3 collapse show">

			<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>

				<nav id="comment-nav-above" class="navigation comment-navigation border-bottom">
					<h2 class="screen-reader-text"><?php echo esc_html__( 'Comment navigation', 'ekiline' ); ?></h2>
					<div class="small nav-links d-flex justify-content-end">

						<div class="nav-previous btn-sm"><?php previous_comments_link( '<span>&larr;</span> '.esc_html__( 'Older Comments', 'ekiline' ) ); ?></div>
						<div class="nav-next btn-sm"><?php next_comments_link( esc_html__( 'Newer Comments', 'ekiline' ).' <span>&rarr;</span>' ); ?></div>

					</div><!-- .nav-links -->
				</nav><!-- #comment-nav-above -->

			<?php endif; ?>

			<ol class="comment-list list-unstyled m-0">
				<?php
					wp_list_comments( array(
						'style'      => 'ol',
						'short_ping' => true,
						'class'      => 'border',
						'callback'   => 'ekilineCommentsExtended',
						'avatar_size'       => 64
					) );
				?>
			</ol><!-- .comment-list -->
			
		</div><!-- #comments-activity -->

	<?php endif; // Check for have_comments(). ?>

	<?php
		// Si hay comentarios pero no activos, mostrar un mensaje
		// If comments are closed and there are comments, show a  note

		if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<p class="no-comments"><?php echo esc_html__( 'Comments are closed', 'ekiline' ); ?></p>

	<?php endif; ?>

<?php 
/* 
 * Personalizar el formulario geenral de comentarios.
 * Custom comments forms
 * https://developer.wordpress.org/themes/template-files-section/partial-and-miscellaneous-template-files/comment-template/
 */

function ekilineCommentsSimple($comment, $args, $depth) {
	
    if ( 'div' === $args['style'] ) {
        $tag       = 'div';
        $add_below = 'comment';
    } else {
        $tag       = 'li';
        $add_below = 'div-comment';
    }?>
    
    <<?php echo $tag; ?> <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?> id="comment-<?php comment_ID() ?>">
    
    <?php if ( 'div' != $args['style'] ) { ?>    	
        <div id="div-comment-<?php comment_ID() ?>" class="comment-body row mb-2 px-md-3">
    <?php } ?>
    
    		<div class="col col-md-1 col-sm-2 col-3 text-center">    
	        <?php if ( $args['avatar_size'] != 0 ) echo get_avatar( $comment, $args['avatar_size'], '', '', array('class' => 'rounded-circle img-fluid')  ); ?>
	        </div>

			<div class="rounded bg-white col-md-11 col-sm-10 col-9 py-2">
		        <?php comment_text(); ?>
	
	        	<?php comment_reply_link( array_merge( $args, array( 
	        					// 'before' => '<div class="btn btn-danger">',
	        					// 'after' => '</div>',
	                            // 'reply_text' => __('Reply','ekiline'),
	                            'add_below' => $add_below, 
	                            'depth'     => $depth, 
	                            'max_depth' => $args['max_depth']
	                        ))); ?>
			</div>

        
        <?php if ( 'div' != $args['style'] ) { ?>
        	
        </div>
        
        <?php }
		
}

function ekilineCommentsExtended($comment, $args, $depth) {
	
    if ( 'div' === $args['style'] ) {
        $tag       = 'div';
        $add_below = 'comment';
    } else {
        $tag       = 'li';
        $add_below = 'div-comment';
    }?>
    
    <<?php echo $tag; ?> <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?> id="comment-<?php comment_ID() ?>">
    
    <?php if ( 'div' != $args['style'] ) { ?>    	
        <div id="div-comment-<?php comment_ID() ?>" class="comment-body">
    <?php } ?>
    
        <div class="comment-author vcard row">
        	
        	<div class="col col-md-1 col-sm-2 col-3 text-center">
		        <?php if ( $args['avatar_size'] != 0 ) echo get_avatar( $comment, $args['avatar_size'], '', '', array('class' => 'img-fluid mt-2')  ); ?>
        		<?php //if ( $args['avatar_size'] != 0 ) { echo get_avatar( $comment, $args['avatar_size'] ); }  ?>
        	</div>
        	
        	<div class="rounded bg-white col-md-11 col-sm-10 col-9 py-2">
		        <?php printf( __( '<cite class="fn">%s</cite> <span class="says">says:</span>','ekiline' ), get_comment_author_link() ); ?>
		        
		        <?php if ( $comment->comment_approved == '0' ) { ?>
		            <em class="comment-awaiting-moderation"><?php __( 'Your comment is awaiting moderation.','ekiline' ); ?></em><br/>
		        <?php } ?>

		        <?php comment_text(); ?>
        		
        	</div>        	
            
        </div>
        
        <div class="d-flex justify-content-between small">
        <div class="comment-meta commentmetadata">
            <a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>">
            	<?php
                /* translators: 1: date, 2: time */
                printf( 
                    __('%1$s at %2$s','ekiline'), 
                    get_comment_date(),  
                    get_comment_time() 
                ); ?>
            </a>
            <?php edit_comment_link( __( '(Edit)','ekiline' ), '  ', '' ); ?>
        </div>

        <div class="reply text-right">
        	<?php comment_reply_link( array_merge( $args, array( 
                            'add_below' => $add_below, 
                            'depth'     => $depth, 
                            'max_depth' => $args['max_depth'] 
                        ))); ?>
        </div>
        </div>
        
        <?php if ( 'div' != $args['style'] ) { ?>
        	
        </div>
        
        <?php }
		
}

/* 
 * Personalizar el formulario de comentarios
 * Customizing comments form
 * https://developer.wordpress.org/reference/functions/comment_form/
 * https://premium.wpmudev.org/blog/customizing-wordpress-comment-form/
 * 
 */

$req = get_option( 'require_name_email' );
$aria_req = ( $req ? " aria-required='true'" : '' );

$args = array(
	'comment_field' => '<div class="comment-form-comment">' .
	'<label for="comment">' . __( 'Comment', 'ekiline' ) . '</label>' .
	( $req ? '<span class="required">*</span>' : '' ) .
	'<textarea id="comment" name="comment" class="form-control mb-2" aria-required="true"></textarea></div>',

  	'fields' => apply_filters( 'comment_form_default_fields', array(
	    'author' =>
	      '<div class="comment-form-author">' .
	      '<label for="author">' . __( 'Name','ekiline' ) . '</label> ' .
	      ( $req ? '<span class="required">*</span>' : '' ) .
	      '<input id="author" name="author" class="form-control" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
	      '" size="30"' . $aria_req . ' /></div>',
	
	    'email' =>
	      '<div class="comment-form-email"><label for="email">' . __( 'Email','ekiline' ) . '</label> ' .
	      ( $req ? '<span class="required">*</span>' : '' ) .
	      '<input id="email" name="email" type="text" class="form-control" value="' . esc_attr(  $commenter['comment_author_email'] ) .
	      '" size="30"' . $aria_req . ' /></div>',
	      
	    'url' =>
	      '<div class="comment-form-url"><label for="url">' .
	      __( 'Website','ekiline' ) . '</label>' .
	      ( $req ? '<span class="required">*</span>' : '' ) .            
	      '<input id="url" name="url" type="text" class="form-control" value="' . esc_attr( $commenter['comment_author_url'] ) .
	      '" size="30" /></div><br/>'      
	  	)
	  ),
	  
    // las clases de manera independiente:
    'class_form'           => 'comment-form form',
    'class_submit' => 'submit btn btn-sm btn-secondary float-right mb-2'  
	
);

	comment_form( $args );
?>
</div><!-- #comments -->