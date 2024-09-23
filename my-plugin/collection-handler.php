<?php
/*
Plugin Name: Collection Handler
*/

function register_collections_post_type()
{
    $args = array(
        'label' => 'Collections',
        'public' => true,
        'supports' => array('title', 'editor', 'thumbnail'),
        'has_archive' => true,
        'show_in_rest' => true,
        'rewrite' => array('slug' => 'collections')
    );
    register_post_type('collection', $args);
}
add_action('init', 'register_collections_post_type');

function register_connoisseur_taxonomy()
{
    $args = array(
        'hierarchical' => true,
        'labels' => array(
            'name' => 'Connoisseurs',
            'singular_name' => 'Connoisseur',
        ),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_admin_column' => true,
        'show_in_quick_edit' => true,
        'show_tagcloud' => true,
        'show_in_rest' => true,
    );
    register_taxonomy('connoisseur', ['collection', 'product'], $args);
}
add_action('init', 'register_connoisseur_taxonomy');



function add_products_metabox()
{
    add_meta_box(
        'collection_products',
        'Välj produkter',
        'render_products_metabox',
        'collection',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'add_products_metabox');

function render_products_metabox($post)
{
    wp_nonce_field('save_collection_products', 'collection_products_nonce');

    $products = wc_get_products(array(
        'limit' => -1,
    ));

    $selected_products = get_post_meta($post->ID, '_collection_products', true);

    echo '<select name="selectedProducts[]" multiple style="width: 100%;">';
    foreach ($products as $product) {
        $selected = in_array($product->get_id(), (array) $selected_products) ? 'selected' : '';
        echo '<option value="' . esc_attr($product->get_id()) . '" ' . $selected . '>' . esc_html($product->get_name()) . '</option>';
    }
    echo '</select>';
}

?>