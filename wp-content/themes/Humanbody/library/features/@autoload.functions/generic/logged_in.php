<?php

function logged_in() {
    if(wp_get_current_user()->ID) :
        return true;
    endif;
    return false;
}
?>
