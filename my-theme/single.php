<?php

get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">

		<h1><?php the_title(); ?></h1>
		<?php
		the_content(); ?>

		<?php
		get_footer();
