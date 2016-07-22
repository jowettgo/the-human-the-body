<?php
function short_description() {
    global $post;
    $meta = get_post_meta($post->ID);
    echo $meta['short-description'][0];
}
 ?>
