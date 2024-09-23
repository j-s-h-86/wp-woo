<?php
/*
Plugin Name: Signature Plugin
*/

function add_post_signature($content)
{
    $signature = '<p style="font-style: italic; color: #000000;"> ... men drick ansvarsfullt, brorsan./ <strong>KornOchMalt</strong></p>';

    if (is_single()) {
        $content .= $signature;
    }

    return $content;
}

// add_filter('the_content', 'add_post_signature');



?>