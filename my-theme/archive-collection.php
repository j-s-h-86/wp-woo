<?php
get_header();

?>
<div class="centered-site">
    <h2>Archive collection</h2>
    <?php
    $terms = get_terms(array(
        'taxonomy' => 'connoisseur',
        'hide_empty' => true,
        'parent' => 0
    ));
    ?>

    <?php get_my_taxonomies();

    ?>
</div>

<form method="GET" action="">
    <label for="startDate">Startdatum:</label>
    <input type="date" name="startDate" id="startDate" />

    <label for="endDate">Slutdatum:</label>
    <input type="date" name="endDate" id="endDate" />

    <label for="taxonomy-term">Välj kategori:</label>
    <select name="connoisseur_term" id="taxonomy-term">
        <option value="">Välj kategori</option>
        <?php
        $terms = get_terms(array(
            'taxonomy' => 'connoisseur',
            'hide_empty' => true,
            'parent' => 0
        ));
        if ($terms && !is_wp_error($terms)) {
            foreach ($terms as $term) {
                echo '<option value="' . esc_attr($term->slug) . '">' . esc_html($term->name) . '</option>';
            }
        }
        ?>
    </select>
    <input type="submit" value="Filtrera" />
</form>


<?php

function handle_date_and_taxonomy_filter()
{
    $args = array(
        'post_type' => 'collection',
        'posts_per_page' => -1,
    );

    $tax_query = array();
    $date_query = array();

    if (isset($_GET['connoisseur_term']) && !empty($_GET['connoisseur_term'])) {
        $term_slug = sanitize_text_field($_GET['connoisseur_term']);
        $tax_query[] = array(
            'taxonomy' => 'connoisseur',
            'field' => 'slug',
            'terms' => $term_slug
        );
    }

    if (isset($_GET['startDate']) && !empty($_GET['startDate'])) {
        $start_date = sanitize_text_field($_GET['startDate']);
        $date_query['after'] = $start_date;
    }

    if (isset($_GET['endDate']) && !empty($_GET['endDate'])) {
        $end_date = sanitize_text_field($_GET['endDate']);
        $date_query['before'] = $end_date;
    }

    if (!empty($date_query)) {
        $date_query['inclusive'] = true;
        $args['date_query'] = array($date_query);
    }

    if (!empty($tax_query)) {
        $args['tax_query'] = $tax_query;
    }

    display_collections($args);
}

?>

<?php
handle_date_and_taxonomy_filter();
?>
</div>
<?php
get_footer();