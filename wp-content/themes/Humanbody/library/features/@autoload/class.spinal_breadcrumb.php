<?php



/**
 *
 */
class spinal_breadcrumb {
    /**
    * private var for breadcrumb to be used in checking what type of breadcrumb to generate
     */
    private $type = false;

    /**
     * public custom page breadcrumb function injection
     *
     * #EXAMPLE USE
     * $this->breadcrumbPage = array( 'page-custom.php' => my_breadcrumb_function()
     * 								  'page-custom2.php' => my_other_breadcrumb_function()
     * 								)
     */
    public $breadcrumbPage = array();


    /**
     * [get_type find out what content are generating the breadcrumb for]
     * @method get_type
     * @return [string]   [return what type of content this is]
     */
    private function get_type() {
        /* use wp query for custom post types like forums and topics */
        global $wp_query;
        $post_type = $wp_query->query_vars['post_type'];

        /* DEFAULT WP CHECKS for native templates */




        /* #POST check for single or single-custom.php post type */
        if(is_single()) :
            $type = 'post';
        endif;
        /* end check for single post or single custom post type */
        /* #PAGE check for page */
        if(is_page()) :
            $type = 'page';
        endif;
        /* end check for is page */
        /* #ARCHIVE check for archive */
        if(is_archive()) :
            $type = 'archive';
        endif;
        /* end check for archive */
        /* #CATEGORY check for category or subcategory */
        if(is_category()) :
            $type = 'category';
        endif;
        /* end check for is category or subcategory */
        /* #TAG check if is Tag archive page */
        if(is_tag()) :
            $type = 'tag';
        endif;

        /* #AUTHOR check is author page */
        if(is_author()) :
            $type = 'author';
        endif;
        /* end check for autor page */
        /* #INDEX check if is home */
        if(is_home() || is_front_page()) :
            $type = 'index';
        endif;
        /* end check for is home */
        /* #SEARCH check if is the search page */
        if(is_search()) :
            $type = 'search';
        endif;
        /* end check for is seach page */
        /* # 404 check if its the 404 not found page */
        if(is_404()) :
            $type = '404';
        endif;
        /* end check for is 404 not found */


        /* # bbpress */
        /* forum support */
        if($post_type == 'forum') :
            $type = 'forum';
        endif;
        /* topic support */
        if($post_type == 'topic') :
            $type = 'topic';
        endif;
        /* end check for bbpress */

        /* idea support */
        if($post_type == 'idea') :
            $type = 'idea';
        endif;

        /* research support */
        if($post_type == 'research') :
            $type = 'research';
        endif;

        /* gallery support */
        if($post_type == 'galleries') :
            $type = 'galleries';
        endif;

        /* affections support */
        if($post_type == 'affection') :
            $type = 'affection';
        endif;
        /* end check for bbpress */


        /* END DEFAULT CHECK WP for native templates */

        /* make custom checks for custom page templates */
        if(is_page()) :
            /* get custom pages */
            $customPages = self::get_all_page_templates();
            /* loop each custom page template */
            foreach ($customPages as $pageName => $template) :
                /* check current page if it has one of the templates */
                if(is_page_template( $template)) :
                    $type = $template; /* this should set a custom page type like page-custom.php */
                endif;
                /* end check current page */
            endforeach;
            /* end loop for pages */
        endif;
        /* return custom or native content type */
        return $type;
    }
    function get() {
        $type = self::get_type();
        /* reset everything, query and wp query */
        wp_reset_query();
        wp_reset_postdata();



        /* home */
        /* home url */
        $homeurl = get_site_url();
        $homeName = 'Home';
        $breadcrumb .= self::create_crumbs(array(array('name'=>$homeName, 'link' => $homeurl)));




        /* PAGE FORUMS AND TOPUCS BREADCRUMB
        ---------------------------------------------------------------------- */
        if($type == 'page' || $type == 'forum' || $type == 'topic') :
            /* if community forum and topic add community to the breadcrumb */

            if($type == 'forum' || $type == 'topic') :
                $communityurl = page('community');
                $communityName = 'Community';
                $breadcrumb .= self::create_crumbs(array(array('name'=>$communityName, 'link' => $communityurl)));
            endif;







            /* get current page */
            global $post;
            /* this is used for pages and forums */
            $hierarchy = $this->get_all_parents($post);
            $breadcrumb .= self::create_crumbs($hierarchy);



        /* POSTS CUSTOM POSTS AND EXCEPTIONS
        ----------------------------------------------------------------------------- */
        elseif($type == 'post' || $type == 'category' || $type == 'archive' ) :
            $hierarchy = $this->get_all_categories();
            $breadcrumb .= self::create_crumbs($hierarchy);
        else :
            if($type == 'idea' ) :
                global $post;
                $breadcrumb .= self::create_crumbs(array(
                    array('name'=>'Ideas', 'link' => 'http://thehumanthebody.com/ideas/'),
                    array('name'=>'What you can do', 'link' => page('what-you-can-do')),
                    $post
                ));

            endif;
            if($type == 'research' ) :
                global $post;
                if(!is_archive()) :
                    $breadcrumb .= self::create_crumbs(array(
                        array('name'=>'Research', 'link' => page('research')),
                        $post
                    ));
                else:
                    $breadcrumb .= self::create_crumbs(array(
                        array('name'=>'Research', 'link' => page('research'), 'active' => true),

                    ));
                endif;
            endif;
            if($type == 'galleries' ) :
                global $post;

                    $breadcrumb .= self::create_crumbs(array(
                        array('name'=>'Community', 'link' => page('community')),
                        array('name'=>'Photos from friends', 'link' => page('gallery')),
                        $post
                    ));

            endif;
            if($type == 'affection' ) :
                global $post;

                    $breadcrumb .= self::create_crumbs(array(
                        array('name'=>'The Body', 'link' => page('body-landing')),
                        $post
                    ));

            endif;
        endif;
        echo $breadcrumb;

    }
    function create_crumbs($hierarchy) {
        $total = count($hierarchy)-1;
        $delimiter = "<i class='fa fa-angle-right'></i>";
        foreach ($hierarchy as $i => $crumb) :

            if((int)$crumb->term_id > 0) :

                $crumbname = $crumb->name;
                $crumburl = get_term_link($crumb);

            elseif((int)$crumb->ID > 0) :
                $crumbname = $crumb->post_title;
                $crumburl = get_the_permalink($crumb->ID);
            endif;

            if(is_array($crumb)) :
                $crumbname = $crumb['name'];
                $crumburl = $crumb['link'];
            endif;



            if($total == $i && !is_array($crumb) || is_array($crumb) && $crumb['active']) :
                $breadcrumb .= "<li property='itemListElement' typeof='ListItem' class='active'>
                                    <a><span property='name'>$crumbname</span></a>
                                    <meta property='position' content='1'>
                                </li>";
            else :

                $breadcrumb .= "<li property='itemListElement' typeof='ListItem'>
                                    <a property='item' typeof='WebPage' href='{$crumburl}'>
                                    <span property='name'>$crumbname</span>
                                    {$delimiter}</a>
                                    <meta property='position' content='1'>
                                </li>";
            endif;
        endforeach;
        return $breadcrumb;
    }

