<?php
/* add forum custom template, hardcoded in bbpress/content-single-forum.php  */
$forumMetaboxes = array(
	array(
		'name'    => __('Image'),
		'desc'    => 'featured image of the category',
		'id'      => 'image',
		'type'    => 'file',
		//'repeatable' => true,
	),
	array(
		'name' => __('Gradient Start'),
		'desc' => 'Category color on image overlay gradient',
		'id'   => 'color1',
		'type' => 'colorpicker'
	),
	array(
		'name' => __('Gradient End'),
		'desc' => 'Category color on image overlay gradient',
		'id'   => 'color2',
		'type' => 'colorpicker'
	),
	array(
		'name' => __('Color Article links and title'),
		'desc' => 'Category color for listed articles',
		'id'   => 'color-article-text',
		'type' => 'colorpicker'
	),
	array(
		'name' => __('Ideas Template For posts'),
		'desc' => '',
		'id'   => 'post-ideas',
		'type' => 'checkbox'
	),
);
$forum = new spinal_metaboxes();
$forum->metaboxes = $forumMetaboxes;
$forum->prefix = "category_meta";
$forum->type = 'category'; // show on this type of post
$forum->title = 'Category Settings';
$forum->hooks();
/* end post featured metabox */
