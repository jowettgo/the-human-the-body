<?php
/**
 * @author Marius Moldovan
 *
 *
 * @var $name  | location of menu
 * @var $activeclass  | css class to be used if menu element is active
 * @var $dropdownclass  | css class to bes used on dropdown
 *
 * @method list_menu($navlocation)  - returns a menu with a li->a format, retrieved from a menu location
 * @method get($menulocation)  - return a menu/submenu with UL/LI/A/ul/li/a
 * @method split($logoUrl)     - return a split menu that has a logo in between menu/logo/menu
 */

class spinal_menu {
    /**
     * $name name of the menu
     * @var string
     */
    public $name = 'main-menu';

    /**
     * $class_active class to be used when the link is active, applied to the list item
     * @var string
     */
    public $activeclass = 'active';
    /**
     * $before add elements or whatever before the menu item link
     * @var string
     */
    public $before = '';
    /**
     * $first add elements or whatever on the first menu item
     * @var string
     */
    public $first = '';
    /**
     * $class_submenu class to be used for the dropdown of the menu element
     * @var string
     */
    public $dropdownclass = 'dropdown-menu';

    public $ul_class ='';


    /**
     * list_menu adds support for listing navigation menus as list items by providing the menu identifier
     * @method list
     * @param  string     $navName name of the menu
     * @return string     returns the menu in the form of the list
     */
    public function list_menu($navlocation) {
        /* check if we have a navigation menu name */
        if($navlocation) :
            /* get all nav menu registered */
            $locations = get_nav_menu_locations();
            /* select the menu from the list of locations */
            $menu = wp_get_nav_menu_object( $locations[$navlocation] );
            /* get the menu items as array */
            if($menu_items = wp_get_nav_menu_items($menu->term_id, array( 'order' => 'DESC' ) )) :
                /* loop through each of the menu items and list them as list item
                by removing the ul or ol we give space for custom listing
                 */
                foreach ($menu_items as $key => $item) :
                    /* store each menu item inside a var for returning it later */
                    $menu .= '<li><a href="'.$item->url.'">'.$item->title.'</a> </li>';
                /* end loop through */
                endforeach;
                /* return menu */
                return $menu;
            endif;
            /* no menu so return false */
            return false;
        endif;
        /* no menu name so return false */
        return false;
    }


    /**
     * genMenu
     * @method genMenu
     * @param  string  $menuOptional [description]
     * @return [type]                [description]
     */
    public function get($menuOptional = false) {
            /* get menu array only if we have no menu location defined */
    		if($menuOptional) $menu = self::get_menu_array($menuOptional);

            /* get the current page url */
    		$currentPage = self::curPageURL();
            /* set up the menu return container */
            $menureturn = "";
            //deg($menu);
            /* check if we have a menu */
            if($menu && count($menu) > 0) :
                $count = 0;

        		foreach($menu as $menuItem) :
                    /* check if current menu item is active or not */
                    if($currentPage == $menuItem["link"]) :
                        $selected = $this->activeclass; // active class defined in object
                    else :
                        $selected = ' default';
                    endif;
                    /* filter the link */
                    $link = self::filter_url($menuItem["link"]);

                    $before = $count == count($menu) -1 ? $this->first : $this->before;

                    /* this is the parent of the menu */
        			$menureturn .= '<li class="'.$this->ul_class.' '.$selected.'">'.$before."<a href='". $link ."' class=".$selected.">".$menuItem["title"]."</a>";
                    /* now check if we have a submenu */
                    if(array_key_exists("submenu", $menuItem)) :
                        /* submenu support */
                        $menureturn .= '<ul class="'.$this->dropdownclass.'">';
                        /* loop through each element of the submenu */
                        foreach ($menuItem["submenu"] as $submenuItem) :
                            /* get title and link */
                            $title = $submenuItem["title"];
                            $link  = self::filter_url($submenuItem["link"]);
                            /* append it to the menu return */
                            $menureturn .= "<li><a href='".$link."' class=".$selected.">".$title."</a></li>";
                        /* end loop through the submenu items */
                        endforeach;
                        /* close the submenu */
                        $menureturn .= "</ul>";
                    /* end check for submenu */
                    endif;
                    /* close current element in the menu */
                    $menureturn .= "</li>";

                $count++;
                /* end loop through the main menu elements */
        		endforeach;
                /* return the menu as a string */
        		return $menureturn;
            /* end check if we have a menu */
            endif;
            return false;
    }
    /**
     * generateSplitMenu
     * @method generateSplitMenu
     * @param  string            $logo [description]
     * @return [type]                  [description]
     */
    public function split($menuLocation='', $logo = '') {
        /* menu is a split menu */
    	$menu = self::get_menu_array($menuLocation, 0, true);
        /* gets menu left and menu right */
    	$menuLeft = $menu[0];
    	$menuRight = $menu[1];
        /* parse the lists as unordered list items */
    	$parsedMenuLeft = self::get($menuLeft);
    	$parsedMenuRight = self::get($menuRight);
        /* return the menu left logo right menu */
    	return '<ul>'.$parsedMenuLeft.'</ul>'.$logo.'<ul>'.$parsedMenuRight.'</ul>';
    }


