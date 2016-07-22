<?php
/* team custom post type */
$idea = new spinal_post();
$idea->type = 'idea';
$idea->singular = 'Idea';
$idea->multiple = 'Ideas';
$idea->slug = 'post-idea';
$idea->capability = 'page';
$idea->taxonomies = true;
$idea->index = 9;
$idea->icon = 'dashicons-lightbulb';
$idea->support = array( 'title', 'editor', 'thumbnail', 'comments');
$idea->hooks();

$idea = new spinal_post();
$idea->type = 'psychotherapist';
$idea->singular = 'Psychotherapist';
$idea->multiple = 'Psychotherapist';
$idea->slug = 'post-psychotherapist';
$idea->capability = 'page';
$idea->taxonomies = false;
$idea->index = 9;
$idea->icon = 'dashicons-lightbulb';
$idea->support = array( 'title', 'editor', 'thumbnail', 'comments');
$idea->hooks();


?>
