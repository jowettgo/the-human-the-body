<?php
function get_the_description() {
    global $post;
    $meta = get_post_meta($post->ID);
    return $meta['description'][0];
}
function the_description() {
    echo get_the_description();
}
 ?>
