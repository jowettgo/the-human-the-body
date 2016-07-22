<?php

/* add forum custom template, hardcoded in bbpress/content-single-forum.php  */
$forumMetaboxes = array(
	array(
		'id'      => 'location',
		'type'    => 'text',
		'attributes'  => array(
			'placeholder' => 'city, country'
		)
	),
	array(
		'name' => 'Date',
		'id'   => 'date',
		'type' => 'text_date',
		'date_format' => 'd.m.Y',
		'attributes'  => array(
			'placeholder' => '31-02-2015',
		),
	),
	array(
		'id'      => 'time',
		'type'    => 'text_time',
		'attributes'  => array(
			'placeholder' => 'hh:mm'
		)
	),
);
$forum = new spinal_metaboxes();
$forum->metaboxes = $forumMetaboxes;
$forum->prefix = "topic-location";
$forum->type = 'topic'; // show on this type of post
$forum->title = 'Location, Date and Time';
$forum->hooks();
/* end post featured metabox */


/* add forum custom template, hardcoded in bbpress/content-single-forum.php  */
$forumMetaboxes = array(
	array(
		//'name'    => __('News'),

		'id'      => 'type',
		'type'    => 'radio_inline',
		'default' => 'topic',
		 'options' => array(
		 	'topic' => 'Topic',
			'meeting' => 'Meeting',
		),

		//'repeatable' => true,
	),
);
$forum = new spinal_metaboxes();
$forum->metaboxes = $forumMetaboxes;
$forum->prefix = "topic-type";
$forum->type = 'topic'; // show on this type of post
$forum->title = 'Topic Type';
$forum->hooks();
/* end post featured metabox */

 ?>