    /**
     * get_menu_array get the menu as a multidimensional array
     * @method get_menu_array
     * @param  string         $menu_name   menu name to be used
     * @param  integer        $debug     default false, if set to true it will debug the menu by listing it as a formated array
     * @param  boolean         $split     set it to true to split the menu in two, for example a menu with 4 elements will result in two menus of 2 elements each
     * @return array                    returns a multidimensional array
     */
    public function get_menu_array($menu_name="", $debug=0, $split=false) {
    		$menu_name = $menu_name ? $menu_name : 'primary'; // Get the nav menu based on $menu_name (same as 'theme_location' or 'menu' arg to wp_nav_menu)
            /* this will store the menu */
            $menuarray = array();
            /* check if we have menus in the first place and the menu exists  */
    		if(($locations = get_nav_menu_locations()) && isset($locations[$menu_name])) :
                /* get menu location */
        		$menu = wp_get_nav_menu_object( $locations[ $menu_name ] );
                /* get menu items */
        		$menu_items = wp_get_nav_menu_items($menu->term_id, array( 'order' => 'DESC' ) );

    		    /* get all menu items as a multidimensional array */
    			foreach ((array) $menu_items as $key => $menu_item ) :
                    /* get the menu title */
                    $title = $menu_item->title;
                    /* check if the menu item has a parrent */
    				if($menu_item->menu_item_parent > 0) :
                        /* store it inside the parrent as a submenu (title and link) */
    					$menuarray[$menu_item->menu_item_parent]["submenu"][$menu_item->ID]["title"] = $menu_item->title;
    					$menuarray[$menu_item->menu_item_parent]["submenu"][$menu_item->ID]["link"] = $menu_item->url;
                    /* the menu item is single so store it as a title and link */
                    else :
    					$menuarray[$menu_item->ID]["title"] = $menu_item->title;
    					$menuarray[$menu_item->ID]["link"] = $menu_item->url;
    				endif;
                /* end loop through the menu */
    			endforeach;
            /* end checker of the menu */
    		endif;
    		/* split it in two */
    		if($split && count($menuarray) > 0) :
                /* chunk it */
    			$menuarray = array_chunk($menuarray, ceil(count($menuarray)/2));
    		else :
                /* it appears we have no items in the menu */
    			$menuarray = $menuarray;
    		endif;

            /* debug option inside the menu */
    		if($debug == 1 &&  !is_admin() ) :
    			echo "<pre>";
    			print_r($menuarray);
    			echo "</pre>";
    		endif;
            /* return the menu array */
    		return array_reverse($menuarray);
    }
    /* ------------------------------------------------------------------------- */
    /* FILTERS */


    /**
     * is_active private function that checks if current page and link is the same, supports Page, Child Page, Category
     * @method is_active
     * @param  string    $currentPage current page link
     * @param  string    $link        menu item link
     * @return boolean                true if is active or false if its not
     */
    public function is_active($currentPage = '', $link = '') {
        /* checks for main url or submenu children is active or not */
        if($currentPage == $link || self::subpageactive($link) || self::categoryactive($link)) :
            /* we have a match so return true */
            return true;
        endif;
        /* we have no match */
        return false;
    }



    /**
     * spinal_filter_url description
     * @method spinal_filter_url
     * @param  [type]            $link [description]
     * @return [type]                  [description]
     */
    private function filter_url($link) {
    	$filtered = str_replace(" ", "-", (($link)));
    	return $filtered;
    }

    /**
     * curPageURL get clean current page url
     * @method curPageURL
     * @return [type]     [description]
     */
    public function curPageURL() {
    	$pageURL = 'http';
    	$pageURL .= "://";
    	if ($_SERVER["SERVER_PORT"] != "80") :
    		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    	else :
    		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    	endif;
    	return $pageURL;
    }



    /**
     * subpageactive check if this post has a parrent and if its active
     * @method subpageactive
     * @param  string        $link  current element link
     * @return boolean              true if active | false if not
     */
    private function subpageactive($link) {
        /* wordpress global post */
    	global $post;
        /* check if the post has a parent */
    	if($post->post_parent) :
            /* get post_name | slug */
    		$slug = get_post($post->post_parent)->post_name;
    	endif;

        /* check the post slug inside the url, every url in wordpress if it has a parent it is appended to the url path */
    	if(strpos($link,$slug) !== false) :
            /* its active so return true */
    		return true;
    	else :
            /* no match return false */
    		return false;
    	endif;
    }

    /**
     * [categoryactive description]
     * @method categoryactive
     * @param  [type]         $link [description]
     * @return [type]               [description]
     */
    private function categoryactive($link) {
        /* check if post is category or single */
    	if(is_category() || is_single()) :
            /* if we have a category its either a single post or category page */
            $category = get_the_category();
            /* get category slug */
    		$slug = $category[0]->category_nicename;
            /* if we have a slug of the category in the link, its active either its a post or category */
            if(stripos( $link, $slug ) > 0) :
    			return true;
    		else :
    			return false;
    		endif;
    	endif;
    }


}























 ?>
