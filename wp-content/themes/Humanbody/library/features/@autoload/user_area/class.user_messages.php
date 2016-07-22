<?php
/**
 * handle all the messages logic
 */
class user_messages extends __user
{
	/**
	 * $ID  current user id
	 * @var int
	 */
	public $ID = false;
	/**
	 * $meta  user meta options
	 * @var array
	 */
	public $meta = array();
	/**
	 * $tables  tables to be used
	 * @var array
	 */
	public $table_names = array('chat'=>'messagemaster', 'line'=>'messageline');

	function __construct() {
		/* store id of logged in user */
		$this->ID = parent::id();
		/* store the current user meta */
		$this->meta = parent::meta();

	}
	/**
	 * get_all get all messages for current logged in member
	 * @return [type] [description]
	 */
	public function get_all() {
		global $wpdb;
		/* message master room */
		$table_names = $this->table_names;
		$tablenameChat = $wpdb->prefix.$table_names['chat'];
		/* get current logged in user */
		$user_id = $this->ID;

	}
	public function get_rooms()
	{
		global $wpdb;
		/* logged in user id */
		$loggedin_ID = $this->ID;
		/* message master room */
		$table_names = $this->table_names;
		$tablenameChat = $wpdb->prefix.$table_names['chat'];

		$query = "SELECT * FROM $tablenameChat WHERE `from_id`= $loggedin_ID OR `to_id` = $loggedin_ID";
		$rows = $wpdb->get_results($query);
		return $rows;
	}
	public function get_room($room_id)
	{
		if($this->can_view($room_id)) :
			global $wpdb;
			/* logged in user id */
			$loggedin_ID = $this->ID;
			/* message master room */
			$table_names = $this->table_names;
			$tablenameChat = $wpdb->prefix.$table_names['chat'];


			$query = "SELECT * FROM $tablenameChat WHERE `ID`= $room_id";
			$rows = $wpdb->get_results($query);

			return $rows;
		endif;
		return false;
	}
	public function last_message($room_ID)
	{
		global $wpdb;
		/* logged in user id */
		$loggedin_ID = $this->ID;
		/* message master room */
		$table_names = $this->table_names;
		$tablenameChatline = $wpdb->prefix.$table_names['line'];

		if($this->can_view($room_ID)) :

			$room = $this->get_room($room_ID);
			$room = $room[0];
			$otherUserID = $room->from_id == $this->ID ? $room->to_id : $room->from_id;


			$query = "SELECT * FROM $tablenameChatline WHERE `chat_id` = $room_ID AND `user_id`=$otherUserID AND (`hidden_for_user` != $loggedin_ID AND `hidden_for_other` != $loggedin_ID) ORDER BY `date` DESC LIMIT 0, 1";

			$rows = $wpdb->get_results($query);
			return $rows[0] ? $rows[0] : false;
		else :
			return false;
		endif;
		return false;
	}
	public function unread($room_id)
	{
		global $wpdb;
		$loggedin_ID = $this->ID;
		/* message master room */
		$table_names = $this->table_names;
		$tablenameChat = $wpdb->prefix.$table_names['line'];

		$query = "SELECT COUNT(*) FROM $tablenameChat WHERE (`user_id` <> $loggedin_ID AND `chat_id` = $room_id AND `status` = 0)";
		$rows = $wpdb->get_results($query);
		$rows = (array)$rows[0];
		return $rows['COUNT(*)'];
	}

	/* #FIXME not good yet bug in selecting the user only message rooms */

