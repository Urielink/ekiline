<?php
/**
 * Navegacion principal || Default menu
 *
 * @package ekiline
 */


/**
 * Agregar logotipo a menu
 * Adding logo image to navbar-brand:
 **/
 
function logoTheme() {
    //variables de logotipo
    $logoIcono = get_theme_mod( 'ekiline_minilogo' ); //get_site_icon_url();
    $logoHor = wp_get_attachment_url( get_theme_mod( 'ekiline_logo_max' ) );

    if ( $logoHor && !$logoIcono ) {
        echo '<img class="img-fluid" src="' . $logoHor . '" alt="' . get_bloginfo( 'name' ) . '"/>';
    } elseif ( !$logoHor && $logoIcono ) {
        echo '<img class="brand-icon" src="' . get_site_icon_url() . '" alt="' . get_bloginfo( 'name' ) . '"/>' . get_bloginfo( 'name' );
    } elseif ( $logoHor && $logoIcono ) {
        echo '<img class="img-fluid d-none d-md-block" src="' . $logoHor . '" alt="' . get_bloginfo( 'name' ) . '"/>
        <span class="d-block d-md-none"><img class="brand-icon" src="' . get_site_icon_url() . '" alt="' . get_bloginfo( 'name' ) . '"/>' . get_bloginfo( 'name' ) . '</span>';
    } else {
        echo get_bloginfo( 'name' );
    } 
}

/**
 * Todos los menus
 * Se complementa con acciones preestablecidas en customizer.php
 * Works with customizer.php
 **/

function ekilineNavbar($navPosition){

	if ( !has_nav_menu( $navPosition ) ) return; 
		
		// invertir color (class css)
        $inverseMenu = 'navbar-light bg-light ';
		if( true === get_theme_mod('ekiline_inversemenu') ) : $inverseMenu = 'navbar-dark bg-dark ';  endif;

		// clase auxiliar alineación de items, transformar a header.
        $navAlign = '';
		$headNav = '';
		$navHelper = '';
		$modalCss = '';
		// variables para boton modal
		$dataToggle = 'collapse';
		$dataTarget = $navPosition.'NavMenu';				
		$expand = 'navbar-expand-md ';
		$togglerBtn = 'navbar-toggler collapsed';
						
		// Variables por cada tipo de menu: configurcion y distribucion de menu	    						
		$actions = get_theme_mod('ekiline_'.$navPosition.'menuSettings');
		$styles = get_theme_mod('ekiline_'.$navPosition.'menuStyles'); 
		
		//Clases css por configuración de menu
		if ($actions == '0') {
		    $navAction = 'static-top';
	    } elseif ($actions == '1') {
	        $navAction = 'fixed-top'; 
	    } elseif ($actions == '2') {
	        $navAction = 'fixed-bottom'; 
	    } elseif ($actions == '3') {
	        $navAction = 'navbar-sticky'; 
	    }	

		//Clases css por estilo de menu
		switch ($styles) {
		    case 0 : $navAlign = ' mr-auto'; break;
		    case 1 : $navAlign = ' ml-auto'; break;
		    case 2 : $navHelper = ' justify-content-md-center'; $navAlign = ' justify-content-md-center'; $headNav = ' flex-md-column'; break;
		    case 3 : $navHelper = ' justify-content-md-between w-100'; $navAlign = ' justify-content-md-between w-100'; $headNav = ' flex-md-column'; break;
		    case 4 : $navHelper = ' justify-content-md-around w-100'; $navAlign = ' justify-content-md-around w-100'; $headNav = ' flex-md-column'; break;
		    case 5 : $navHelper = ' offcanvas bg-light'; $navAlign = ' ml-auto'; break;
		    case 6 : $navHelper = ' order-first'; $expand = ' '; break;
		    case 7 : $modalCss = 'modal fade'; break;
		    case 8 : $modalCss = 'modal fade move-from-bottom'; break;
		    case 9 : $modalCss = 'modal fade left-aside'; break;
		    case 10 : $modalCss = 'modal fade right-aside'; break;
		}
				   		
		// Clases css para mostrar el boton del modal
		if ( $styles >= '7'){
			 $expand = ' '; 
			 $dataToggle = 'modal';
			 $dataTarget = $navPosition.'NavModal';
			 $togglerBtn = 'modal-toggler navbar-toggler collapsed';
		}

		// Clases reunidas para <nav>
		$navClassCss = 'navbar '. $inverseMenu . $navPosition . '-navbar ' . $expand . $navAction;
		// Clases reunidas para .navbar-collapse
		$collapseCss = 'collapse navbar-collapse ' . $navHelper;

?>

			<nav id="<?php echo $navPosition;?>SiteNavigation"  class="<?php echo $navClassCss;?>">
			
		    	<div class="container<?php echo $headNav; ?>">

		            <a class="navbar-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>"><?php logoTheme(); ?></a>
		            
					<?php if( get_bloginfo( 'description' ) ) { ?>
					<span class="navbar-text d-none d-md-block"><?php echo get_bloginfo( 'description' ); ?></span> 
					<?php }?>

		            <button class="<?php echo $togglerBtn;?>" type="button" data-toggle="<?php echo $dataToggle; ?>" data-target="#<?php echo $dataTarget; ?>">
		      			<span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
		            </button>
		            
	            <?php if ( $styles <= '6'){ ?> 

			        <div id="<?php echo $dataTarget;?>" class="<?php echo $collapseCss;?>">

			    	        <?php wp_nav_menu( array(
			        	                'menu'              => $navPosition,
			        	                'theme_location'    => $navPosition,
			        	                // 'depth'             => 2, // en caso de restringir la profundidad
			        	                'container'         => '',
		                                'container_class'   => '',
		                                'container_id'      => '',
			        	                'menu_class'        => 'navbar-nav' . $navAlign,
			        	                'menu_id'           => $navPosition . 'MenuLinks',
			                            'fallback_cb'       => 'EkilineNavFallback',
			        	                'walker'            => new EkilineNavMenu()
			    	                ) ); ?>
		        	
			        </div>
			       		            	
	            <?php } ?>


		    	</div><!-- .container --> 
		    	
			</nav><!-- .site-navigation -->       
			
	            <?php if ( $styles >= '7'){
		            	ekiline_modalMenuBottom($navPosition);
	            }?>
	<?php 

}

