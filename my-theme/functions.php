<?php

function my_theme_enqueue_styles()
{
    wp_enqueue_style('my-theme-style', get_stylesheet_uri());

    wp_enqueue_style('mt-fonts', 'https://fonts.googleapis.com/css2?family=Bitter:ital,wght@0,100..900;1,100..900&display=swap');
}
add_action('wp_enqueue_scripts', 'my_theme_enqueue_styles');


function navbar()
{
    register_nav_menu('header-menu', __('Header Menu'));
}
add_action('init', 'navbar');

function buy_collection()
{
    if (isset($_POST['collection_id'])) {
        $collection_id = intval($_POST['collection_id']);
        $selected_products = get_post_meta($collection_id, '_collection_products', true);

        if (!empty($selected_products)) {
            foreach ($selected_products as $product_id) {
                WC()->cart->add_to_cart($product_id);
            }
        }

        wp_redirect(wc_get_cart_url());
        exit;
    }
}
add_action('template_redirect', 'buy_collection');

add_action('form_on_page', 'create_collection_form');

function create_collection_form()
{

    $products = wc_get_products(array());

    ?>
    <form method="POST">
        <input class="newCollectionName" name="title" placeholder="Namn på ny kollektion" /><br>
        <textarea class="newCollectionDescription" name="content" placeholder="Beskrivning av ny kollektion"></textarea><br>
        <?php $args = array(
            'post_type' => 'product',
            'posts_per_page' => -1,
            'post_status' => 'publish',
        );

        $products = new WP_Query($args);

        if ($products->have_posts()) {
            ?>
            <select name="selectedProducts[]" multiple>
                <?php
                while ($products->have_posts()) {
                    $products->the_post();
                    $product = wc_get_product(get_the_ID());
                    $id = $product->get_id();
                    $title = $product->get_name();
                    ?>
                    <option value="<?php echo esc_attr($id); ?>"><?php echo esc_html($title); ?></option>
                    <?php
                }
                ?>
            </select>
            <?php
            wp_reset_postdata();
        } else {
            echo 'Inga produkter hittades.';
        }
        ?>
        <input type="submit" class="button" value="Skapa kollektion" />
    </form>
    <?php
}

add_action('categories_on_page', 'get_products_in_categories');

function get_products_in_categories()
{
    ?>
    <div class="products-by-category">
        <?php
        $product_categories = get_terms('product_cat', array('hide_empty' => false));

        foreach ($product_categories as $category) {
            if ($category->slug === 'uncategorized') {
                continue;
            }
            ?>
            <div class="product-category">
                <h2><?php echo $category->name; ?></h2>
                <div class="products">
                    <?php
                    $args = array(
                        'post_type' => 'product',
                        'posts_per_page' => -1,
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'product_cat',
                                'field' => 'term_id',
                                'terms' => $category->term_id,
                            ),
                        ),
                    );
                    $products = new WP_Query($args);

                    if ($products->have_posts()) {
                        while ($products->have_posts()) {
                            $products->the_post();
                            $product = wc_get_product(get_the_ID());
                            ?>
                            <div class="product">
                                <a href="<?php the_permalink(); ?>">
                                    <div class="thumb_container"><?php the_post_thumbnail('medium'); ?></div>
                                    <h3><?php the_title(); ?></h3>
                                    <span class="price"><?php echo $product->get_price_html(); ?></span>
                                </a>
                                <form method="post" class="buy-now-form">
                                    <input type="hidden" name="product_id" value="<?php echo esc_attr($product->get_id()); ?>">
                                    <input type="hidden" name="add" value="add_to_cart">
                                    <button type="submit" class="addToCartButton">Köp nu</button>
                                </form>

                            </div>
                            <?php
                        }
                        wp_reset_postdata();
                    } else {
                        echo '<p>No products found in this category.</p>';
                    }
                    ?>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
    <?php
}

function add_collection_to_cart()
{
    if (isset($_POST['add']) && $_POST['add'] === 'add_to_cart' && !empty($_POST['products'])) {
        foreach ($_POST['products'] as $product) {
            WC()->cart->add_to_cart($product);
        }
        wp_safe_redirect(wc_get_cart_url());
        exit;
    }
}

add_action('template_redirect', 'add_collection_to_cart');

function add_item_to_cart()
{
    if (isset($_POST['add']) && $_POST['add'] === 'add_to_cart' && !empty($_POST['product_id'])) {
        $product_id = intval($_POST['product_id']);
        WC()->cart->add_to_cart($product_id);
        wp_safe_redirect(wc_get_cart_url());
        exit;
    }
}
add_action('template_redirect', 'add_item_to_cart');

function display_collections($args = null)
{
    if (!$args) {
        $args = array(
            'post_type' => 'collection',
            'posts_per_page' => -1,
            'post_status' => 'publish'
        );
    }
    $collections = get_posts($args);

    if (empty($collections)) {
        echo '<p>Inga kollektioner hittades.</p>';
        return;
    }

    echo '<div class="collectionContainers">';

    foreach ($collections as $collection) {
        $selected_products = get_post_meta($collection->ID, '_collection_products', true);


        if (!is_array($selected_products)) {
            $selected_products = array();
        }

        if (empty($selected_products)) {
            echo '<p>No products found for collection ID: ' . $collection->ID . '</p>';
            continue;
        }

        $total_price = 0;
        foreach ($selected_products as $product_id) {
            $product = wc_get_product($product_id);
            if ($product) {
                $total_price += $product->get_price();
            }
        }

        $collection_permalink = get_permalink($collection->ID);
        echo '<div class="collection">';
        echo '<a href="' . esc_url($collection_permalink) . '">';
        // echo get_the_post_thumbnail($collection->ID, 'thumbnail');
        if (has_post_thumbnail($collection->ID)) {
            echo get_the_post_thumbnail($collection->ID, 'thumbnail');
        } else {
            echo '<img src="https://m.media-amazon.com/images/I/61gJexHvUgS._AC_UF1000,1000_QL80_.jpg" alt="Default Thumbnail">';
        }
        echo '<h3>' . esc_html(get_the_title($collection->ID)) . '</h3>';
        echo '</a>';
        echo '<p>Totalpris: ' . wc_price($total_price) . '</p>';

        echo '<form method="post" class="buy-now-form">';
        echo '<input type="hidden" name="collection_id" value="' . esc_attr($collection->ID) . '">';
        echo '<button type="submit" class="addToCartButton">Köp nu</button>';
        echo '</form>';

        echo '</div>';
    }
    echo '</div>';
}

function get_my_taxonomies()
{
    $terms = get_terms(array(
        'taxonomy' => 'connoisseur',
        'hide_empty' => true,
        'parent' => 0
    ));

    if ($terms) {
        echo '<div class="taxTermsContainer">
        <p class="categoriesP">Kategorier: </p>' ?>
        <?php
        foreach ($terms as $term) {
            echo ('<a href="' . get_term_link($term) . '">' . $term->name . '</a>');
        }
    }
}
