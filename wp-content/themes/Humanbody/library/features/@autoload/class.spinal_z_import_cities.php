<?php


/**
 * Handle csv imports
 */
class csv_import_cities
{

    private $table_city = 'sp_city';
    private $table_country = 'sp_country';
    /* add import to run on  */
    function __construct($file = false, $in = false)
    {
        /* run the installer, it only runs once if the database does not exist */
        $this->install();
        /* import the csv and add them to the database */
        if($file) :
            $this->import($file);
        endif;
    }

    /**
     * import csv file and insert the data as columns 3 columns data iso(1) -> country(1) -> cities(N)
     * @method import
     * @param  string   $file absolut path to the csv file
     * @param  integer  $in   assign a category number to the data
     * @return boolean  true or false on success or fail
     */
     function import($file) {
         /* check for the path of file */

         if($file) :
             $this->remove_all();
             $arrResult  = array();
             /* create file pointer resource */
             $handle     = fopen($file, "r");
             if(empty($handle) === false) :
                /* read line by line */
                while(($data = fgetcsv($handle, 1000, ",")) !== FALSE) :
                     //$this->insert($data[0], $in);
                     if($data[1]) {
                         $country = $data[1];
                     }
                     if($data[0]) {
                         $countryIso = $data[0];
                         $all[$country]['iso'] = $countryIso;
                     }



                     $all[$country]['cities'][] = $data[2];
                endwhile;
                /* close the file pointer to the file */
                fclose($handle);
                foreach ($all as $country => $data) :
                    $iso = $data['iso'];
                    $country_id = $this->insert_country($country, $iso);
                    if(is_array($data['cities']) && count($data['cities'])) :
                        foreach ($data['cities'] as $city) :
                            $this->insert_city($city, $country_id);
                        endforeach;
                     endif;
                endforeach;


                 return true;
             endif;
             return false;
         endif;
         return false;
     }

    /**
     * insert data from csv into the database
     * @method insert
     * @param  string $data csv line
     * @param  unteger $in   identifier
     * @return NULL
     */
    function insert_city($city, $country_id) {
        global $wpdb;
        $table = $wpdb->prefix.$this->table_city;
        $wpdb->insert(
        	$table,
        	array(
        		'city' => $city,
                'country' => $country_id
        	)
        );
    }
    /**
     * insert data from csv into the database
     * @method insert
     * @param  string $data csv line
     * @param  unteger $in   identifier
     * @return NULL
     */
    function insert_country($country, $iso) {
        global $wpdb;
        $table = $wpdb->prefix.$this->table_country;
        $wpdb->insert(
        	$table,
        	array(
        		'country' => $country,
                'iso' => $iso
        	)
        );
        return $wpdb->insert_id;
    }
    /**
     * delete all the rows in the category identifier
     * @method delete
     * @param  integer $in identifier for the rows to be deleted
     * @return NULL
     */
    public function remove_all()
    {
        global $wpdb;
        $cities = $wpdb->prefix.$this->table_city;
        $countries = $wpdb->prefix.$this->table_country;

        $wpdb->query("TRUNCATE TABLE `$cities`");
        $wpdb->query("TRUNCATE TABLE `$countries`");
    }
    /**
     * count all the csv fields in the database
     * @method count
     * @param  integer $in row category identifier
     * @return integer     return total count of interests
     */
    public function count()
    {
        global $wpdb;
        /* get the count of all the csv fields */
        $table = $wpdb->prefix.$this->table_city;
        $sql = "SELECT COUNT(*) FROM $table";
        $rows = $wpdb->get_results($sql);
        $rows = (array)$rows[0];
        return $rows['COUNT(*)'];
    }

