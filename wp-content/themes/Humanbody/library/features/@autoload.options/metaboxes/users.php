<?php

$forumMetaboxes = array(
	array(
		'name' => __( '<hr><h2>Public Info</h2>'),
		'desc' => __( 'Public profile information'),
		'id'   => 'title_social',
		'type' => 'title',
		'repeatable' => false,
	),
	array(
		'name'    => __('Gender'),
		//'desc'    => '',
		'id'      => 'gender',
		'type'    => 'radio_inline',
		//'repeatable' => true,
		'options' => array(
			'male' => 'male',
			'female' => 'female',
			'other' => 'other'
		)
	),

	array(
		'name'    => __('Notification'),
		//'desc'    => '',
		'id'      => 'notification',
		'type'    => 'radio_inline',
		'options' => array(
			'1' => 'on',
			'2' => 'off',
		)
	),

	array(
		'name'    => __('Country'),
		//'desc'    => '',
		'id'      => 'country',
		'type'    => 'text',
		//'repeatable' => true,
	),
	array(
		'name'    => __('City'),
		//'desc'    => '',
		'id'      => 'city',
		'type'    => 'text',
		//'repeatable' => true,
	),
	array(
		'name'    => __('Date of Birth'),
		//'desc'    => '',
		'id'      => 'birthdate',
		'type'    => 'text_date_timestamp',
		//'repeatable' => true,
	),
);
$forum = new spinal_metaboxes();
$forum->metaboxes = $forumMetaboxes;
$forum->prefix = "user_meta";
$forum->type = 'user'; // show on this type of post
$forum->title = 'cafgdf';
$forum->hooks();


 ?>
