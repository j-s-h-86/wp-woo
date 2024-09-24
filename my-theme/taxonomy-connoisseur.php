<?php
get_header();

?>
<div class="centered-site">
    <?php
    $term = get_queried_object();
    echo '<h2>' . esc_html($term->name) . '</h2>';

    echo '<p>Nybörjare? Intresserad? Kännare?<br>Här finns öl och paket för alla nivåer av erfarenhet.</p>';
    if (have_posts()):
        echo '<ul>';
        while (have_posts()):
            the_post();
            ?>
            <li>
                <a href="<?php the_permalink(); ?>">
                    <h4><?php the_title(); ?></h4>
                </a>
            </li>
            <?php
        endwhile;
        echo '</ul>';
    else:
        echo '<p>Inga inlägg hittades i denna taxonomi.</p>';
    endif;
    ?>
</div>
<?php

get_footer();
?>