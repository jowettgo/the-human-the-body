<?php
/**
 *
 */


class spinal_subscribe_csv_download
{

    private $table_name;


    function __construct()
    {
        self::db();
    }
    public function get_all() {
        if(wp_get_current_user()->caps['administrator']) :

            global $wpdb;
            $table = $this->table_name;
            $query = "SELECT * FROM $table WHERE 1 LIMIT 0, 100";
            $rows = $wpdb->get_results($query);
            $download = get_template_directory_uri().'/library/csv/subscribers.php';
            echo "<a href='$download' class='button-primary' style='line-height: 37px;text-align: center;margin-top:0px; margin-bottom: 20px;'>Download CSV</a>";
            echo '<h2>'.count($rows).' Total</h2><hr>';

            foreach ($rows as $email) {
                echo $email->email."<hr/>";
            }

        endif;
    }
    public function check($email) {
        global $wpdb;
        $table = $this->table_name;
        $query = "SELECT * FROM  `$table` WHERE  `email` LIKE  '$email' LIMIT 0 , 30";
        $rows = $wpdb->get_results($query, 'OBJECT');
        if(count($rows) < 1) :
            return false;
        else :
            return true;
        endif;

    }
    public function add($email) {
        global $wpdb;

        $table_name = "{$wpdb->prefix}spinal_subscribers";
        $data = array('email' => $email);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) :
          return "Invalid email";

        else :
            if(!self::check($email)) :
                $wpdb->insert( $table_name, $data);
                return 'Thank You For Subscribing';
            else :
                return 'already subscribed';
            endif;
        endif;
    }
    function db() {
        global $wpdb;
        $table_name = "{$wpdb->prefix}spinal_subscribers";
        $this->table_name = $table_name;

        if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            //table is not created. you may create the table here.
            $create_table_query = "
                    CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}spinal_subscribers` (
                    `ID` int(11) NOT NULL AUTO_INCREMENT,
                     `email` varchar(50) NOT NULL,
                     PRIMARY KEY (`ID`)
                    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
            ";
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $create_table_query );
        }


    }
}
