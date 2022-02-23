<?php
/**
 * Ekiline info
 *
 * Admin bar button
 *
 * @link https://codex.wordpress.org/Plugin_API/Action_Reference/wp_before_admin_bar_render
 * @link https://codex.wordpress.org/Javascript_Reference/ThickBox
 *
 * Y con estilos agregados.
 *
 * @link https://codex.wordpress.org/Function_Reference/wp_add_inline_style
 * @link https://gist.github.com/corvannoorloos/43980115659cb5aee571
 * @link https://wordpress.stackexchange.com/questions/36394/wp-3-3-how-to-add-menu-items-to-the-admin-bar
 * @link https://wordpress.stackexchange.com/questions/266318/how-to-add-custom-submenu-links-in-wp-admin-menus
 *
 * @package ekiline
 */

/**
 * Ekiline, side menu button.
 */
function ekiline_theme_page() {
	add_theme_page(
		'Ekiline Menu Page Title',
		__( 'About Ekiline', 'ekiline' ),
		'edit_posts',
		'ekiline_options',
		'ekiline_theme_html_page'
	);
}
add_action( 'admin_menu', 'ekiline_theme_page' );

/**
 * Ekiline, info.
 */
function ekiline_theme_html_page() { ?>
<style>
	.ekiline-notice, .ekiline-notice h4, .ekiline-notice ul, .ekiline-notice ul li{ margin:0px 5px 0px 0px !important; padding-bottom:0px !important; display: inline-block; }
	.ekiline-notice ul li, .welcome-panel::before{ display:none;}
	.welcome-panel-header{box-sizing:border-box;margin-left:auto;margin-right:auto;max-width:1500px;width:100%;padding:40px;}
	.welcome-panel-column {display: block;}
	.welcome-panel .welcome-panel-column-container {margin-top: 0px;}
</style>
<div class="wrap">
	<h1>
		<span class="dashicons dashicons-layout" aria-hidden="true"></span>
			<?php esc_html_e( 'About Ekiline for WordPress', 'ekiline' ); ?>
	</h1>

	<div id="welcome-panel" class="welcome-panel">
		<div class="welcome-panel-content">

			<div class="welcome-panel-header">
				<span style="float:right;"><?php echo ( function_exists( 'get_avatar' ) ) ? get_avatar( 'uriel@bixnia.com', $size = '100', $default = '' ) : ''; ?></span>
				<h2><?php esc_html_e( 'Thanks for using this theme!', 'ekiline' ); ?></h2>
				<p class="about-description">
					<?php esc_html_e( 'Find more information to improve your skills in customizing your site.', 'ekiline' ); ?>
				</p>
			</div>

			<div class="welcome-panel-column-container">
				<div class="welcome-panel-column">
					<div style="padding:4px;">
						<h3><?php esc_html_e( 'Add an extra to the creation and delivery of your projects.', 'ekiline' ); ?></h3>
						<ul>
							<li><span class="dashicons dashicons-book dash-note"></span>
								<?php esc_html_e( 'Quick use guide in Keynote or Powerpoint to freely customize and deliver to your clients.', 'ekiline' ); ?></li>
							<li><span class="dashicons dashicons-edit dash-note"></span>
								<?php esc_html_e( 'More design elements, CSS, HTML and JS available to grow your project.', 'ekiline' ); ?></li>
							<li><span class="dashicons dashicons-layout dash-note"></span>
								<?php esc_html_e( 'Ekiline Blocks, plugin with carousel and more options in page editor.', 'ekiline' ); ?></li>
							<li><span class="dashicons dashicons-welcome-view-site dash-note"></span>
								<?php esc_html_e( 'Complete clean theme, no warnings or messages.', 'ekiline' ); ?></li>
						</ul>
						<p>
							<?php printf( '<a class="button button-primary button-hero" href="%1$s" target="_blank"><span class="dashicons dashicons-cart"></span> %2$s</a>', esc_url( 'https://ekiline.com/compra/' ), esc_html__( 'Buy and download', 'ekiline' ) ); ?>
							<?php printf( '<a class="button button-primary button-hero gold" href="%1$s" target="_blank"><span class="dashicons dashicons-carrot"></span> %2$s</a>', esc_url( 'https://ekiline.com/fondeo/' ), esc_html__( 'FundMe', 'ekiline' ) ); ?>
						</p>
						<p>
							<?php
							printf(
								/* translators: %1$s is replaced with links data */
								esc_html__( '%1$s with Ekiline and %2$s, %3$s.', 'ekiline' ),
								'<a href="' . esc_url( 'https://ekiline.com/gana/' ) . '" target="_blank" rel="noopener">' . esc_html__( 'Make money', 'ekiline' ) . '</a>',
								'<a href="' . esc_url( 'https://ekiline.com/fondeo/' ) . '" target="_blank" rel="noopener">' . esc_html__( 'fund its development', 'ekiline' ) . '</a>',
								'<a href="' . esc_url( 'https://ekiline.com/' ) . '" target="_blank" rel="noopener">' . esc_html__( 'discover how', 'ekiline' ) . '</a>'
							);
							?>
						</p>
					</div>
				</div>
				<div class="welcome-panel-column">
					<div style="padding:4px;">
						<h3><?php esc_html_e( 'About Ekiline', 'ekiline' ); ?></h3>
						<p><?php esc_html_e( 'Ekiline simplifies the creation of a website with WordPress, it is a working method that brings together the standard practices of the internet industry, to facilitate the tasks of planning, design, development and optimization. For more information visit ekiline.com', 'ekiline' ); ?></p>
						<h3><?php esc_html_e( 'About Uri Lazcano', 'ekiline' ); ?></h3>
						<p>
							<span style="float:left;"><?php echo ( function_exists( 'get_avatar' ) ) ? get_avatar( 'uriel@bixnia.com', $size = '60', $default = '' ) : ''; ?></span>
							<?php esc_html_e( 'Frontend designer established in Mexico City. 10 years developing websites and 10 more years of experience in graphic design for advertising. Check my career and work.', 'ekiline' ); ?>
						</p>
						<p>
							<?php printf( '<a href="%1$s" target="_blank">%2$s</a>', esc_url( 'https://profiles.wordpress.org/urielink/' ), esc_html__( 'WordPress', 'ekiline' ) ); ?>
							| <?php printf( '<a href="%1$s" target="_blank">%2$s</a>', esc_url( 'https://github.com/Urielink' ), esc_html__( 'Github', 'ekiline' ) ); ?>
							| <?php printf( '<a href="%1$s" target="_blank">%2$s</a>', esc_url( 'https://www.linkedin.com/in/urielink/' ), esc_html__( 'Linkedin', 'ekiline' ) ); ?>
							| <?php printf( '<a href="%1$s" target="_blank">%2$s</a>', esc_url( 'https://www.behance.net/Urielink' ), esc_html__( 'Behance', 'ekiline' ) ); ?>
							| <?php printf( '<a href="%1$s" target="_blank">%2$s</a>', esc_url( 'https://bixnia.com' ), esc_html__( 'B I X N I A', 'ekiline' ) ); ?>
						</p>
					</div>
				</div>
				<div class="welcome-panel-column welcome-panel-last">
					<div style="padding:4px;">
						<h3>
							<span class="dashicons dashicons-book dash-note"></span>
							<?php esc_html_e( 'Ekiline docs', 'ekiline' ); ?>
						</h3>
						<ul>
							<li><?php printf( '<a href="%1$s" target="_blank">%2$s</a>', esc_url( 'https://ekiline.com/instala/' ), esc_html__( 'Install', 'ekiline' ) ); ?></li>
							<li><?php printf( '<a href="%1$s" target="_blank">%2$s</a>', esc_url( 'https://ekiline.com/personaliza/' ), esc_html__( 'Customize', 'ekiline' ) ); ?></li>
							<li><?php printf( '<a href="%1$s" target="_blank">%2$s</a>', esc_url( 'https://ekiline.com/elementos/' ), esc_html__( 'Elements and blocks', 'ekiline' ) ); ?></li>
							<li><?php printf( '<a href="%1$s" target="_blank">%2$s</a>', esc_url( 'https://ekiline.com/compatible/' ), esc_html__( 'Compatibility', 'ekiline' ) ); ?></li>
						</ul>

						<?php
						/**
						 * Widget RSS reader.
						 */
						$rss_instance = array(
							'title' => 'Ekiline Tips:',
							'url'   => 'http://ekiline.com/feed/',
							'items' => 5,
						);
						$rss_args     = array(
							'before_widget' => '<div class="ekiline-notice widget %s">',
							'after_widget'  => '</div>',
							'before_title'  => '<h4 class="widgettitle">',
							'after_title'   => '</h4>',
						);
						the_widget( 'WP_Widget_RSS', $rss_instance, $rss_args );
						?>

						<p><small><strong><?php esc_html_e( 'Limited liability:', 'ekiline' ); ?></strong>
						<?php esc_html_e( 'As a courtesy, we provide information on how to use certain third-party products, but we do not directly support their use and we are not responsible for the functions, reliability or compatibility of such products. The names, trademarks and logos of third parties are registered trademarks of their respective owners.', 'ekiline' ); ?></small></p>
					</div>
				</div>
			</div>

		</div>
	</div>

	<p style="text-align: right;">
		<small>
			<?php
				/* translators: %1$s is replaced with date data */
				printf( esc_html__( '&copy; Copyright %1$s Ekiline', 'ekiline' ), esc_attr( date( 'Y' ) ) );
			?>
			<?php esc_html_e( 'All rights reserved. Ekiline developed by', 'ekiline' ); ?>
			<?php printf( '<a href="%1$s" target="_blank">%2$s</a>', esc_url( 'https://bixnia.com/' ), esc_html__( 'B I X N I A', 'ekiline' ) ); ?>
		</small>
	</p>
</div>
<script type='text/javascript'>
	jQuery(document).ready(function($){
		var random = Math.floor(Math.random() * 5) + 1;
		$('.ekiline-notice ul li:nth-child('+random+')').delay(2000).show(100);
	});
</script>
	<?php
}

