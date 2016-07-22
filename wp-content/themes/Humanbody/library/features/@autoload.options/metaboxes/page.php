<?php
$pageMetaboxes = array(
	array(
		//'name'    => __('News'),
		'desc'    => 'short description',
		'id'      => 'short-description',
		'type'    => 'textarea',
		//'repeatable' => true,
	),
);
$page = new spinal_metaboxes();
$page->metaboxes = $pageMetaboxes;
$page->prefix = "page-short-description";
$page->type = 'page'; // show on this type of post
$page->title = 'Page short description';
$page->hooks();

 ?>