    public function get_countries()
    {
        global $wpdb;
        $table = $wpdb->prefix.$this->table_country;
        $sql = "SELECT * FROM `$table` ORDER BY `country` ASC";
        $rows = $wpdb->get_results($sql);
        return $rows;
    }
    public function get_cities($country_id)
    {
        if($country_id) :
            global $wpdb;
            $table = $wpdb->prefix.$this->table_city;
            $sql = "SELECT * FROM `$table` WHERE `country`=$country_id ORDER BY `city` ASC";
            $rows = $wpdb->get_results($sql);
            return $rows;
        endif;
    }

    public function get_country_name_by_iso($iso = false)
    {
        global $wpdb;

        if($iso) :
            $iso = trim($iso);
            $table = $wpdb->prefix.$this->table_country;
            $sql = "SELECT * FROM `$table` WHERE `iso`='$iso'";
            $rows = $wpdb->get_results($sql);
            $name = $rows[0];
            return $name->country;
        endif;
    }
    public function get_country_iso_by_name($country_name = false)
    {
        global $wpdb;

        if($country_name) :
            $country_name = trim($country_name);
            $table = $wpdb->prefix.$this->table_country;
            $sql = "SELECT * FROM `$table` WHERE `country`='$country_name'";
            $rows = $wpdb->get_results($sql);
            $iso = $rows[0];
            return $iso->iso;
        endif;
    }
    public function get_cities_by_country_name($country_name = false)
    {
        global $wpdb;

        if($country_name) :
            $country_name = trim($country_name);
            $table = $wpdb->prefix.$this->table_country;
            $sql = "SELECT * FROM `$table` WHERE `country`='$country_name'";
            $rows = $wpdb->get_results($sql);
            $country_id = $rows[0];
            $country_id = $country_id->ID;

            if($country_id) :
                $table = $wpdb->prefix.$this->table_city;
                $sql = "SELECT * FROM `$table` WHERE `country`=$country_id ORDER BY `city` ASC";
                $rows = $wpdb->get_results($sql);
                return $rows;
            endif;
        endif;
    }
    public function get_cities_by_iso($iso)
    {
        if(strlen($iso) > 1) :
            global $wpdb;
            $table = $wpdb->prefix.$this->table_city;
            $country =  $wpdb->prefix.$this->table_country;
            $sql = "SELECT * FROM `$country` WHERE `iso`='$iso'";
            $rows = $wpdb->get_results($sql);
            $country_id = $rows[0];
            $country_id = $country_id->ID;
            /* only return cities that have a country id */
            if($country_id) :
                $sql = "SELECT * FROM `$table` WHERE `country`=$country_id ORDER BY `city` ASC";
                $rows = $wpdb->get_results($sql);
                return $rows;
            endif;
        endif;
    }
    /**
     * install the table needed for the csv fields
     * @method install
     * @return boolean
     */
    function install() {
        /* table name */
        $city_table = $this->table_city;
        $country_table = $this->table_country;
        /* table installer class */
        $installer = new spinal_table_install();
        /* #NOTE
        the sql statement needs [table_name] and [charset]
        the installer will take care of the rest
         */
        $sql = "CREATE TABLE IF NOT EXISTS [table_name] (
                  `ID` mediumint(9) NOT NULL AUTO_INCREMENT COMMENT 'city id',
                  `country` mediumint(9) NOT NULL COMMENT 'country ID',
                  `city` varchar(100) NOT NULL COMMENT 'city',
                  PRIMARY KEY (`ID`),
                  UNIQUE KEY `ID` (`ID`)
              ) [charset];";
             /* returns true or false on install success or error */
        $installer->install($sql, $city_table);

        $sql = "CREATE TABLE IF NOT EXISTS [table_name] (
                  `ID` mediumint(9) NOT NULL AUTO_INCREMENT COMMENT 'country id',
                  `country` varchar(100) NOT NULL COMMENT 'country name',
                  `iso` varchar(5) NOT NULL COMMENT 'country iso code',
                  PRIMARY KEY (`ID`),
                  UNIQUE KEY `ID` (`ID`)
              ) [charset];";
             /* returns true or false on install success or error */
        $installer->install($sql, $country_table);
    }

}
new csv_import_cities();
 ?>
