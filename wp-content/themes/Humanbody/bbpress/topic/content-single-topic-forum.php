<?php

/**
 * Single Topic Content Part
 *
 * @package bbPress
 * @subpackage Theme
 */

$topic = $post;
$title = get_the_title($topic->ID);
$description  = get_the_content($topic->ID);

$meta = get_post_meta($topic->ID);
$date = $meta['date'][0];
$time = $meta['time'][0];

$totalreplies = bbp_get_topic_post_count()-1;
$voicecount = $meta['_bbp_voice_count'][0];
$authorID = $post->post_author;
$userdata = new user_info();
$userinfo  =$userdata->get($authorID);

$avatar = $userinfo['avatar'];
$date = get_the_date('F jS, Y \a\t g:i a');

$spinal_comments = new spinal_comments;

$user = new user_info();
?>



<!-- start content -->
<div id="content">

	<!-- start what-can section  -->
	<div class="community-subpage forum-details">
		<!-- start what-can top inner -->
		<div class="events-top-inner">
			<div class="container">
				<!-- Start Bredcrumb -->
				<div class="breadcrumb-wrapper">

					<ol vocab="http://schema.org/" typeof="BreadcrumbList">
						<?php spinal_breadcrumb() ?>
					</ol>

				</div>
				<!-- End Bredcrumb -->

				<div class="row">
					<!-- Start description wrapper -->
					<div class="description-wrapper col-lg-12 col-md-12 col-sm-12 col-xs-12">

						<h1><?php echo $title; ?></h1>
					</div>
					<!-- End description wrapper -->
				</div>
			</div>
		</div>
		<!-- end what-can top inner -->

		<!-- start what-can top inner -->
		<div class="events-bottom-inner">
			<div class="container">
				<div class="row">
					<div class="col-lg-8 col-md-6 col-sm-6 col-xs-12">

						<div class="user-pic">
							<div class="user-img-outer">
								<div class="use-img-inner">
									<img src="<?php echo $avatar ?>" alt="user-avatar">
								</div>
								<span class="date-name">
									<span> <?php echo $userinfo['name'] ?></span>
									<time><?php echo $date ?></time>
								</span>

							</div>
						</div>

					</div>

					<div class="col-lg-2 col-md-3 col-sm-3 col-xs-6 topic-users">
						<i class="fa fa-users"></i>
						<div class="specifications">
							<?php //echo $voicecount ?>
							<?php
								$number_of_commenters = $spinal_comments->get_unique_commenters($topic->ID);
								echo $number_of_commenters;
							?>
						</div>

					</div>

					<div class="col-lg-2 col-md-3 col-sm-3 col-xs-6 topic-replies">
						<i class="fa fa-commenting"></i>
						<div class="specifications">
							<?php //echo $totalreplies ?>
							<?php
							$comments_count = wp_count_comments( $topic->ID );
							echo $comments_count->approved;
							?>
						</div>

					</div>

					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 topic-description">
						<hr style="border-top:1px solid rgba(255, 255, 255, 0.09)">
						<p>
							<?php echo $description; ?>
						</p>
					</div>

				</div>
			</div>
		</div>
		<!-- end what-can top inner -->
	</div>
	<!-- end what-can section  -->
	<!-- start comment section -->
	<div class="comment-section">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="article-separator">
						<h5 class="divider">Comments</h5>
					</div>

					<?php

						if ( !$user->premium )  {
							return;
							exit;
						}
					 ?>
					 <?php include_once('comments.php') ?>


					<?php /*
					//Native bbp comment system, not used anymore
					<!-- start comment wrapper -->
					<div class="comment-wrapper">


						 <?php if ( bbp_has_replies() ) : ?>

							 <?php if ( bbp_thread_replies() ) : ?>

 								<?php bbp_list_replies(); ?>

 							<?php else : ?>
								<!-- Replies -->
 								<?php
								$all_arr = array();
								$n = 0;
								$startsub = false;
								while ( bbp_replies() ) :
									bbp_the_reply(); ?>
									<?php

									// bb press adds current topic as reply 
									if($post->post_type != 'topic' ) :
										$n++;
										// deg($post);
										$level = $post->menu_order;
										if($post->reply_to > 0) $level = 1;
										else $level = 1;
										// only start container on the first element
										$userdata = new user_info();
										$userinfo  =$userdata->get($post->post_author);
										// deg($author);
										$name = ucfirst($userinfo['name']);

										 if ( $userinfo['administrator'] ) :
											 $adminstyle = 'admin-outer';
										else :
											$adminstyle = '';
										endif;
										//deg($post);
										$replyurl = reply_url(array(), $topic->ID);
									
									?>
									<ul class="comment-container comment-level-<?php echo $level; echo $n==1? ' first-meeting-reply' : '' ?>" id="post-<?php bbp_reply_id(); ?>">
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
														<span class="name"><?php echo $name ?> <?php bbp_reply_admin_links(); ?></span>
														<span class="date"><?php echo get_the_date('F jS, Y \a\t g:i a') ?></span>
														<?php
															if($post->reply_to > 0){
															$my_postid = $post->reply_to;//This is page id or post id
															$content_post = get_post($my_postid);
															$content = $content_post->post_content;
															$content = apply_filters('the_content', $content);
															$content = str_replace(']]>', ']]&gt;', $content);
														?>
														<div class="reply-to">
															<strong>Reply to:</strong>
															<?php echo html_entity_decode($content); ?>
														</div>
															
														<?php } ?>
														<?php //do_action( 'bbp_theme_before_reply_content' ); ?>
															<?php bbp_reply_content(); ?>
														<?php //do_action( 'bbp_theme_after_reply_content' ); ?>
														<?php
														if(logged_in()) :
															?>
															<!-- <a href="<?php echo $replyurl ?>" class="reply">
																<i class="fa fa-reply-all"></i> Reply
															</a> -->
															<?php
														endif;
														 ?>

														<?php do_action( 'bbp_theme_before_reply_admin_links' ); ?>

														<?php do_action( 'bbp_theme_after_reply_admin_links' ); ?>
													</div>
												</div>
											</div>
										</li>
									</ul>
									<?php
									//end check for reply as topic
									endif;
									 ?>

 								<?php endwhile; ?>

 							<?php endif; ?>


							<?php if(is_array(bbp_get_topic_pagination_links())) : ?>
							<div class="pagination-reply-meeting">
								<?php
								foreach (bbp_get_topic_pagination_links() as $key => $pagination) {
									echo $pagination;
								}
								 ?>
							</div>
						<?php endif; ?>
							<h5 class="divider"></h5>

				 		<?php endif; ?>



						<?php //bbp_get_template_part( 'form', 'reply' ); ?>
					</div>
					<!-- end comment wrapper -->
					


					<?php
						if ( logged_in() ) :
					 ?>
					<!-- start comment form -->
					<div class="feedback-wrapper">
						<h3>Share your thoughts</h3>
						<form id="new-post" name="new-post" method="post" action="<?php the_permalink(); ?>" class="feedback-form">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<label for="message">Your message*</label>

									<textarea id="message" tabindex="103" name="bbp_reply_content" required></textarea>
									<?php bbp_reply_form_fields() ?>
								<?php if ( bbp_is_subscriptions_active() && !bbp_is_anonymous() && ( !bbp_is_reply_edit() || ( bbp_is_reply_edit() && !bbp_is_reply_anonymous() ) ) ) : ?>

									<?php do_action( 'bbp_theme_before_reply_form_subscription' ); ?>



										<!-- <input name="bbp_topic_subscription" id="subscribe-reply" type="checkbox" value="bbp_subscribe"<?php bbp_form_topic_subscribed(); ?> tabindex="<?php bbp_tab_index(); ?>" />
										<?php if ( bbp_is_reply_edit() && ( bbp_get_reply_author_id() !== bbp_get_current_user_id() ) ) : ?>
											<label for="subscribe-reply"><?php _e( 'Notify the author of follow-up replies via email', 'bbpress' ); ?></label>
										<?php else : ?>
											<label for="subscribe-reply"><?php _e( 'Notify me of follow-up replies via email', 'bbpress' ); ?></label>
										<?php endif; ?> -->



									<?php do_action( 'bbp_theme_after_reply_form_subscription' ); ?>
									</div>
								<?php endif; ?>
							</div>
							<div class="row submit-wrapper">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">

									<input type="submit" tabindex="<?php bbp_tab_index(); ?>" name="bbp_reply_submit" class="button submit" value="<?php _e( 'Submit', 'bbpress' ); ?>">

								</div>

								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<a href="#" class="scroll-top"><i class="fa fa-long-arrow-up"></i>Back to top</a>
								</div>

							</div>

						</form>
					</div>
					<!-- end comment form -->
					<?php endif; ?>
					*/ ?>
	
				</div>

			</div>
		</div>

	</div>
	<!-- start comment section -->

</div>
<!-- end content -->
