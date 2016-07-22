<?php
/* team custom post type */
$idea = new spinal_post();
$idea->type = 'affection';
$idea->singular = 'Affection';
$idea->multiple = 'Affections';
$idea->slug = 'body-affection';
$idea->capability = 'page';
$idea->taxonomies = false;
$idea->index = 15;
$idea->icon = 'dashicons-universal-access';
$idea->support = array( 'title', 'thumbnail');
$idea->hooks();



?>
