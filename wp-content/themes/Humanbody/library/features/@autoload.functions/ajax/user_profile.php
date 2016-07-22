<?php
/* ajax load cities based on iso ( integration with maps ) */
add_action( 'wp_ajax_getcomments', 'ajax_get_comments' );
/* ajax load cities based on iso ( integration with maps ) */
add_action( 'wp_ajax_nopriv_getcomments', 'ajax_get_comments' );

/* ajax load cities based on iso ( integration with maps ) */
add_action( 'wp_ajax_getUserPosts', 'ajax_get_getUserPosts' );
/* ajax load cities based on iso ( integration with maps ) */
add_action( 'wp_ajax_nopriv_getUserPosts', 'ajax_get_getUserPosts' );

/* ajax load cities based on iso ( integration with maps ) */
add_action( 'wp_ajax_getUserImages', 'ajax_get_userimages' );
/* ajax load cities based on iso ( integration with maps ) */
add_action( 'wp_ajax_nopriv_getUserImages', 'ajax_get_userimages' );

/* ajax load cities based on iso ( integration with maps ) */
add_action( 'wp_ajax_getUserNotification', 'ajax_get_usernotification' );
/* ajax load cities based on iso ( integration with maps ) */
add_action( 'wp_ajax_nopriv_getUserNotification', 'ajax_get_usernotification' );


/* ajax load cities based on iso ( integration with maps ) */
add_action( 'wp_ajax_getUserPostsIdeas', 'ajax_get_getUserPostsIdeas' );
/* ajax load cities based on iso ( integration with maps ) */
add_action( 'wp_ajax_nopriv_getUserPostsIdeas', 'ajax_get_getUserPostsIdeas' );



function ajax_get_getUserPosts($posts = false, $page = 0) {

    /* purge everything on post vars */
    $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    /* get the page */
    $page = $_POST['p'] ? $_POST['p'] : $page;

    $id = user_info::decrypt($_POST['i']);
    $user = new user_info($id);
    /* multi support for static and ajax */

    $posts = $posts ? $posts : $user->get_posts(false, $page);

    if (is_array($posts) && count($posts)) :
        foreach ($posts as $key => $post_) :
            $category  = get_the_category($post_->ID);
            $categoryname = $category[0]->name;
            if(!$categoryname) :
                $categoryname = get_post($post_->post_parent);
                $categoryname = $categoryname->post_title;
            endif;
            $author = new user_info($post_->post_author);
            $profile = $author->get_profile_link($author->ID);
            /* HTML For posts
            ---------------------------------------------*/
             ?>
                <tr>
                    <td><?php echo date('d - F - Y \<\b\r\> h:s A', strtotime($post_->post_date)) ?></td>
                    <td><?php echo $categoryname ?></td>
                    <td>
                        <a href="<?php echo get_the_permalink($post_->ID) ?>"><?php echo $post_->post_title ?></a>
                        <?php if(1 == 2): ?><p>by <a href="<?php echo $profile ?>"><?php echo $author->name ?></a></p><?php endif; ?>
                    </td>
                    <td>"<?php echo substr(strip_tags($post_->post_content), 0, 80) ?>"</td>
                </tr>
            <?php
            /* HTML For posts
            ---------------------------------------------*/
        endforeach;
    endif;
}


function ajax_get_getUserPostsIdeas($posts = false, $page = 0) {

    /* purge everything on post vars */
    $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    /* get the page */
    $page = $_POST['p'] ? $_POST['p'] : $page;

    $id = user_info::decrypt($_POST['i']);
    $user = new user_info($id);
    /* multi support for static and ajax */

    $posts = $posts ? $posts : $user->get_posts_ideas(false, $page);

    if (is_array($posts) && count($posts)) :
        foreach ($posts as $key => $post_) :
            $category  = get_the_category($post_->ID);
            $categoryname = $category[0]->name;
            if(!$categoryname) :
                $categoryname = get_post($post_->post_parent);
                $categoryname = $categoryname->post_title;
            endif;
            $author = new user_info($post_->post_author);
            $profile = $author->get_profile_link($author->ID);
            /* HTML For posts
            ---------------------------------------------*/
             ?>
                <tr>
                    <td><?php echo date('d - F - Y \<\b\r\> h:s A', strtotime($post_->post_date)) ?></td>
                    <td>What would you do</td>
                    <td>
                        <a href="<?php echo get_the_permalink($post_->ID) ?>"><?php echo $post_->post_title ?></a>
                        <?php if(1 == 2): ?><p>by <a href="<?php echo $profile ?>"><?php echo $author->name ?></a></p><?php endif; ?>
                    </td>
                    <td>"<?php echo substr(strip_tags($post_->post_content), 0, 80) ?>"</td>
                </tr>
            <?php
            /* HTML For posts
            ---------------------------------------------*/
        endforeach;
    endif;
}

