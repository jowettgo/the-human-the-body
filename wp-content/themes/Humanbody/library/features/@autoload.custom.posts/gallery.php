<?php
/* team custom post type */
$idea = new spinal_post();
$idea->type = 'galleries';
$idea->singular = 'Gallery';
$idea->multiple = 'Galleries';
$idea->slug = 'galleries';
$idea->capability = 'page';
$idea->taxonomies = false;
$idea->index = 13;
$idea->icon = 'dashicons-format-gallery';
$idea->support = array( 'title', 'thumbnail', 'comments');
$idea->hooks();

?>
