<?php
add_action('bbp-meetings', 'bbp_new_meeting', 10, $accepted_args= 1);
function bbp_new_meeting($post) {
    if(logged_in()) {
        /* purge everything in the post */
        $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $title = $_POST['title'];
        $location = $_POST['location'];
        $date = $_POST['meeting-date'];
        $time = $_POST['time-date'];
        $short = $_POST['short-description'];
        $description = $_POST['description'];

    	if($title && $location && $date && $time && $short && $description) :
    		$user = wp_get_current_user();
    		$topic_data = apply_filters( 'bbp_new_topic_pre_insert', array(
    			'post_author'    => $user->ID,
    			'post_title'     =>$title,
    			'post_content'   => $description,
    			'post_status'    => 'publish',
    			'post_parent'    => $post->ID,
    			'post_date' => date('Y-m-d H:i:s'),
    			'post_date_gmt' =>  date('Y-m-d H:i:s'),
    			'post_type'      => bbp_get_topic_post_type(),
    			'comment_status' => 'open'
    		) );

    		// Insert topic
    		$topic_id = wp_insert_post( $topic_data );
    		update_post_meta($topic_id, 'type', 'topic');
    		update_post_meta($topic_id, '_bbp_forum_id', $post->ID);
    		update_post_meta($topic_id, '_bbp_topic_id', $topic_id);
    		update_post_meta($topic_id, '_bbp_author_ip', getUserIP());
    		update_post_meta($topic_id, '_bbp_last_active_time', date('Y-m-d H:i:s'));
    		update_post_meta($topic_id, '_bbp_reply_count', 0);
    		update_post_meta($topic_id, '_bbp_reply_count_hidden', 0);
    		update_post_meta($topic_id, '_bbp_last_active_id', 0);
    		update_post_meta($topic_id, '_bbp_voice_count', 1);
    		update_post_meta($topic_id, '_bbp_last_reply_id', 0);
            update_post_meta($topic_id, 'type', 'meeting');
            update_post_meta($topic_id, 'location', $location);
            update_post_meta($topic_id, 'date', $date);
            update_post_meta($topic_id, 'time', $time);
            update_post_meta($topic_id, 'short', $short);
            update_post_meta($topic_id, '_bbp_status', 'publish');


    		wp_redirect(get_the_permalink());
    	endif;
    }
}
 ?>
