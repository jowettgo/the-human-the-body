<?php

function can_meeting_reply() {
    if(bbp_current_user_can_access_create_reply_form()) {

        if(can_access_meeting()) {
            return true;
        }
        return false;
    }
    return false;
}
function can_access_meeting() {
    if(is_user_logged_in()) :
        global $current_user;
        if($current_user->has_cap('join_meeting')) :
            /* user can join the meeting */
            return true;
        /* return false, user cannot join */
        endif;
        return false;
    endif;
    return false;
}
function is_premium() {
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
 ?>
