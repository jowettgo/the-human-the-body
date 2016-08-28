<?php

/**
 * user info associated with the account
 *
 * array(
 *  'interests' => array(),
 *  'tv' => array(),
 *  'about' => array(),
 *  'music' => array(),
 *  'books' => array(),
 *  'location' => array('city'=> '', 'country' => '')
 *  'posts' => '' // count of posts added by this user
 *  'since' => 'date'
 * )
 *
*/
class user_info extends __user
{
    /**
     * $ID user id]
     * @var string
     */
    public $ID = '';

    /**
     * $user_info user info contains all the meta info speciffic to member profile
     * @var array
     */


    public $name;
    public $email;
    public $admin;
    public $premium;
    public $avatar;
    public $birthdate;
    public $gender;
    public $description;
    public $interests_1;
    public $interests_2;
    public $interests_3;
    public $city;
    public $country;
    public $user_info = array();
    public $affections;
    /**
     * $meta user info
     * @var array
     */
    public $meta = array();

    /**
     * @method __construct  main class constructor
     */
    function __construct($user_id = false)
    {
        $userGID = $user_id ? $user_id : parent::id();


        $this->ID = $userGID;
        $this->meta = get_user_meta( $userGID);
        $this->check_premium($userGID);


        $userInfo  = $this->get($userGID);
        $this->ID = $userInfo['ID'];
        $this->user_info = $userInfo;
        $this->name = $userInfo['name'];
        $this->email = $userInfo['email'];
        $this->admin = $userInfo['administrator'];
        $this->premium = $userInfo['premium'];
        $this->avatar = $userInfo['avatar'];
        $this->avatar_id =  $this->meta['avatar_id'][0];
        //$this->birthdate = $userInfo['birthdate'];
        $this->gender = $this->meta['gender'][0];
        $this->description = $this->meta['description'][0];
        $this->birthdate = $this->meta['birth-date'][0];
        $this->city = $userInfo['location']['city'];
        $this->affections = $userInfo['affections'];
        $this->country = $userInfo['location']['country'];
        if($this->premium) :
            $this->interests_1 = $this->meta['interests1'][0];
            $this->interests_2 = $this->meta['interests2'][0];
            $this->interests_3 = $this->meta['interests3'][0];
        endif;
    }
    public function get_profile_link($user_id)
    {
        $user_id = self::encrypt($user_id);
        return page('member').'?u='.$user_id;
    }
    /**
     * add meta info to user after init
     * @method add
     * @param  [type] $info [description]
     * @param  [type] $meta [description]
     */
    public function add($info, $meta)
    {

    }
    /**
     * update user info based on data to add and meta key
     * @method update
     * @param  mixed $info  data to be added to the user
     * @param  string $meta  meta key to add the info to
     * @return boolean       true or false based on success of adding it
     */
    public function update($info, $meta)
    {

    }
    /**
     * check premium handles all the capabilities of the user based on the request sent by paypal
     * @method check_premium
     * @param  integer       $user_ID the id of the user to update, optional
     * @return NULL
     */
    public function check_premium($user_ID = false)
    {
        $userID = $user_ID ? $user_ID : $this->ID;
        $info = $this->meta;
        
        
        /* check if paypal meta is set */
        
        //DISABLED
        if (isset($info['paypal']) and 1==2) {
            
            $paypal  = unserialize($info['paypal'][0]);
            
            if (empty($paypal)) {
                // fix serialized corrupt data
                // http://stackoverflow.com/questions/10152904/unserialize-function-unserialize-error-at-offset
                $paypal = preg_replace('!s:(\d+):"(.*?)";!e', "'s:'.strlen('$2').':\"$2\";'", $info['paypal'][0]);
                $paypal = unserialize($paypal);
            }
            
            $status = $paypal['payment_status'];

            $paid_on = strtotime($paypal['paid_on']);
            $today = date('Y-m-d h:m:s');
            
            /* check status and date is not more than 30 days over paid on date (monthly subscription) */
            if($status == 'Completed') :

                $today = date('Y-m-d h:m:s');
                $ts1 = strtotime($today);
                $ts2 = $paid_on;
                $seconds_diff = $ts1 - $ts2;
                $days_passed = floor($seconds_diff/(60*60*24));

                $user = new WP_User( $userID );
                $role = ($user->get_role('premium_member'));

                $allcaps = $user->get_role_caps();
                /* only change roles if the current user is not an admi */
                if($allcaps['administrator'] != 1) :

                    if($days_passed <= 30) :
                        /* set premium member role */
                        $user->remove_role( 'normal_member' );
                        $user->add_role( 'premium_member' );
                        $data = array(
                            'ID' => $userID,
                            'role' => 'premium_member'
                        );
                        /* update user with new roles so they are visible in admin */
                        wp_update_user($data);
                        do_action('premium-membership');
                    else :
                        update_user_meta( $userID, 'days-since-expire', $days_passed);

                        /* set normal member role */
                        $user->remove_role( 'premium_member' );
                        $user->remove_cap( 'premium' );
                        $user->add_role( 'normal_member' );
                        $data = array(
                            'ID' => $userID,
                            'role' => 'normal_member'
                        );
                        /* update user with new roles so they are visible in admin */
                        wp_update_user($data);
                        do_action('expired-membership');
                    endif;
                endif;
            endif;
        }


    }
    /**
     * get user info, leave parameters blank to get all the user info
     * @method get
     * @param  $ID           id of the user to get the meta from
     * @param  string $meta  @optional meta key to access the data
     * @return mixed         returns mixed, string or array based on the type of data stored
     */
    public function get($ID = false, $meta=false, $post = false)
    {
        /* user id */
        $ID = $ID ? $ID : $this->ID;
        /* check if we have a user id */
        if($ID) :
            /* no meta key, return every thing */
            if (!$meta) :
                /* return all meta info on the user */
                    $meta = get_user_meta($ID);
                    $user = get_userdata( $ID );
                    /* map the user info array */
                    if($user) {
                        $name = ucfirst($meta['first_name'][0]).' '.ucfirst($meta['last_name'][0]);
                        $attachment_id = $meta['wp_user_avatar'][0] ? $meta['wp_user_avatar'][0] : get_image_id($meta['avatar'][0]);

                        if(!$meta['wp_user_avatar'][0] && $attachment_id) {
                            update_user_meta( $ID, 'wp_user_avatar', $attachment_id);
                        }

                        update_user_meta( $ID, 'wp_user_avatar', $meta['avatar_id'][0] );
                        $avatar =  wp_get_attachment_image_src( $meta['wp_user_avatar'][0]) ;
                        $avatarimage =  $avatar[0] ?  $avatar[0] : $meta['avatar'][0];
                    }
                    $c_user = wp_get_current_user();
                    $roles = $c_user->get_role_caps();

                    if(isset($roles['blocked']) && $roles['blocked'] == 1) :
                        wp_logout();
                        wp_redirect( get_site_url(), $status = 302 );
                    endif;

                    $avatar = $avatar[0] ? $avatar[0] : $avatarimage ? $avatarimage : _IMG_.'nopic_192.gif';
                    $user_o = get_user_by( 'ID', $ID );
                    //deg($roles);
                    $info = array(
                        'ID' => $user->ID,
                        'name' => $name,
                        'email' => $user->data->user_email,
                        'administrator' => $roles['administrator'],
                        'premium' => $roles['premium'],
                        'interests' => array(
                            'interests' => $meta['interests'][0],
                            'tv' => $meta['tv'][0],
                            'music' => $meta['music'][0],
                            'books' => $meta['books'][0],
                        ),
                        'location' => array(
                            'city' => preg_replace('/[^a-z\s\-]/i', '', $meta['city'][0]),
                            'country' => preg_replace('/[^a-z\s\-]/i', '', $meta['country'][0])
                        ),
                        'affections' => $meta['affections'][0],
                        'friends' => $meta['friends'][0],
                        'about' => $meta['description'][0],
                        'since' => $user->data->user_registered,
                        'avatar' => $avatar,

                        'data' => $user,

                    );
                    return $info;
            endif;
        else :
            /* guest commenting */

                $avatar = _IMG_.'nopic_192.gif';

                if($post) :
                    $name = $post->comment_author;
                endif;

                //deg($meta);
                $info = array(
                    'name' => $name,
                    'email' => $user->data->user_email,
                    'avatar' => $avatar,

                );
                return $info;

        endif;


    }
    function get_post_count($user_id = false) {
        $user_id = $user_id ? $user_id : $this->ID;
        global $wpdb;
        $table = $wpdb->prefix.'posts';
        $sql = "SELECT COUNT(*) FROM `$table` WHERE (`post_type`='post' OR `post_type`='topic') AND `post_author`=$user_id AND `post_status`='publish' AND `post_type`!='reply'";
        $count = $wpdb->get_results($sql);
        $count = (array)$count[0];
        $count = $count['COUNT(*)'];
        return $count;
    }
    function get_comments_count($user_id = false) {
        $user_id = $user_id ? $user_id : $this->ID;
        global $wpdb;
        $table = $wpdb->prefix.'comments';
        $sql = "SELECT COUNT(*) FROM `$table` WHERE `user_id`=$user_id AND `comment_approved`='1'";
        $count = $wpdb->get_results($sql);
        $count = (array)$count[0];
        $count = $count['COUNT(*)'];
        return $count+ $this->get_reply_count($user_id);
    }
    function get_reply_count($user_id = false) {
        $user_id = $user_id ? $user_id : $this->ID;
        global $wpdb;
        $table = $wpdb->prefix.'posts';
        $sql = "SELECT COUNT(*) FROM `$table` WHERE `post_author`='$user_id' AND `post_status`='publish' AND `post_type`='reply'";
        $count = $wpdb->get_results($sql);
        $count = (array)$count[0];
        $count = $count['COUNT(*)'];
        return $count;
    }