	public function global_unread()
	{
		global $wpdb;
		$loggedin_ID = $this->ID;
		/* message line */


		$table_names = $this->table_names;
		$tablenameChatline = $wpdb->prefix.$table_names['line'];
		$rooms = $this->get_rooms();
		foreach ($rooms as $room) {
			$room_id = $room->ID;

			$query = "SELECT COUNT(*) FROM $tablenameChatline WHERE
			(`user_id` <> $loggedin_ID
			AND `chat_id` = $room_id
			AND `status` = 0
			AND (`hidden_for_user` != $loggedin_ID AND `hidden_for_other` != $loggedin_ID))";
			$rows = $wpdb->get_results($query);
			$rows = (array)$rows[0];
			$count += $rows['COUNT(*)'];
		}
		return $count;
	}

	
	/**
	 * clear all the unread messages in the current message room
	 * @method clear_unread
	 * @param  [type]       $room_id [description]
	 * @return [type]                [description]
	 */
	function clear_unread($room_id) {
		global $wpdb;
		$table_names = $this->table_names;
		$tablenameChatlines = $wpdb->prefix.$table_names['line'];
		
		$room = $this->get_room($room_id);
		$room = $room[0];
		$otherUserID = $room->from_id == $this->ID ? $room->to_id : $room->from_id;
		$data = array(
			'status' => 1
		);
		$where = array(
			'chat_id' => $room_id,
			'user_id' => $otherUserID
		);
		
		$where2 = array(
			'post_id' => $room_id,
			'from_id' => $otherUserID
		);
		
		//uodate notificaions too
		$notifications_table = $wpdb->prefix.'notifications';
		$wpdb->update( $notifications_table, $data, $where2);
		
		//
		return $wpdb->update( $tablenameChatlines, $data, $where);
	}
	/* Hide the message from the user logged in
	---------------------------------------------------------------------------- */
	/**
	 * hide message based on the idof the message, it changes the status of being hidden or visible for logged in user
	 * @method hide_message
	 * @param  integer      $messsage_id  message id to be updated
	 * @return boolean                  true or false
	 */
	public function hide_message($message_id = false) {
		if($message_id != false) :
			/* compose the tablename */
			global $wpdb;
			$table_names = $this->table_names;
			$tablenameChatlines = $wpdb->prefix.$table_names['line'];
			/* get the message object */
			$message = $this->get_message($message_id);
			/* get the message room id for checking the access */
			$room_id = $message->chat_id;
			/* check the room privileges */

			if($this->can_view($room_id)) :

				$user_id = $this->ID;
				/* check for logged in user */
				if($user_id > 0) :

					/* search key value pairing based on author or conversation user */
					/* hide for current user */
					if($message->user_id == $user_id) :
						$data = array(
							'hidden_for_user' => $user_id,
						);
					/* hide for the second user */
					else :
						$data = array(
							'hidden_for_other' => $user_id,
						);
					endif;
					/* ubdate the message with this id */
					$where = array(
						'ID' => $message_id
					);
					/* return updated rows (1) or (0) */
					return $wpdb->update( $tablenameChatlines, $data, $where);
				endif;
			endif;
		endif;
	}


	/**
	 * highlight message based on the idof the message, it changes the status of being hidden or visible for logged in user
	 * @method hide_message
	 * @param  integer      $messsage_id  message id to be updated
	 * @return boolean                  true or false
	 */
	public function highlight_message($message_id = false, $status) {
		if($message_id != false) :
			/* compose the tablename */
			global $wpdb;
			$table_names = $this->table_names;
			$tablenameChatlines = $wpdb->prefix.$table_names['line'];
			/* get the message object */
			$message = $this->get_message($message_id);
			/* get the message room id for checking the access */
			$room_id = $message->chat_id;
			/* check the room privileges */

			if($this->can_view($room_id)) :

				$data = array(
					'hightlighted' => $status,
				);
				/* ubdate the message with this id */
				$where = array(
					'ID' => $message_id
				);
				/* return updated rows (1) or (0) */
				return $wpdb->update( $tablenameChatlines, $data, $where);
			endif;
		endif;
	}

	public function delete_conversation($room_id)
	{
		/* compose the tablename */
		global $wpdb;
		$table_names = $this->table_names;
		$tablenameChatline = $wpdb->prefix.$table_names['line'];

		if($this->can_view($room_id)) :
			$messages = $this->get_messages_from_room($room_id);
			$user_id = $this->ID;
			/* check for logged in user */
			if($user_id > 0) :

				foreach ($messages as $message) :

				/* search key value pairing based on author or conversation user */
				/* hide for current user */
				if($message->user_id == $user_id) :
					$data = array(
						'hidden_for_user' => $user_id,
					);
				/* hide for the second user */
				else :
					$data = array(
						'hidden_for_other' => $user_id,
					);
				endif;
				/* ubdate the message with this id */
				$where = array(
					'ID' => $message->ID
				);
				/* return updated rows (1) or (0) */
				$wpdb->update( $tablenameChatline, $data, $where);
				endforeach;
			endif;
		endif;

	}
	/**
	 * [get_message description]
	 * @method get_message
	 * @param  integer     $mid  message id
	 * @return object           returns database message object
	 */
	public function get_message($mid = false) {
		if($mid != false) {
				global $wpdb;
				/* compose the tablename */
				$table_names = $this->table_names;
				$tablename = $wpdb->prefix.$table_names['line'];
				/* sql statement */
				$sql = "SELECT * FROM $tablename WHERE `ID` = $mid";
				/* meesage result */
				$message = $wpdb->get_results($sql);
				/* extract the object from results array */
				$message = $message[0];
				return $message;
		}
	}
	/**
	 * get secure link of view the conversation
	 * @method get_room_secure
	 * @param  integer          $roomID  room id
	 * @return string                    url of conversation
	 */
	public function get_room_secure($roomID)
	{
		$pageurl = page('my-account-message-room');
		return $pageurl.'?hb='.$this->encrypt($roomID);
	}
	/**
	 * get all the message from the room providing the id of the room and the count fo maximum messages
	 * @method get_messages_from_room
	 * @param  [type]                 $room_ID [description]
	 * @param  integer                $total   [description]
	 * @return [type]                          [description]
	 */
	public function get_messages_from_room($room_ID, $total	= 5, $start=0)
	{
		if($room_ID > 0 && $this->can_view($room_ID)) :
			global $wpdb;
			/* logged in user id */
			$loggedin_ID = $this->ID;
			/* message master room */
			$table_names = $this->table_names;
			$tablenameChatline = $wpdb->prefix.$table_names['line'];
			if($this->can_view($room_ID)) :
				$query = "SELECT * FROM $tablenameChatline WHERE `chat_id` = $room_ID AND `hidden_for_user` != $loggedin_ID AND `hidden_for_other` != $loggedin_ID ORDER BY `date` DESC LIMIT $start, $total";

		        $rows = $wpdb->get_results($query);
				return $rows;
			else :
				return false;
			endif;
		endif;
		return false;
	}
	