    /**
     * [get_all_page_templates get page templates "page-custom.php"]
     * @method get_all_page_templates
     * @return [template_name]                 [return custom page template to be used with breadcrumb]
     */
    function get_all_page_templates() {
        /* get page templates */
        $templates = wp_get_theme()->get_page_templates();
        $template_names = array();
        /* get just the custom pages */
        foreach ( $templates as $template_name => $template_filename )
        {
            /* only add page templates */
            if(strpos($template_name, 'page')) :
                $template_names[$template_filename] = $template_name;
                /* end only add page templates */
            endif;
        }
        /* return our templates */
        return $template_names;
    }
    function get_all_parents($post, $array = array()) {

            if(count($array) < 1) {
                $array[] = $post;
            }
            if($post->post_parent > 0) {
                $parent = $post->post_parent;
                $parentObject = get_post($parent);
                $array[] =  $parentObject;
                return $this->get_all_parents($parentObject, $array);
            }
            else {
                return array_reverse($array);
            }
    }
    function get_all_categories($category = false, $array = array()) {

            if($category == false && !is_single()) {
                global $wp_query;
                $slug = $wp_query->query_vars['category_name'];
                /* category specific */
                $category = get_category_by_slug($slug);
                //deg($category);
                $array[] = $category;
            }
            if(is_single() && $category == false ) {

                global $post;
                $cat =  get_the_category();
                $category =$cat[0];
                $array[] = $post;
                $array[] = $category;
            }

            if((int)$category->category_parent > 0) {
                $parent = $category->category_parent;

                $parentObject = get_term($parent, 'category');

                $array[] =  $parentObject;
                return $this->get_all_categories($parentObject, $array);
            }
            else {
                return array_reverse($array);
            }

    }
}










 ?>
