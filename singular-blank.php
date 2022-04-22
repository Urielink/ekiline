<?php
/**
 * Template Name: Blank page
 * Template Post Type: post, page
 *
 * Para uso en casos de mostrar contenido personalizado, sin formato.
 * For use in cases of displaying custom content, without formatting.
 *
 * @link https://developer.wordpress.org/themes/template-files-section/page-template-files/
 *
 * @package WordPress
 * @subpackage ekiline
 */

?>

<!DOCTYPE html>
<html <?php language_attributes(); ?> itemscope itemtype="http://schema.org/WebPage">
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="profile" href="http://gmpg.org/xfn/11">
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>

		<?php the_content(); ?>

	<?php wp_footer(); ?>
	</body>
</html>
