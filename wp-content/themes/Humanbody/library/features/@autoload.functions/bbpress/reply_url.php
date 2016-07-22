<?php
function reply_url( $args = array(), $postID) {



		// Get the reply to use it's ID and post_parent
		$reply = bbp_get_reply( bbp_get_reply_id( (int) $r['id'] ) );

		// Bail if no reply or user cannot reply
		if ( empty( $reply ) || ! bbp_current_user_can_access_create_reply_form() )
			return;

		// Build the URI and return value
		//$uri = remove_query_arg( array( 'bbp_reply_to' ) );
		//$uri = add_query_arg( array( 'bbp_reply_to' => $reply->ID ) );
        $url = get_permalink($postID).'?bbp_reply_to='.$reply->ID;
		$uri = wp_nonce_url( $url, 'respond_id_' . $reply->ID );
		$uri = $uri . '#new-post';

		return $uri;
	}
 ?>
