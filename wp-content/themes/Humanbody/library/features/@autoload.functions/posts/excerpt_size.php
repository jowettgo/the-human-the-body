<?php
/**
 * custom excerpt size limiter function
 * @method excerpt_size
 * @param  integer      $legth max number of characters
 * @return string 
 */
function excerpt_size($legth = 150) {
    global $post;
    return substr(strip_tags($post->post_content), 0, $legth).'...';
}
 ?>
