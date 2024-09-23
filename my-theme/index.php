<?php
if (is_tax() || is_category()) {
	// Get the current term
	$term = get_queried_object();

	// Output the term name
	echo '<h1>' . esc_html($term->name) . '</h1>';
}
get_header();
?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">

		<?php
		if (have_posts()):

			the_content();
		endif;
		?>

		<?php
		get_footer();