    public function get_posts($user_id = false, $page=0)
    {
        $max_posts = 5;

        if ($page==0) {
            $start_posts = 0;
        } else {
            $start_posts = ($page-1)*$max_posts;
        }

        global $wpdb;
        /* get user by id and extract email */
        $ID = $user_id ? $user_id : $this->ID;
        /* replies table */
        $posts = $wpdb->prefix.'posts';
        /* reply sql */
        $rsql = "SELECT * FROM `$posts` WHERE (`post_type`='post' OR `post_type`='topic') AND `post_author` = '$ID' AND `post_status`='publish' ORDER BY `post_date` DESC  LIMIT $start_posts, $max_posts";
        $_posts = $wpdb->get_results($rsql);
        return $_posts;
    }
    public function get_posts_ideas($user_id = false, $page=0)
    {
        $max_posts = 5;

        if ($page==0) {
            $start_posts = 0;
        } else {
            $start_posts = ($page-1)*$max_posts;
        }

        global $wpdb;
        /* get user by id and extract email */
        $ID = $user_id ? $user_id : $this->ID;
        /* replies table */
        $posts = $wpdb->prefix.'posts';
        /* reply sql */
        $rsql = "SELECT * FROM `$posts` WHERE (`post_type`='idea') AND `post_author` = '$ID' AND `post_status`='publish' ORDER BY `post_date` DESC  LIMIT $start_posts, $max_posts";

        $_posts = $wpdb->get_results($rsql);
        return $_posts;
    }    
    public function get_images_count($userID)
    {
        $images = $this->get_images(1000, 0, $userID);

        return count($images);
    }
    /**
     * !! The merge with bbc replies was disabled. Only gets normal wp comments !! 
     * get comments and replies of the current oser or supply another user id to get that user specific comments
     * @method get_comments
     * @param  integer      $user_id  optional user id to get the comments from
     * @return array        return mysql rows with all the info needed
     */
    public function get_comments($user_id=0, $page = 0)
    {
        $max_comments = 10;
        $max_reply = 3;
        $start_reply = $page * $max_reply;

        if ($page==1) {
            $start_comment = 0;
        } else {
            $start_comment = ($page-1)*$max_comments;
        }

        global $wpdb;
        /* get user by id and extract email */
        $ID = $user_id ? $user_id : $this->ID;;
        /* get all the comments combined with  replies on the forums/meetings */

        /*comments table*/
        $commentstable = $wpdb->prefix.'comments';
        /* comments sql */
        $csql = "SELECT * FROM `$commentstable` WHERE `user_id` = '$ID' ORDER BY `comment_date` DESC LIMIT $start_comment, $max_comments";

        /* replies table */
        //$replytable = $wpdb->prefix.'posts';
        /* reply sql */
        //$rsql = "SELECT * FROM `$replytable` WHERE `post_type`='reply' AND `post_author` = '$ID' ORDER BY `post_date` DESC  LIMIT $start_reply, $max_reply";

        //echo $csql;

        /* chat with user / profile member */
        $comments = $wpdb->get_results($csql);
        //$replies = $wpdb->get_results($rsql);

        //$total = array_merge( $comments , $replies);
        $total = $comments;

        foreach ($total as $key => $comment) {

            /* comments */
            if($comment->comment_ID > 0) :

                $content = $comment->comment_content;
                $post_comment = get_post($comment->comment_post_ID);
                $postTitle = $post_comment->post_title;
                $category = get_the_category( $post_comment->ID );
                $permalink = get_the_permalink($comment->comment_post_ID);

                $author = new user_info($post_comment->post_author);
                $profile = $author->get_profile_link($author->ID);
                $date = $comment->comment_date;
                
                $post_type = get_post_type_object( get_post_type($post_comment->ID) );
                $post_content = $post_comment->post_content;
                $categoryName = !empty($category[0]->name)?$category[0]->name:$post_type->labels->name;
                
            /* replies */
            else :

                $content = $comment->post_content;
                $post_comment = get_post($comment->post_parent);
                $postTitle = $post_comment->post_title;
                $permalink = get_the_permalink($comment->post_parent);

                $author = new user_info($post_comment->post_author);
                $profile = $author->get_profile_link($author->ID);
                $date = $comment->post_date;

                $post_content = $post_comment->post_content;
                
                $category = get_post_custom( $comment->ID );
                $parent = get_post($category['_bbp_forum_id'][0]);
                $ttl = get_the_title($parent->post_parent);
                $parent_title = !empty($ttl)?get_the_title($parent->post_parent).' <i class="fa fa-angle-right"></i> ':'';
                $categoryName = get_post($post_comment->post_parent);
                $categoryName = $parent_title.$categoryName->post_title;
            endif;

            /* normalize data */
            $comments_[] = array(
                'permalink' => $permalink,
                'title'=> $postTitle,
                'comment'=> $content,
                'content'=> strip_tags($post_content),
                'name' => $author->name,
                'profile' => $profile,
                'date' => $date,
                'category' =>$categoryName
            );
        }

        /* custom sorter */
        if(is_array($comments_)) :
            usort($comments_, function($a, $b) {
              $ad = new DateTime($a['date']);
              $bd = new DateTime($b['date']);
              if ($ad == $bd) {
                return 0;
              }
              return $ad < $bd ? 1 : -1;
            });

            return $comments_;
        endif;
    }
    public function get_images($total=5, $page = 1, $user_id = false)
    {
        $args = array('post_type'=>'galleries', 'posts_per_page'=>60, 'order_by'=>'date', 'order'=>'DESC');
        /* post parent 234/342/647 */
        $user_id = $user_id ? $user_id : $this->ID;
        /* loop galleries/take ids */

        $galleries = new WP_Query($args);
        $i = 0;
        if(is_array($galleries->posts) && count($galleries->posts)) :
        foreach ($galleries->posts as $gal) {
            $meta = get_post_meta($gal->ID);
            $photos = unserialize($meta['photos'][0]);
            if(is_array($photos) && count($photos) > 0) :
            foreach ($photos as $photo) {
                $at = get_post($photo['url_id']);

                if($at->post_author == $user_id && $photo['approve'] == 'on') :
                    $images[$i]['title'] = $gal->post_title;
                    $images[$i]['image'] = featured_image($photo['url_id'], 'thumbnail');
                    $images[$i]['date'] = date('d - F - Y \<\b\r\> h:s A', strtotime($gal->post_date));
                    $images[$i]['link'] = get_the_permalink($gal->ID);
                    $i++;
                endif;
            }
            endif;
        }
        endif;
        if(is_array($images) && count($images) > 0) :
            $pagination = new array_pagination($images, $total);
            return $pagination->page($page-1);
        endif;

    }


