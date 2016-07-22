<?php
//archive main
global $wp_query;
global $post;
$type = $wp_query->query_vars['post_type'];
switch ($type) {
    case 'research':
        get_template_part('page', 'research');
        break;

    default:

        break;
}


?>
