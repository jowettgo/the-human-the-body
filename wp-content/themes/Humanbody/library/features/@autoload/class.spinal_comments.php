<?php

/**
 * get comments from post id and store them inside the list var
 */
class spinal_comments {
    /**
     * post id of the page where to get the comments
     * @var string
     */
    public $ID = '';
    /**
     * store the comment system here
     * @var array
     */
    public $list = array();
    public $per_page = 50;
    public $table;

    public function __construct()
    {
        global $post;
        global $wpdb;
        $this->ID = $post->ID;
        /* store the table name of comments inside the public table var */
        $this->table = $wpdb->prefix.'comments';
    }
    public function count() {
        global $wpdb;
        /* check to see if we need to retrieve the comments on a different page
        otherwise we will get the current page id
         */
        $ID = $ID ? $ID : $this->ID;
        /* comments table */
        $table = $this->table;
        $current_user = wp_get_current_user();
        $cid = $current_user->ID;
        $approved = "AND (`comment_approved`=0 AND `user_id`=$cid) OR (`comment_post_ID`=$ID AND `comment_approved`=1)";
        $count = "SELECT COUNT(*) FROM $table WHERE `comment_post_ID`=$ID ".$approved;
        $mysql = $wpdb->get_results($count);
        $result = get_object_vars($mysql[0]);

        return $result['COUNT(*)'];
    }
    public function get_unique_commenters($postid) {
        global $wpdb;
        $ID = $postid ? $postid : $this->ID;
        /* comments table */
        $table = $this->table;
        $count = "SELECT COUNT(DISTINCT comment_author_email) FROM $table WHERE `comment_post_ID`=$ID AND comment_approved = '1'";
        $mysql = $wpdb->get_results($count);

        $result = get_object_vars($mysql[0]);

        return $result['COUNT(DISTINCT comment_author_email)'];
    }    
    public function get_comments($ID = false)
    {
        global $wpdb;
        /* check to see if we need to retrieve the comments on a different page
        otherwise we will get the current page id
         */
        $ID = $ID ? $ID : $this->ID;
        /* comments table */
        $table = $this->table;
        /* pagination vars */
        $page = get_query_var('page');
        $page = ($page == 0 ? 1 : $page)-1;
        $per_page = $this->per_page;
        $min =  ($page-1) * $per_page;
        $min = $min < 0 ? 0 : $min;
        $max = $page * $per_page;
        $max = $max > 0 ? $max : 0;
        /* end pagination vars */

        $current_user = wp_get_current_user();
        $cid = $current_user->ID;
        $approved = "AND (`comment_approved`=1)";
        /* sql statement */
        $sql = "SELECT * FROM $table WHERE `comment_post_ID`=$ID ".$approved." ORDER BY `comment_date` ASC LIMIT $max, $per_page ";

        /* get rows */
        $rows = $wpdb->get_results($sql);
        /* do comment hierarchy */
        $hierarchy = array();
        /* check for comments first and then do the walk */
        if(count($rows) > 0) :
            foreach ($rows as $comment) :

                $parent = $comment->comment_parent;
                $commentID = $comment->comment_ID;
                if($parent == 0) :
                    $hierarchy[$commentID]['comment'] = $comment;
                else :
                    $hierarchy[$parent]['reply'][$commentID] = $comment;
                endif;
            /* end loop through */
            endforeach;
        /* end check for comments */
        endif;
        /* store the comments inside the list variable in the class */
        $this->list = ($hierarchy);
        /* return it */
        return $hierarchy;

    }
    /**
     * add comment in to the db
     * @method add
     */
    public function add() {
        /* purge the wicked */
        $_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
        $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        if($_POST['add-comment']) {
            $user = wp_get_current_user();
            /* user logged in */
            if($user->ID) :
                /* prepare vars for user comment when logged in */
                $userinfo  = new user_info();
                $user_name = $userinfo->name;
                $email = $userinfo->email;

            /* vizitor details */
            else :
                $user_name = $_POST['fullname'];
                $email = $_POST['email'];
            endif;
            /* message */
            $message = $_POST['comment'];
            $replyID =(int) $_POST['r'];
            /* check the data is valid */



            if(filter_var($email, FILTER_VALIDATE_EMAIL)) :
                /* check size of name and message */
                if(strlen($user_name) > 3) :
                    if(strlen($message) > 2) :
                        /* all good, add the comment */
                        global $wpdb;

                        $approved = $userinfo->admin ? 1 :
                                                logged_in() ? 1 : 0;

                        $wpdb->insert($this->table,
                            array(
                                'comment_post_ID' => $this->ID,
                                'comment_author' => $user_name,
                                'comment_author_email' => $email,
                                'comment_author_IP' => getUserIP(),
                                'comment_date' => date('Y-m-d H:i:s'),
                                'comment_date_gmt' =>  date('Y-m-d H:i:s'),
                                'comment_content' => $message,
                                'comment_approved' => $approved,
                                'comment_type' => '',
                                'comment_parent' => $replyID,
                                'user_id' => $user->ID ? $user->ID : ''
                            )
                        );
                        $mesage =  $approved ? true : 'Thank you for sharing your thoughts our comment is pending and will be added once its moderated.';

                        wp_update_comment_count_now($this->ID);        

                        /* add notification */
                        $commentID = $wpdb->insert_id;
                        $currentPOST = get_page($this->ID);
                        $userID = $user->ID;
                        /* add notification hook */
                        do_action( 'spinal_comment_notifications', $commentID, $currentPOST, $userID, $replyID );
                        $this->redirect($approved);
                    endif;
                    return 'Please add a message before posting this comment!';
                endif;
                return 'Name too short!';
            endif;
            return 'Email invalid!';
        }
    }
    function form($template = false) {

        $user = wp_get_current_user();
        $replyID =(int)$_GET['r'];
        $permalink = get_the_permalink();

        

        if($user && $user->ID) :
            if ($replyID and $replyID>0) {
                $author = get_comment_author( $replyID );
                $replyto = 'reply to '.$author;
            } else {
                 $replyto = '';
            }

            if($_GET['com'] == 1) {
                $template = '<div class="review-notice">Thank you for sharing your thoughts the comment is pending and will be added once its moderated.</div>';
            }
            $template .= "<form class='feedback-form' method='POST' action='{$permalink}'>
                            <div class='row'>
                                <div class='col-xs-12'>
                                    <input type='hidden' name='r' value='$replyID'/>
                                    <label for='message'>Your message {$replyto}*</label>
                                    <textarea name='comment' id='message' required=''></textarea>
                                </div>
                            </div>
                            <div class='row submit-wrapper'>
                                <div class='col-lg-6 col-md-6 col-sm-6 col-xs-12'>
                                    <input type='submit' value='submit' name='add-comment'>
                                </div>
                                <div class='col-lg-6 col-md-6 col-sm-6 col-xs-12'>
                                    <a href='javascript:void(0)' class='scroll-top'><i class='fa fa-long-arrow-up'></i>Back to top</a>
                                </div>
                            </div>
                        </form>";
        else :
            $template .= "<form class='feedback-form' method='POST'>
                            <div class='row'>
                                <div class='col-lg-4 col-md-4 col-sm-6 col-xs-12'>
                                    <label for='name'>Your name*</label>
                                    <input type='text' id='name' required='' name='fullname'>
                                    <input type='hidden' name='r' value='$replyID'/>
                                    <label for='email'> Your email*</label>
                                    <input type='email' id='email' required='' name='email'>
                                </div>
                                <div class='col-lg-8 col-md-9 col-sm-6 col-xs-12'>
                                    <label for='message'>Your message*</label>
                                    <textarea name='comment' id='message' required=''></textarea>
                                </div>
                            </div>
                            <div class='row submit-wrapper'>
                                <div class='col-lg-6 col-md-6 col-sm-6 col-xs-12'>
                                    <input type='submit' value='submit' name='add-comment'>
                                </div>
                                <div class='col-lg-6 col-md-6 col-sm-6 col-xs-12'>
                                    <a href='javascript:void(0)' class='scroll-top'><i class='fa fa-long-arrow-up'></i>Back to top</a>
                                </div>
                            </div>

                        </form>";
        endif;
        return $template;
    }
    function redirect($approved) {
        wp_redirect(get_the_permalink($this->ID).($approved ? false : '?com=1'));
    }
    public function admin_links() {

    }
    public function pagination($ID = false)
    {

    }

}

/* hook it up in the header */
add_filter('spinal-primal', 'spinal_add_comments' );
function spinal_add_comments() {
    global $spinal_comments;
    $spinal_comments = new spinal_comments();
    $message = $spinal_comments->add();
}


 ?>