    public function get_interests($key = false)
    {
        if($key) :
            switch ($key) :
                case 1:
                    $interests = $this->interests_1;
                    break;
                case 2:
                    $interests = $this->interests_2;
                    break;
                case 3:
                    $interests = $this->interests_3;
                    break;
                default:

                    break;
            endswitch;
            if($interests) :

            $interests = explode(',', $interests);
                if(is_array($interests) && count($interests) > 0) :
                    foreach ($interests as $interest) {
                        $interestlabel = explode('-', $interest);
                        $interestlabel = $interestlabel[1];
                        $selected .= "<a class='ui label transition visible' data-value='$interest'>$interestlabel<i class='delete icon'></i></a>";
                    }
                    return $selected;

                endif;
            endif;
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
}



add_action('spinal-primal', 'update_user_info');

function update_user_info() {
    $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    if($_POST['edit-profile'] && logged_in()) :
        $userinfo = new user_info();
        $name = explode(' ', $_POST['fullname']);
        update_user_meta( $userinfo->ID, 'first_name', $name[0]);
        update_user_meta( $userinfo->ID, 'last_name', $name[1]);
        update_user_meta( $userinfo->ID, 'email', $_POST['email']);
        update_user_meta( $userinfo->ID, 'gender',$_POST['gender']);
        update_user_meta( $userinfo->ID, 'birth-date', $_POST['birthdate']);
        update_user_meta( $userinfo->ID, 'description', $_POST['description']);
        update_user_meta( $userinfo->ID, 'avatar_id', $_POST['avatar']);
        update_user_meta( $userinfo->ID, 'wp_user_avatar', $_POST['avatar']);
        update_user_meta( $userinfo->ID, 'city', $_POST['city']);
        update_user_meta( $userinfo->ID, 'country', preg_replace('/\d/', '', $_POST['country']));
        update_user_meta( $userinfo->ID, 'interests1', $_POST['interests-1']);
        update_user_meta( $userinfo->ID, 'interests2', $_POST['interests-2']);
        update_user_meta( $userinfo->ID, 'interests3', $_POST['interests-3']);
        update_user_meta( $userinfo->ID, 'affections', $_POST['affections']);
        wp_redirect( page('my-account-edit'));
    endif;
}



class array_pagination {
    private $array;
    private $max;
    public function __construct($array, $max)
    {
        $this->array = array_reverse($array);
        $this->max = $max;
    }
    public function page($page)
    {
        $max = $this->max;
        $array = $this->array;
        $page = $page;
        $start = ($max*$page);
        return array_slice($array, $start, $max);
    }
}
 ?>
