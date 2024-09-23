<?php

function my_theme_enqueue_styles()
{
    wp_enqueue_style('my-theme-style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'my_theme_enqueue_styles');


function navbar()
{
    register_nav_menu('header-menu', __('Header Menu'));
}
add_action('init', 'navbar');

// Hantera formulärsubmitt för "Köp nu"-knappen
function handle_buy_now_form_submission()
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
add_action('template_redirect', 'handle_buy_now_form_submission');

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
        <input type="submit" value="Skapa kollektion" />
    </form>
    <?php
}

add_action('categories_on_page', 'get_products_in_categories');

function get_products_in_categories()
{
    ?>
    <div class="products-by-category">
        <?php
        // Få alla produktkategorier
        $product_categories = get_terms('product_cat', array('hide_empty' => false));

        // Loopa genom varje kategori
        foreach ($product_categories as $category) {
            if ($category->slug === 'uncategorized') {
                continue;
            }
            ?>
            <div class="product-category">
                <h2><?php echo $category->name; ?></h2>
                <div class="products">
                    <?php
                    // WP_Query för att få produkter i denna kategori
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

                    // Loopa genom produkterna
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
                                    <button type="submit" class="buy-now-button">Köp nu</button>
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

function mt_handle_add_all_to_cart()
{
    if (isset($_POST['add']) && $_POST['add'] === 'add_to_cart' && !empty($_POST['products'])) {
        foreach ($_POST['products'] as $product) {
            WC()->cart->add_to_cart($product);
        }
        wp_safe_redirect(wc_get_cart_url());
        exit;
    }
}

add_action('template_redirect', 'mt_handle_add_all_to_cart');

add_action('init', 'handle_buy_now');
function handle_buy_now()
{
    if (isset($_POST['product_id'])) {
        $product_id = intval($_POST['product_id']);
        // Lägg till produkten i kundvagnen
        WC()->cart->add_to_cart($product_id);

        // Om du vill omdirigera till kundvagnen eller kassan efteråt
        wp_redirect(wc_get_cart_url());
        exit;
    }
}


