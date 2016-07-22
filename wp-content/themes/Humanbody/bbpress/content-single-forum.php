<?php

/**
 * Single Forum Content Part
 *
 * @package bbPress
 * @subpackage Theme
 */

$meta = get_post_meta($post->ID);
$template = $meta['template'][0];


if($post->post_type == 'forum' && $post->post_parent == 0) :
	$type = 'main-forum';
elseif($post->post_type == 'forum' && $post->post_parent > 0) :
	$type = 'sub-forum';
endif;

/* forum switch */
switch ($type) {
	case 'main-forum':

			/* switch view based on template */
			switch ($template) :
				case 'forum':
						/*  forum */
						include_once('forum/content-single-forum-forum.php');
					break;
				case 'support':
						/* adds a hook to member premium pages */
						do_action('member-premium-header-hook');
						/* forum */
						include_once('forum/content-single-forum-support.php');
					break;
				case 'meetings':
						/* adds a hook to member premium pages */
						do_action('member-premium-header-hook');
						/* forum */
						include_once('forum/content-single-forum-meetings.php');
					break;
				default:
					# code...
					break;
			endswitch;
			/* end switch based on template */

		break;
	/* sub forums switch */
	case 'sub-forum':
			/* switch view based on template */
			switch ($template) :
				case 'forum':
						/* topics in the sub forum */
						include_once('forum/content-single-forum-sub-forum.php');
					break;
				case 'support':
						/* adds a hook to member premium pages */
						do_action('member-premium-header-hook');
						/* topics in the sub forum */
						include_once('forum/content-single-forum-sub-support.php');
					break;

				default:
					# code...
					break;
			endswitch;
			/* end switch based on template */
		break;
	default:

		break;
}




?>
