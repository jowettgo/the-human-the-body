<?php
/**
 * static class used for contact email saving and listing
 */
class save_contact_email
{
    /* table name installed, the database prefix is added in the installer */
    private static $table_name = 'contact_emails';
    /**
     * add sent mail to database for future reference
     * @method add
     * @param  array $contact [description]
     * @return boolean           true/false if the email was saved in the database
     */
    public static function add($contact = array()) {
        global $wpdb;
        /* construct email vars */
        list($name, $email, $message) = $contact;

        /* insert row with email info */
        $insert = $wpdb->insert(
            $wpdb->prefix.self::$table_name,
            array(
                'date' => current_time( 'mysql' ),
                'name' => $name,
                'email' => $email,
                'message' => $message
            )
        );
        return $insert;
    }
    /**
     * get all the saved emails from database as raw object rows
     * @method get_all
     * @return array      returns array of row objects
     */
    public static function get_all()
    {
        global $wpdb;
        /* construct email vars */
        $wp_table_name = $wpdb->prefix.self::$table_name;
        /* get all */
        $sql = "SELECT * FROM `{$wp_table_name}`";
        $rows = $wpdb->get_results($sql);
        return $rows;
    }
    /* Installer
    ------------------------------------------------------------ */
	/**
	 * @method install()  install the notifications database with a custom table just for this
	 * @return boolean   true on successfull install or false on error
	 */
	public static function install() {
		/* table name */
		$_table = self::$table_name;
		/* table installer class */
		$installer = new spinal_table_install();
		/* #NOTE
		the sql statement needs [table_name] and [charset]
		the installer will take care of the rest
		 */
		$sql = "CREATE TABLE IF NOT EXISTS [table_name] (
				  `ID` mediumint(9) NOT NULL AUTO_INCREMENT COMMENT 'contact email id',
				  `date` DATETIME NOT NULL COMMENT  'time of the sending email',
                  `name` VARCHAR(50) NOT NULL  COMMENT 'senders name',
                  `email` VARCHAR(50) NOT NULL  COMMENT 'senders email',
				  `message` text NOT NULL COMMENT 'email message',

				  PRIMARY KEY (`ID`),
				  UNIQUE KEY `ID` (`ID`)

			  ) [charset];";
			 /* returns true or false on install success or error */
			 return $installer->install($sql,$_table);
	}
}
/* installer only runs once so no worries */
save_contact_email::install();
/* add contact observer action */

add_action('contact-observer', 'save_email_in_db', $priority = 10, $args = 1);
function save_email_in_db($contact_email) {
    save_contact_email::add($contact_email);
}

 ?>