function ajax_get_comments($comments = false, $page = 0) {
    /* purge everything on post vars */
    $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    /* get the page */
    $page = $_POST['p'] ? $_POST['p'] : $page;

    /* user id */
    $id = user_info::decrypt($_POST['i']);
    $user = new user_info($id);
    /* multi support for static and ajax */
    $comments = $comments ? $comments : $user->get_comments(false, $page);

    if(is_array($comments) && count($comments) > 0) :
        foreach ($comments as $key => $comment) :
            /* HTML For comments
            ---------------------------------------------*/
             ?>
                <tr>
                    <td><?php echo date('d - F - Y \<\b\r\> h:s A', strtotime($comment['date'])) ?></td>
                    <td><?php echo $comment['category']; ?></td>
                    <td>
                        <a href="<?php echo $comment['permalink'] ?>"><?php echo ucfirst(($comment['title'])) ?></a>
                        <?php if(1 == 2): ?><p>by <a href="<?php echo $comment['profile'] ?>"><?php echo $comment['name'] ?></a></p><?php endif; ?>
                    </td>
                    <td>"<?php echo substr($comment['comment'], 0, 80) ?>"</td>
                </tr>
            <?php
            /* HTML For comments
            ---------------------------------------------*/
        endforeach;
    endif;
}

function ajax_get_userimages($images = false, $page = 1) {
    /* purge everything on post vars */
    $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    /* purge everything on post vars */
    $_GET  = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
    /* get the page */
    $page = $_POST['p'] ? $_POST['p'] : $page;

    $id = user_info::decrypt($_POST['i'] ? $_POST['i'] : $_GET['u']);
    $user = new user_info($id);

    $images = $images ? $images : $user->get_images(5, $page);

    if(is_array($images) && count($images)) :
        foreach ($images as $image) :
            ?>
            <tr>
                <td><?php echo $image['date'] ?></td>
                <td>
                    Pictures from friends
                    <ul>
                        <li><a href="<?php echo $image['link'] ?>"><?php if(1 ==2): ?><i class="fa fa-angle-right"></i><?php echo $image['title']; endif; ?></a></li>
                    </ul>
                </td>
                <td>
                    <a href="<?php echo $image['link'] ?>"><?php echo $image['title'] ?></a>
                    <!-- <p>by <a href="#"><?php echo $user->name ?></a></p> -->
                </td>
                <td><img src="<?php echo $image['image'] ?>" alt=""></td>
            </tr>
            <?php
        endforeach;
    endif;
}

function ajax_get_usernotification() {
    /* purge everything on post vars */
    $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    /* purge everything on post vars */
    $_GET  = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
    /* get the page */
    $val = $_POST['val'] ? $_POST['val'] : $val;
    $id = user_info::decrypt($_POST['i'] ? $_POST['i'] : $_GET['u']);
    $user = new user_info($id);

    $images = $images ? $images : $user->get_images(5, $page);

    if(is_array($images) && count($images)) :
        foreach ($images as $image) :
            ?>
            <tr>
                <td><?php echo $image['date'] ?></td>
                <td>
                    GALLERY
                    <ul>
                        <li><a href="<?php echo $image['link'] ?>"><?php if(1 ==2): ?><i class="fa fa-angle-right"></i><?php echo $image['title']; endif; ?></a></li>
                    </ul>
                </td>
                <td>
                    <a href="<?php echo $image['link'] ?>"><?php echo $image['title'] ?></a>
                    <!-- <p>by <a href="#"><?php echo $user->name ?></a></p> -->
                </td>
                <td><img src="<?php echo $image['image'] ?>" alt=""></td>
            </tr>
            <?php
        endforeach;
    endif;
}
 ?>