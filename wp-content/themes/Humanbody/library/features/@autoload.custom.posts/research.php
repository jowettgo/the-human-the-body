<?php
/* team custom post type */
$idea = new spinal_post();
$idea->type = 'research';
$idea->singular = 'Research';
$idea->multiple = 'Research';
$idea->slug = 'research';
$idea->capability = 'page';
$idea->taxonomies = false;
$idea->index = 10;
$idea->icon = 'dashicons-awards';
$idea->support = array( 'title', 'thumbnail');
$idea->hooks();

?>
