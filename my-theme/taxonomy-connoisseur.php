<?php
get_header();

$term = get_queried_object();
echo '<h1>' . esc_html($term->name) . '</h1>';

echo '<p>Nybörjare? Intresserad? Kännare? Här finns öl för alla nivåer av erfarenhet.</p>';

if (have_posts()):
    echo '<ul>';
    while (have_posts()):
        the_post();
        ?>
        <li>
            <a href="<?php the_permalink(); ?>">
                <?php the_title(); ?>
            </a>
        </li>
        <?php
    endwhile;
    echo '</ul>';
else:
    echo '<p>Inga inlägg hittades i denna taxonomi.</p>';
endif;

get_footer();
?>