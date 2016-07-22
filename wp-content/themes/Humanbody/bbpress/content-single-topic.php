<?php

/**
 * Single Topic Content Part
 *
 * @package bbPress
 * @subpackage Theme
 */

$topic = $post;

//echo $post->post_type;
$meta = get_post_meta($topic->ID);
$type = $meta['type'][0];
$topictype = $post->post_type;

switch ($type) {
	case 'meeting':
			include_once('topic/content-single-topic-meeting.php');
		break;
	case 'topic':
			include_once('topic/content-single-topic-forum.php');
		break;
	default:

		break;
}


?>
