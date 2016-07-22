<?php

/* add forum custom template, hardcoded in bbpress/content-single-forum.php  */
$forumMetaboxes = array(
	array(
		//'name'    => __('News'),
		'desc'    => 'template',
		'id'      => 'template',
		'type'    => 'select',
		'options' => array(
			'forum' => 'Forum',
			'support' => 'Support',
			'meetings' => 'Meetings'
		),
		//'repeatable' => true,
	),
);
$forum = new spinal_metaboxes();
$forum->metaboxes = $forumMetaboxes;
$forum->prefix = "forum-template";
$forum->type = 'forum'; // show on this type of post
$forum->title = 'Template';
$forum->hooks();
/* end post featured metabox */


 ?>
