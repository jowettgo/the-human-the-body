<?php

function subforums($forumParentID) {
	$args = array(
		'post_type' => 'forum',
		'post_parent' => $forumParentID,
		'posts_per_page' => -1
	);

	$subforums = new WP_Query($args);

	foreach ($subforums->posts as $forum) {
		$childs[] = $forum->ID;
	}
	wp_reset_query();
	return $childs;
}

 ?>