	public function get_messages_count_in_room($room_ID)
	{
		if($room_ID > 0 && $this->can_view($room_ID)) :
			global $wpdb;
			/* logged in user id */
			$loggedin_ID = $this->ID;
			/* message master room */
			$table_names = $this->table_names;
			$tablenameChatline = $wpdb->prefix.$table_names['line'];

			if($this->can_view($room_ID)) :
				$query = "SELECT * FROM $tablenameChatline WHERE `chat_id` = $room_ID AND `hidden_for_user` != $loggedin_ID AND `hidden_for_other` != $loggedin_ID ORDER BY `date` DESC";

		        $rows = $wpdb->get_results($query);
				return count($rows);
			else :
				return false;
			endif;
		endif;
		return false;
	}
	
	/**
	 * function to check the user if he can view the current table room
	 * @method can_view
	 * @param  [type]   $room_ID [description]
	 * @return [type]            [description]
	 */
	public function can_view($room_ID)
	{
		global $wpdb;
		/* logged in user id */
		$loggedin_ID = $this->ID;
		/* message master room */
		$table_names = $this->table_names;
		$tablenameChat = $wpdb->prefix.$table_names['chat'];


		$query = "SELECT * FROM $tablenameChat WHERE (`from_id`= $loggedin_ID AND `ID` = $room_ID) OR (`to_id` = $loggedin_ID AND `ID` = $room_ID)";

        $rows = $wpdb->get_results($query);

		if(count($rows) == 1) {
			return true;
		}
		else {
			return false;
		}
	}
	public function get_send_link($user_id) {
		$enc_id = self::encrypt($user_id);
		$roomID = $this->room_exists($user_id);
		if($roomID) {
			return page('my-account-message-room').'?hb='.self::encrypt($roomID);
		}
		return page('my-account-message-room').'?new=r&u='.$enc_id;
	}
	public function room_exists($other_user_id)
	{
		global $wpdb;
		/* logged in user id */
		$loggedin_ID = $this->ID;
		/* message master room */
		$table_names = $this->table_names;
		$tablenameChat = $wpdb->prefix.$table_names['chat'];


		$query = "SELECT * FROM $tablenameChat WHERE (`from_id`= $loggedin_ID AND `to_id` = $other_user_id) OR (`to_id` = $loggedin_ID AND `from_id` = $other_user_id)";

        $rows = $wpdb->get_results($query);

		if(count($rows) == 1) {
			$row = $rows[0];
			return $row->ID;
		}
		return false;

	}
	/**
	 * send send a message to a user based on the target id of the user
	 * @param  int   $id  the user id to send it to
	 * @param  str   $message  the message to send it to the user
	 * @return bool  true on send, false on error
	 */
	public function send($message,$room_ID) {
		if($room_ID > 0) :
			global $wpdb;
			/* message master room */
			$table_names = $this->table_names;
			$tablenameChatline = $wpdb->prefix.$table_names['line'];
				$wpdb->insert(
					$tablenameChatline,
					array(
						'chat_id' => $room_ID,
						'user_id' => $this->ID,
						'message' => $message,
						'date' => current_time( 'mysql' ),
					)
				);
				$message_id = $wpdb->insert_id;
				if($message_id > 0) :
					do_action('add_message_notification', $message_id);
					return true;
				endif;
				return false;
		endif;
		return false;
	}
	/**
	 * create room with a user if it doesn`t exist
	 * @param  int   $to_id  the user id to create the new room with
	 * @return bool  true on send, false on error
	 */
	public function create_room($to_id) {
		if($to_id > 0) :
			global $wpdb;
			$loggedin_ID = $this->ID;
			/* message master room */
			$table_names = $this->table_names;
			$tablenameChat = $wpdb->prefix.$table_names['chat'];

			$query = "SELECT * FROM $tablenameChat WHERE (`from_id`= $loggedin_ID AND `to_id` = $to_id) OR (`from_id`= $to_id AND `to_id` = $loggedin_ID) ";
			$rows = $wpdb->get_results($query);
			$room = $rows[0];

			if(!is_object($room)) :
					$wpdb->insert(
						$tablenameChat,
						array(
							'from_id' => $this->ID,
							'to_id' => $to_id,
						)
					);
					return $wpdb->insert_id;
			else :
				wp_redirect($this->get_room_secure($room->ID));
				exit;
			endif;

		endif;
		return false;
	}
	/* SECURITY FUNCTIONS
	------------------------------------------------------------------- */
	/**
	 * decrypt string
	 * @method decrypt
	 * @param  [type]  $string [description]
	 * @param  string  $key    [description]
	 * @return [type]          [description]
	 */
	function decrypt($string, $key='humanbody_messages_2') {
		$result = '';
		$string = base64_decode($string);
		for($i=0; $i<strlen($string); $i++) {
			$char = substr($string, $i, 1);
			$keychar = substr($key, ($i % strlen($key))-1, 1);
			$char = chr(ord($char)-ord($keychar));
			$result.=$char;
		}
		return $result;
	}
	/**
	 * encrypt string
	 * @method encrypt
	 * @param  [type]  $string [description]
	 * @param  string  $key    [description]
	 * @return [type]          [description]
	 */
	function encrypt($string, $key='humanbody_messages_2') {
		$result = '';
		for($i=0; $i<strlen($string); $i++) {
			$char = substr($string, $i, 1);
			$keychar = substr($key, ($i % strlen($key))-1, 1);
			$char = chr(ord($char)+ord($keychar));
			$result.=$char;
		}
		return base64_encode($result);
	}
	/* INSTALL SCRIPT
	------------------------------------------------------------------- */
	/**
	 * INSTALL TABLES
	 * @method install
	 */
	public function install() {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();
		$table_names = $this->table_names;
		/* table names */
		$tablenameChat = $table_names['chat'];
		$tablenameChatline = $table_names['line'];

		/* table installer class */
		$installer = new spinal_table_install();
		/* #NOTE
		the sql statement needs [table_name] and [charset]
		the installer will take care of the rest
		 */
		/**
		 * Main chat table for master chats
		 */
		$sql = "CREATE TABLE IF NOT EXISTS [table_name] (
				  `ID` int(10) NOT NULL AUTO_INCREMENT COMMENT 'chat id',
				  `from_id` int(10) NOT NULL COMMENT 'user',
				  `to_id` int(10) NOT NULL COMMENT 'user',
				  PRIMARY KEY (`ID`),
				  UNIQUE KEY `ID` (`ID`)
			  ) [charset];";

