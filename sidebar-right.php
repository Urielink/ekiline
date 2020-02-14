<?php
/**
 * The sidebar containing the main widget area.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package ekiline
 */
if (!is_active_sidebar( 'sidebar-2')) return;
?>

<aside id="third" class="widget-area<?php orderCols('right');?>">
	<?php dynamic_sidebar( 'sidebar-2' ); ?>
</aside><!-- #third -->