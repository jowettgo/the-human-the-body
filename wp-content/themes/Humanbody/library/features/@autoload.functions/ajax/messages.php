<?php
/* ajax load more messages */
add_action( 'wp_ajax_moremessages', 'ajax_load_more_messages' );
/* ajax remove message */
add_action( 'wp_ajax_removemessages', 'ajax_remove_messages');
/* ajax highlight message */
add_action( 'wp_ajax_highlightmessages', 'ajax_highlight_messages');
/* ajax remove conversation */
add_action('wp_ajax_removeconversation', 'ajax_remove_conversation');

function ajax_remove_conversation() {
    /* purge everything on post vars */
    $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    /* instantiate the object */
    $mcr = new user_messages();
    /* decrypt the id of the user */
    $room_id = $mcr->decrypt($_POST['rid']);
    /* modify the hidden status of the message for the current user on all messages in the conversation */
    if($room_id > 0) :
        /* hide the message */
        $mcr->delete_conversation($room_id);
    endif;
    die();
}
function ajax_remove_messages() {
    /* purge everything on post vars */
    $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    /* instantiate the object */
    $mcr = new user_messages();
    /* decrypt the id of the user */
    $message_id = $mcr->decrypt($_POST['mid']);

    /* modify the hidden status of the message for the current user */
    if($message_id > 0) :
        /* hide the message */
        $mcr->hide_message($message_id);
    endif;
    die();
}

function ajax_highlight_messages() {
    /* purge everything on post vars */
    $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    /* instantiate the object */
    $mcr = new user_messages();
    /* decrypt the id of the user */
    $message_id = $mcr->decrypt($_POST['mid']);
	$val = $_POST['status'];

    /* modify the hidden status of the message for the current user */
    if($message_id > 0) :
        /* hide the message */
        $mcr->highlight_message($message_id, $val);
    endif;
    die();
}

function ajax_load_more_messages() {
    /* purge everything on post vars */
    $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

    $mcr = new user_messages();
    /* ajax form date collection */
    $room_id = $mcr->decrypt($_POST['rid']);
    $start = $_POST['start'];
    $end = 5;

    /* get all the messages */
    $more = $mcr->get_messages_from_room($room_id, $end, $start);

    /* check if there are any messages */
    if(is_array($more) && count($more) > 0) :
        /* get current logged in user */
        $current_user = new user_info();
        $currentInfo = $current_user->get();
        /* loop through all the messages and list them for inclusion */
        foreach ($more as $message) {
            /* get all the vars needed */
            $content = $message->message;
            $user = new user_info();
            $info = $user->get($message->user_id);
            $avatar = $info['avatar'];
            /* time handling */
            $date = date_today($message->date);
            $day = $date[0];
            $time = $date[1];


            if($currentInfo['ID'] == $message->user_id) :
                $align = 'current-user';
            else:
                $align = 'conversation-user';
            endif;
            ?>
            <li class="clearfix <?php echo $align ?>">
                <div class="message-info">
                    <div class="items">
						<p>edit</p>
						<div class="delete-message <?php echo $message->hightlighted == 1?'center':''; ?>" data-mid="<?php echo $mcr->encrypt($message->ID) ?>"></div>
						<?php if($message->hightlighted == 0): ?>
							<div class="highlight-message" data-mid="<?php echo $mcr->encrypt($message->ID) ?>" data-val="1"><span>highlight</span></div>
						<?php endif; ?>
					</div>
                    <div class="time-of-message">
                        <?php echo $day ?> <br>
                        <?php echo $time ?>
                    </div>
                    <div class="messenger-inner">
                        <img src="<?php echo $avatar ?>" alt="img">
                    </div>
                </div>
                <div class="message-body">
                    <p>
                        <?php echo $content ?>
                    </p>
                </div>
            </li>
            <?php
        }
    endif;
    die();
}


 ?>
