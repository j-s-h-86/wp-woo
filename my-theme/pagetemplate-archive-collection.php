<?php
/*
Template Name: Collection Archive
*/
get_header();
?>

<?php get_my_taxonomies(); ?>
<div class="centered-site">

    <?php
    if (have_posts()):

        while (have_posts()):
            the_post();

            ?>
            <a href="<?php echo (get_permalink(get_the_ID())); ?>">
                <div>
                    <?php
                    the_title();
                    ?>
                </div>
            </a>
            <?php
        endwhile;

    endif;
    ?>
</div>

<?php
get_footer();
?>