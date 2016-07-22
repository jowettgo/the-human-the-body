<?php
/**
 * `friendlist class for all one`s friends need :)
 */
class friends_list extends __user
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
	public $table = 'friendslist';

    /**
     * on class initialize add current user id and meta
     * @method __construct
     */
	function __construct() {
		/* store id of logged in user */
		$this->ID = parent::id();
		/* store the current user meta */
		$this->meta = parent::meta();
	}
    /**
     * generates the add to friends list
     * @method get_add_link
     * @param  string       $user_id  encrypted user id
     * @return string       returns the link of add to friends
     */
    function get_add_link($user_id) {
        $user_id = self::encrypt($user_id);
        return page('my-account-friendlist').'?add=u&u='.$user_id;
    }
    /**
     * generates the remove from friends list
     * @method get_remove_link
     * @param  string       $user_id  encrypted user id
     * @return string       returns the link of remove from friends
     */
    function get_remove_link($user_id) {
        $user_id = self::encrypt($user_id);
        return page('my-account-friendlist').'?remove=u&u='.$user_id;
    }
    /**
     * check if suplied id of user is friend or not
     * @method is
     * @param  integer  $user_id  another user id
     * @return boolean            true if user is in friendlist or false
     */
    public function is($user_id = false)
    {
        if($user_id) :
            global $wpdb;
            /* table names */
    		$tablename = $wpdb->prefix.$this->table;
            /* current logged in user id */
            $loggedin_id = $this->ID;
            /* get friends */
            $friendslist = $this->get();
            /* add the user id to the friend list */
            if($friendslist[$user_id] > 0) :
                return true;
            endif;
            return false;
        endif;
        /* no user id so return a message */
        return false;
    }
    /**
     * add friend to friends list of the logged in user
     * @method add
     * @param  integer $user_id
     */
    public function add($user_id = false)
    {
        /* only add the friend to the list if there is a user id to be added */
        if($user_id) :
            global $wpdb;
            /* table names */
    		$tablename = $wpdb->prefix.$this->table;
            /* current logged in user id */
            $loggedin_id = $this->ID;
            /* get friends */
            $friendslist = $this->get();
            /* add the user id to the friend list */
            $friendslist[$user_id] = $user_id;

            /* update key value pairing */
            $data = array(
                'friends' => serialize($friendslist),
            );
            /* what row to be affected */
            $where = array(
                'user_id' => $loggedin_id
            );
            /* return updated rows (1) or (0) */
            return $wpdb->update( $tablename, $data, $where);
        endif;
        /* no user id so return a message */
        return 'no user id to be added';
    }
    /**
     * [remove description]
     * @method remove
     * @param  integer $user_id [description]
     * @return string/integer   returns an info message or the number of rows affected (0/1)
     */
    public function remove($user_id = 0)
    {
        /* only remove the friend to the list if there is a user id to be added */
        if($user_id) :
            global $wpdb;
            /* tablename */
            $tablename = $wpdb->prefix.$this->table;
            /* current logged in user id */
            $loggedin_id = $this->ID;

            /* get friends */
            $friendslist = $this->get();
            /* remove the friend with userr id from the friend list retrieved */
            foreach ($friendslist as $fk => $f_id) :
                if($f_id == $user_id) :
                    unset($friendslist[$f_id]);
                endif;
            endforeach;
            /* update key value pairing */
            $data = array(
                'friends' => serialize($friendslist),
            );
            /* what row to be affected */
            $where = array(
                'user_id' => $loggedin_id
            );
            /* return updated rows (1) or (0) */
            return $wpdb->update( $tablename, $data, $where);
        endif;
        /* no user id so return a message */
        return 'no user id to be added';
    }
    /**
     * get the friends list of the current logged in user
     * @method get
     * @return array     list of friends (user id`s)
     */
    public function get()
    {
        global $wpdb;
        /* table names */
		$tablename = $wpdb->prefix.$this->table;
        /* get logged in id of the current user */
        $loggedin_id = $this->ID;
        /* get the friendslist row of the current user */
        $sql = "SELECT * FROM `$tablename` WHERE `user_id`=$loggedin_id";
        $friendslist_mfl = $wpdb->get_results($sql);
        if(!isset($friendslist_mfl[0])) :
				$wpdb->insert(
					$tablename,
					array(
						'user_id' => $loggedin_id,
						'friends' => serialize(array()),
					)
				);
            return array();
        else :
            /* and and return the unserialized version of the friends */
            $friendslist = $friendslist_mfl[0]->friends;
            return unserialize($friendslist);
        endif;
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
    /* Installer
    ------------------------------------------------------------ */
    /**
	 * INSTALL TABLES
	 * @method install
	 */
	public function install() {
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		/* table names */
		$tablename = $this->table;

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
				  `user_id` int(10) NOT NULL COMMENT 'user id',
				  `friends` LONGTEXT NOT NULL COMMENT 'user friends list as a serialized value',
				  PRIMARY KEY (`ID`),
				  UNIQUE KEY `ID` (`ID`)
			  ) [charset];";

		$res = $installer->install($sql,$tablename);
    }
}
$friendslist = new friends_list();
$friendslist->install();



add_action('member-friends-list', 'friends_list_add');
add_action('member-friends-list', 'friends_list_remove');
/* add friend */
function friends_list_add() {
    /* used to encrypt/decrypt */
    $list = new friends_list();
    /* get user id and decrypt it */
    $user_id = $list->decrypt($_GET['u']);
    if($_GET['add'] && $user_id > 0) :
        /* add to friends */
        $list->add($user_id);
        do_action('add-to-friends-list', $user_id);
        wp_redirect( page('my-account-friendlist') );
    endif;
}
/* remove friend */
function friends_list_remove() {
    /* used to encrypt/decrypt */
    $list = new friends_list();
    /* get user id and decrypt it */
    $user_id = $list->decrypt($_GET['u']);
    if($_GET['remove'] && $user_id > 0) :
        /* add to friends */
        $list->remove($user_id);
        wp_redirect( page('my-account-friendlist') );
    endif;
}





 ?>
