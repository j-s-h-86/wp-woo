<?php


get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">

		<?php
		while (have_posts()):
			the_post();

			the_content();

		endwhile; // End of the loop.
		?>

		<?php
		get_footer();
