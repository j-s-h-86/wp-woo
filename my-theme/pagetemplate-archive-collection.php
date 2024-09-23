<?php
/*
Template Name: Collection Archive
*/
get_header();
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">

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
        // get_template_part('archive', 'collection');
        $terms = get_terms(array(
            'taxonomy' => 'connoisseur',
            'hide_empty' => true,
            'parent' => 0
        ));

        if ($terms) {
            foreach ($terms as $term) {
                echo ('<a href="' . get_term_link($term) . '">' . $term->name . '</a>');
            }
        }
        ?>



    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();
?>