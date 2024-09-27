<?php
get_header(); ?>

<div class="centered-site">
	<?php
	if (have_posts()):

		while (have_posts()):
			the_post();
			?>
			<h1><?php the_title(); ?></h1>
			<?php
			the_content();
		endwhile;
	endif;
	?>
</div>
<?php
get_footer();
