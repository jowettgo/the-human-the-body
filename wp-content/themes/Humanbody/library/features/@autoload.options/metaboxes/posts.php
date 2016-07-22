<?php

/*
Post metaboxes, custom features
------------------------------------------------------------------------------------------ */

/* add post featured metabox, to be displayed in the header slider section  */
$postFeaturedMetaboxes = array(
	array(
		//'name'    => __('News'),
		'desc'    => 'Add the article to featured in the category',
		'id'      => 'post-featured',
		'type'    => 'checkbox',
		//'repeatable' => true,
	),
	array(
		//'name'    => __('News'),
		'desc'    => 'Show author on article',
		'id'      => 'post-author',
		'type'    => 'checkbox',
		//'repeatable' => true,
	),
);
$postsFeatured = new spinal_metaboxes();
$postsFeatured->metaboxes = $postFeaturedMetaboxes;
$postsFeatured->prefix = "featured";
$postsFeatured->type = 'post'; // show on this type of post
$postsFeatured->title = 'featured';
$postsFeatured->hooks();
/* end post featured metabox */

/* add post featured metabox, to be displayed in the header slider section  */
$postFeaturedMetaboxes = array(
	array(
		//'name'    => __('News'),
		'desc'    => 'short description of the post (visible after the title)',
		'id'      => 'short-description',
		'type'    => 'textarea',
		//'repeatable' => true,
	),
);
$postsFeatured = new spinal_metaboxes();
$postsFeatured->metaboxes = $postFeaturedMetaboxes;
$postsFeatured->prefix = "short-description";
$postsFeatured->type = 'post'; // show on this type of post
$postsFeatured->title = 'short-description';
$postsFeatured->hooks();
/* end post featured metabox */

 ?>