		$res = $installer->install($sql,$tablenameChat);

		/**
		 * Chat lines to be used
		 * ID - chat line id
		 * chat_id - chat master id
		 * user_id - user that sent the message
		 * message - the message sent
		 * date - the datetime the message was sent
		 * status - if the message was read or not 0 / 1
		 */
		$sql = "CREATE TABLE IF NOT EXISTS [table_name] (
				  `ID` bigint(10) NOT NULL AUTO_INCREMENT COMMENT 'message id',
				  `chat_id` int(10) NOT NULL COMMENT 'user',
				  `user_id` int(10) NOT NULL COMMENT 'user',
				  `message` text NOT NULL COMMENT 'comment',
				  `date` DATETIME NOT NULL COMMENT  'time of the notification',
				  `status` int(1) NOT NULL DEFAULT '0' COMMENT 'notification status (read / unread)',
				  `hidden_for_user` TINYINT( 1 ) NOT NULL DEFAULT  '0' COMMENT 'hide this message for current user',
				  `hidden_for_other` TINYINT( 1 ) NOT NULL DEFAULT  '0' COMMENT 'hide this meesage for other user'
				  PRIMARY KEY (`ID`),
				  UNIQUE KEY `ID` (`ID`)
			  ) [charset];
		";
		$succcess = $installer->install($sql,$tablenameChatline);
	}
}

/* install notifications [it only runs once] */
$messages = new user_messages();
$messages->install();



/* add hacking room id security check with redirect */
add_filter('invalid_room_id', 'redirect_room_id_error');
function redirect_room_id_error() {
	$_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
	$mcr = new user_messages();
	$room_id = $mcr->decrypt($_GET['hb']);
	$user_id = $mcr->decrypt($_GET['u']);
	/* enter the room only if the current room can ve viewed or we create a new one */
	if($room_id > 0 && $mcr->can_view($room_id) || $_GET['new']) :
		if( $_GET['new'] && $user_id > 1) :
			$room = $mcr->create_room($user_id);
			wp_redirect( page('my-account-message-room').'?hb='.$mcr->encrypt($room));
		endif;
	else :
		/* not allowed */
		wp_redirect( page('my-account-messages'));
	endif;
}
?>
