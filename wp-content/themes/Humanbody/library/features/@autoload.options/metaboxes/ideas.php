<?php

/*
Post metaboxes, custom features
------------------------------------------------------------------------------------------ */

/* add post featured metabox, to be displayed in the header slider section  */
$ideasMetaboxes = array(
	array(
		'name'    => __('Fullname'),
		'desc'    => 'Author',
		'id'      => 'idea-author',
		'type'    => 'text',
		//'repeatable' => true,
	),
);
$ideas = new spinal_metaboxes();
$ideas->metaboxes = $ideasMetaboxes;
$ideas->prefix = "Details";
$ideas->type = 'idea'; // show on this type of post
$ideas->title = 'Details';
$ideas->hooks();
/* end post featured metabox */
/* add post featured metabox, to be displayed in the header slider section  */
$ideasMetaboxes = array(
	array(
		'name'    => __('Description'),
		'desc'    => 'Idea Description',
		'id'      => 'description',
		'type'    => 'textarea',
		//'repeatable' => true,
	),
);
$ideas = new spinal_metaboxes();
$ideas->metaboxes = $ideasMetaboxes;
$ideas->prefix = "description";
$ideas->type = 'idea'; // show on this type of post
$ideas->title = 'Description';
$ideas->hooks();
/* end post featured metabox */

 ?>
