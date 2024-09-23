<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package storefront
 */

?><!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

	<?php wp_head(); ?>
	<!-- Google Tag Manager -->
	<script>(function (w, d, s, l, i) {
			w[l] = w[l] || []; w[l].push({
				'gtm.start':
					new Date().getTime(), event: 'gtm.js'
			}); var f = d.getElementsByTagName(s)[0],
				j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : ''; j.async = true; j.src =
					'https://www.googletagmanager.com/gtm.js?id=' + i + dl; f.parentNode.insertBefore(j, f);
		})(window, document, 'script', 'dataLayer', 'GTM-M3DFRZST');</script>
	<!-- End Google Tag Man
</head>

<body <?php body_class(); ?>>
	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-M3DFRZST" height="0" width="0"
			style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->
	<header id="masthead" class="site-header" role="banner">
		<div class="site-branding">
			<!-- <h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>"
					rel="home"><?php bloginfo('name'); ?></a></h1> -->
			<p class="site-description"><?php bloginfo('description'); ?></p>
		</div><!-- .site-branding -->

		<nav id="site-navigation" class="main-navigation" role="navigation">
			<?php
			wp_nav_menu(array(
				'theme_location' => 'header-menu',
				'menu_id' => 'primary-menu',
			));
			?>
		</nav><!-- #site-navigation -->
	</header><!-- #masthead -->

	<?php wp_body_open(); ?>


	<div id="page" class="hfeed site">

		<div id="content" class="site-content" tabindex="-1">
			<div class="col-full">