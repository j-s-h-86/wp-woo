<?php
get_header();

?>
<div class="centered-site">
    <?php
    if (have_posts()) {
        while (have_posts()) {
            the_post();
            ?>
            <h1><?php the_title(); ?></h1>
            <div><?php the_content(); ?></div>

            <?php

            $selected_products = get_post_meta(get_the_ID(), '_collection_products', true);
            $total_price = 0;

            if (!empty($selected_products)) {
                echo '<div class="customCollection">';

                foreach ($selected_products as $product_id) {
                    // $product = get_post($product_id);
                    $product = wc_get_product($product_id);

                    if ($product) {
                        $product_title = esc_html($product->get_name());
                        $product_content = $product->get_description();
                        $product_thumbnail = get_the_post_thumbnail($product_id, 'thumbnail');
                        $product_price = $product->get_price();

                        $total_price += $product_price;
                        echo '<div class="itemInCustomCollection">';
                        echo '<h3>' . $product_title . '</h3>';
                        echo $product_thumbnail;
                        echo '<p>' . $product_content . '</p>';
                        echo 'Pris: ' . wc_price($product_price);
                        echo '</div>';
                    }
                }

                echo '</div>';
            } else {
                echo '<p>No products found in this collection.</p>';
            }
        }
        $taxonomies = get_object_taxonomies(get_post_type(), 'names');
        $all_terms = array();

        foreach ($taxonomies as $taxonomy) {
            $terms = get_the_terms(get_the_ID(), $taxonomy);
            if ($terms && !is_wp_error($terms)) {
                $all_terms = array_merge($all_terms, $terms);
            }
        }

        if ($all_terms) {
            echo '<h2 class="includedInCategory">Included in category:</h2>';
            echo '<ul>';
            foreach ($all_terms as $term) {
                echo '<li><a href="' . get_term_link($term) . '">' . esc_html($term->name) . '</a></li>';
            }
            echo '</ul>';
        } else {
            echo '<p>No terms found for this collection.</p>';
        }


        ?>
        <form method="post">
            <input type="hidden" name="add" value="add_to_cart">
            <?php
            foreach ($selected_products as $product) {
                echo '<input type="hidden" name="products[]" value="' . esc_attr($product) . '">';
            }
            ?>
            <button type="submit" class="addToCartButton">Köp nu: <?php echo wc_price($total_price) ?></button>
        </form>
        <?php
    }
    do_action('template_redirect');

    ?>
</div>
<p><?php get_my_taxonomies(); ?></p>
<?php
get_footer();


?>