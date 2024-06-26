<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package ripro-v2
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="app" class="site">
	<?php get_template_part( 'template-parts/global/header-menu' );
	if ( ripro_v2_show_hero() ) {
		get_template_part( 'template-parts/global/hero' );
	}
	if ( is_archive() || is_search() ) {
		get_template_part( 'template-parts/global/term-bar' );
	}?>
	<main id="main" role="main" class="site-content">