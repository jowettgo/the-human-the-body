<?php
add_action( 'wp_footer', 'join_unjoin_meeting_js' , 99999);
add_action( 'wp_ajax_join', 'ajax_join_meeting' );
add_action( 'wp_ajax_unjoin', 'ajax_unjoin_meeting' );





function join_unjoin_meeting_js() {
?>
<script type="text/javascript" >
jQuery(document).ready(function($) {
    var url = "<?php echo admin_url('admin-ajax.php'); ?>";

    //start join meeting
	$('.join-meeting').click(function(){

		if ($(this).hasClass('active')) {

			$(this).removeClass('active').text('join');

		}else{
			$(this).addClass('active').text('joined');
		}
	});



    var selectorid = '.join-meeting';
    var updateId = '.total-attending';

    details_join_unjoin(url, selectorid, updateId, true);

    var selectorid = '#join-meeting-ajax';

    var updateId = '.specifications.update-ajax';

    details_join_unjoin(url, selectorid, updateId, false);

	//end join meeting
    function details_join_unjoin(url, selectorid, updateID, list) {


        $(selectorid).on({
            click: function() {
                selector = $(this);
                var activetext = selector.attr('data-text-active');
                var inactivetext = selector.attr('data-text-inactive');
                if(list == true) {
                    var updateSelector = selector.parent().parent().find(updateID);
                }
                if(!list) {
                    var updateSelector = $(updateID);
                }
                var action = selector.data('action');

                if(!action) {
                    if(selector.hasClass('unjoin-meeting')) {
                        action = 'unjoin';
                    }
                    else {
                        action = 'join';

                    }
                }
                var topic = selector.attr('data-id');
                /* ajax method */
                var ajaxSend = jQuery.ajax({
                    url: url,
                    method: "POST",
                    data: {id: topic, action : action}
                });
                /* on success */
                ajaxSend.done(function(returnedData, textStatus, jqXHR) {
                    action = action ? action : selector.data('action');

                    /* get active and inactive text */



                    if(action == 'join') {
                        selector.addClass('unjoin-meeting active');
                        if(!list) {
                            selector.children('.join-text').text(activetext);
                        }
                        else {
                            selector.text(activetext);
                        }

                        selector.data('action', 'unjoin');
                    }
                    else {
                        selector.removeClass('unjoin-meeting active');

                        if(!list) {
                            selector.children('.join-text').text(inactivetext);
                        }
                        else {
                            selector.text(inactivetext)
                        }
                        selector.data('action', 'join');
                    }
                    updateSelector.text(returnedData);
                        console.log(returnedData, textStatus);
                });
                /* on fail */
                ajaxSend.fail(function(jqXHR, textStatus, errorThrown) {
                    console.log(errorThrown);
                });
            }

        })
    }
})
</script>
<?php

}
function ajax_join_meeting() {
    /* sanitize post */
    $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    $meetingID = $_POST['id'];
    echo join_meeting($meetingID);
    die();
}


function ajax_unjoin_meeting() {
    /* sanitize post */
    $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    $meetingID = $_POST['id'];
    echo unjoin_meeting($meetingID);
    die();
}





/* Functions
-------------------------------------------------*/

function can_join() {
    if(is_user_logged_in()) :
        global $current_user;
        if($current_user->has_cap('premium')) :
            /* user can join the meeting */
            return true;
        /* return false, user cannot join */
        endif;
        return false;
    endif;
    return false;
}

function joined($meetingID) {
    global $current_user;
    $data = get_post_meta($meetingID);
    $participants = unserialize($data['joined'][0]);
    if(is_array($participants) && in_array($current_user->ID, $participants)) :
        return true;
    endif;
    return false;
}

function joined_user($meetingID, $current_user_id) {
    global $current_user;
    $data = get_post_meta($meetingID);
    $participants = unserialize($data['joined'][0]);
    if(is_array($participants) && in_array($current_user_id, $participants)) :
        return true;
    endif;
    return false;
}

function join_meeting($meetingID) {
	if(!joined($meetingID)) :
    //if(can_join() && !joined($meetingID)) :
        global $current_user;
        $data = get_post_meta($meetingID);
        $participants = $data['joined'][0];
        $participants = unserialize($participants);
        $participants[] = $current_user->ID;
        update_post_meta($meetingID, 'joined', $participants);
        return total_joined($meetingID);
    endif;
    return false;
}
function unjoin_meeting($meetingID) {
	if(joined($meetingID)) :
    // if(can_join() && joined($meetingID)) :
        global $current_user;
        $data = get_post_meta($meetingID);
        $participants = unserialize($data['joined'][0]);
        $key = array_search($current_user->ID, $participants);
        unset($participants[$key]);
        update_post_meta($meetingID, 'joined', $participants);
        return total_joined($meetingID);
    endif;
    return false;

}

function total_joined($topicID) {
    $meta = get_post_meta($topicID);
    $participants = count(unserialize($meta['joined'][0]));
    return $participants;
}

?>
