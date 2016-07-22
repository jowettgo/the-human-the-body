<?php
$spinal_comments = new spinal_comments;

$list = $spinal_comments->get_comments($topic->ID);
 ?>
				<div class="comment-wrapper">
					<span><b><?php echo ( $spinal_comments->count()) ?> comments</b> on</span>
					<a class="article-title"><?php the_title() ?></a>

				    <?php


                    foreach ($list as $id => $comment) :
                        $replyurl = get_the_permalink( $post->ID).'?r='.$comment['comment']->comment_ID.'#new-reply';
                        $name = $comment->comment_author;

                        if($comment['comment']) :
                            $datetime = new DateTime($comment['comment']->comment_date);
                            $comtemp = $comment['comment'];

                            $authorid = get_user_by_email($comtemp->comment_author_email);

                            $userdata = new user_info();

                            $userinfo  =$userdata->get($authorid->ID, false,$comtemp);


                            $name = ucfirst($userinfo['name']);

                             if ( $userinfo['administrator'] ) :
                                 $adminstyle = 'admin-outer';
                            else :
                                $adminstyle = '';
                            endif;
                            $datecomment = date_format($datetime, 'F jS, Y \a\t g:i a');
                            $authorLink = $userdata->get_profile_link($authorid->ID);
							$current_user = new user_info();
                        ?>
                            <ul class="comment-container comment-level-1" id="comment-<?php echo $comment['comment']->comment_ID ?>">
                                <li class="clearfix">
                                    <div class="comment-outer clearfix <?php echo $adminstyle ?>">
                                        <div class="user-pic pull-left">
                                            <div class="user-img-outer">
                                                <div class="use-img-inner">
                                                    <img src="<?php echo $userinfo['avatar'] ?>" alt="user-avatar">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="right-comment pull-right">
                                            <div class="comment-inner">

                                            	<?php

                                            		if($current_user->name == $name){ ?>
                                            			<a href="<?php echo page('my-account') ?>" class="name"><?php echo $name ?> <?php $spinal_comments->admin_links(); ?></a>
                                            		<?php }else{ ?>
                                            			<a href="<?php echo $authorLink ?>" class="name"><?php echo $name ?> <?php $spinal_comments->admin_links(); ?></a>
                                            		<?php }

                                            	?>
                                                <span class="date"><?php echo $datecomment ?></span>
                                                <?php //do_action( 'bbp_theme_before_reply_content' ); ?>
                                                    <?php echo $comment['comment']->comment_content ?>
                                                <?php //do_action( 'bbp_theme_after_reply_content' ); ?>
                                                <a href="<?php echo $replyurl ?>" class="reply">
                                                    <i class="fa fa-reply-all"></i> Reply
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        <?php
                        endif;
                            if(count($comment['reply']) > 0) :
                            foreach ($comment['reply'] as $replyID => $reply) {
                                $datetime = new DateTime($reply->comment_date);
                                $datecomment = date_format($datetime, 'F jS, Y \a\t g:i a');

                                $authorid = get_user_by_email($reply->comment_author_email);
                                $userdata = new user_info();
                                $userinfo  =$userdata->get($authorid->ID, false, $reply);
                                // deg($author);
                                $name = ucfirst($userinfo['name']);

                                 if ( $userinfo['administrator'] ) :
                                     $adminstyle = 'admin-outer';
                                else :
                                    $adminstyle = '';
                                endif;
                                $authorLink = $userdata->get_profile_link($authorid->ID);
								$current_user = new user_info();
                                ?>
                                <ul class="comment-container comment-level-2" id="comment-<?php echo $reply->comment_ID ?>">
                                    <li class="clearfix">
                                        <div class="comment-outer clearfix <?php echo $adminstyle ?>">
                                            <div class="user-pic pull-left">
                                                <div class="user-img-outer">
                                                    <div class="use-img-inner">
                                                        <img src="<?php echo $userinfo['avatar'] ?>" alt="user-avatar">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="right-comment pull-right">
                                                <div class="comment-inner">
                                                	<?php

	                                            		if($current_user->name == $name){ ?>
	                                            			<a href="<?php echo page('my-account') ?>" class="name"><?php echo $name ?> <?php $spinal_comments->admin_links(); ?></a>
	                                            		<?php }else{ ?>
	                                            			<a href="<?php echo $authorLink ?>" class="name"><?php echo $name ?> <?php $spinal_comments->admin_links(); ?></a>
	                                            		<?php }

	                                            	?>
                                                    <span class="date"><?php echo $datecomment ?></span>
                                                    <?php //do_action( 'bbp_theme_before_reply_content' ); ?>
                                                        <?php echo $reply->comment_content ?>
                                                    <?php //do_action( 'bbp_theme_after_reply_content' ); ?>

                                                    <a href="<?php echo $replyurl ?>" class="reply">
                                                        <i class="fa fa-reply-all"></i> Reply
                                                    </a>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                                <?php
                            }
                        endif;
                         ?>
                        <?php
                    endforeach;



                     ?>

				</div>

				<div class="feedback-wrapper" id="new-reply">
					<h3>Share your thoughts</h3>
					<?php
                    echo $spinal_comments->form();
                     ?>
				</div>
