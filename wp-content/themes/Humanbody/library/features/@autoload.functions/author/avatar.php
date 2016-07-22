<?php

function spinal_avatar_src($authorID = false) {
    if(!$authorID)  :
        $user = new user_info();
        $info = $user->get();
        return $info['avatar'];
    endif;


}
?>
