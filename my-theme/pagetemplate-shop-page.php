<?php
/**
 * Template Name: Shop Page
 */
get_header();
?>

<?php
function display_collections()
{
    $collections = get_posts(array(
        'post_type' => 'collection',
        'posts_per_page' => -1,
        'post_status' => 'publish'
    ));

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
        echo get_the_post_thumbnail($collection->ID, 'thumbnail');
        echo '<h3>' . esc_html(get_the_title($collection->ID)) . '</h3>';
        echo '</a>';
        echo '<p>Totalpris: ' . wc_price($total_price) . '</p>';

        echo '<form method="post" class="buy-now-form">';
        echo '<input type="hidden" name="collection_id" value="' . esc_attr($collection->ID) . '">';
        echo '<button type="submit" class="buy-now-button">Köp nu</button>';
        echo '</form>';

        echo '</div>';
    }
    echo '</div>';
}
?>

<h3?php if (have_posts()): while (have_posts()): the_post(); the_content(); endwhile; endif; ?>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $new_id = wp_insert_post(array(
            'post_type' => 'collection',
            'post_title' => sanitize_text_field($_POST['title']),
            'post_content' => wp_kses($_POST['content'], array()),
            'post_status' => 'publish',
            'post_author' => get_current_user_id()
        ));

        $products = array_map('intval', $_POST['selectedProducts']);
        update_post_meta($new_id, '_collection_products', $products);
        ?>
        <a href="<?php echo get_permalink($new_id); ?>">
            <h3>Visa din nya kollektion</h3>
        </a>
        <?php
    }
    ?>

    <?php display_collections(); ?>
    <?php do_action('categories_on_page'); ?>
    <?php do_action('get_products_in_categories'); ?>
    <?php do_action('create_collection_form'); ?>
    <?php do_action('form_on_page'); ?>

    <?php get_footer(); ?>