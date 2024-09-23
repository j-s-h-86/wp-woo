<?php
/**
 * Template Name: Page Template
 */
?>

<div>
    <h1><?php the_title(); ?></h1>
    <div class="custom-page-content">
        <?php the_content(); ?>
    </div>
</div>

<?php get_footer(); ?>