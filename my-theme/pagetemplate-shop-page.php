<?php
/**
 * Template Name: Shop Page
 */
get_header();
?>
<?php get_my_taxonomies(); ?>
<div class="centered-site">

    <?php if (have_posts()):
        while (have_posts()):
            the_post();
            ?>
            <h2><?php the_title(); ?></h2>
            <?php
            the_content();
        endwhile;
    endif; ?>

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
    <h3><?php do_action('categories_on_page'); ?></h3>
    <?php do_action('get_products_in_categories'); ?>
    <?php do_action('create_collection_form'); ?>
    <?php do_action('form_on_page'); ?>
</div>
<?php get_footer(); ?>