/*
 * Fragmento para crear un menu con madal
 */
function ekiline_modalMenuBottom($navPosition){
	/*tipos de animacion: .zoom, .newspaper, .move-horizontal, .move-from-bottom, .unfold-3d, .zoom-out, .left-aside, .right-aside */
	$modalId = $navPosition.'NavModal';
	$modalCss = '';
	switch ( get_theme_mod('ekiline_'.$navPosition.'menuStyles') ) {
	    case 7 : $modalCss = 'modal fade modal-nav'; break;
	    case 8 : $modalCss = 'modal fade move-from-bottom modal-nav'; break;
	    case 9 : $modalCss = 'modal fade left-aside modal-nav'; break;
	    case 10 : $modalCss = 'modal fade right-aside modal-nav'; break;
	}?>
	
<div id="<?php echo $modalId;?>" class="<?php echo $modalCss;?>" tabindex="-1" role="dialog" aria-labelledby="navModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <!--div class="modal-header">
        <h3 class="modal-title" id="navModalLabel"><?php // echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?></h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div-->
      <div class="modal-body navbar-light bg-light">
      	<div class="navbar p-0">

		<?php if( get_bloginfo( 'description' ) ) { ?>
			<span class="navbar-text"><?php echo get_bloginfo( 'description' ); ?></span> 
		<?php }?>
        
        <button class="navbar-toggler m-0" data-dismiss="modal" aria-label="Close">
  			<span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
        </button>
        
	    <?php wp_nav_menu( array(
	                'menu'              => $navPosition,
	                'theme_location'    => $navPosition,
	                // 'depth'             => 2, // en caso de restringir la profundidad
	                'container'         => 'div',
	                'container_class'   => 'navbar-collapse collapse show',
	                'container_id'      => '',
	                'menu_class'        => 'navbar-nav',
	                'menu_id'           => 'modal-menu',
	                'fallback_cb'       => 'EkilineNavFallback',
	                'walker'            => new EkilineNavMenu()
	            ) ); ?>
      	</div>
      </div>
      <!--div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div-->
    </div>
  </div>
</div><!-- #<?php echo $modalId;?> -->

<?php }
// add_action( 'wp_footer', 'ekiline_modalMenuBottom', 0, 1 );

function EkilineNavFallback() {
if ( is_user_logged_in() ) $link = '/wp-admin/nav-menus.php'; else $link = '/wp-login.php';
$link = get_site_url(null,$link);
  ?>
  <ul id="SetNavMenu" class="navbar-nav mr-auto">
  	<li class="nav-item">
  		<a class="nav-link" href="<?php echo $link; ?>"><?php echo __('Assign a menu!','ekiline'); ?></a>		
	</li>
  </ul>
  <?php
} // EkilineNavFallback