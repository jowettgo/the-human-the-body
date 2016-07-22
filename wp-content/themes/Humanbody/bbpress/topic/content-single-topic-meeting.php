<?php
/* adds a hook to member premium pages */
do_action('member-premium-header-hook');

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
$location = $meta['location'][0];
$date = $meta['date'][0];
$time = $meta['time'][0];
/* total joined users */
$participants = count(unserialize($meta['joined'][0]));
$ajaxTotalBase = joined($topic->ID) ? $participants-1 : $participants;

$totalreplies = bbp_get_topic_post_count()-1;

$user = new user_info();

$color1 = '#5969B0';
$color2 =  '#C39360';
?>



<!-- start content -->
<div id="content">

	<!-- start what-can section  -->
	<div class="events-section secondary-subpage">
		<div class="category-color" style="background: <?php echo $color1 ?>;
			background: -moz-linear-gradient(-45deg, <?php echo $color1 ?> 0%, <?php echo $color2 ?> 100%);
			background: -webkit-linear-gradient(-45deg, <?php echo $color1 ?> 0%,<?php echo $color2 ?> 100%);
			background: linear-gradient(135deg, <?php echo $color1 ?> 0%,<?php echo $color2 ?> 100%);
			filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='<?php echo $color1 ?>', endColorstr='<?php echo $color2 ?>',GradientType=1 );">
		</div>
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

						<p><?php echo $description; ?>
						</p>

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
					<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
						<i class="fa fa-map-marker"></i>
						<div>
							<strong>Location</strong>
						</div>
						<div  class="specifications">
							<?php echo $location ?>
						</div>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
						<i class="fa fa-calendar"></i>
						<div>
							<strong>Date</strong>
						</div>
						<div  class="specifications">
							<?php echo $date ?>
						</div>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
						<i class="fa fa-clock-o"></i>
						<div>
							<strong>Time</strong>
						</div>
						<div  class="specifications">
							<?php echo $time ?>
						</div>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
						<i class="fa fa-users"></i>
						<div>
							<a title="" class="" href="#participants_m" data-target="#participants_m" data-toggle="modal">
								<strong>Attendants</strong>
							</a>	
						</div>
						<div class="specifications update-ajax">
							<a title="" class="" href="#participants_m" data-target="#participants_m" data-toggle="modal">
								<?php echo $participants; ?>
							</a>

						</div>
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
				<div class="col-lg-8 col-lg-offset-2 col-md-8 col-md-offset-2 col-sm-12 col-xs-12">
					<div class="desciption-content">
						<h4>Description</h4>
						<p>
							<?php echo $description; ?>
						</p>
						<?php if(can_join()) : ?>

							<button id="join-meeting-ajax" class="join-meeting-detail <?php if(joined($topic->ID)) echo 'unjoin-meeting';?>"
	                        data-id="<?php echo $topic->ID ?>"
	                        data-text-active="joined" data-text-inactive="join the get-together">
								<i class="fa fa-check"></i>
								<span class="join-text">
									<?php
									if(!joined($topic->ID)) : ?>
										join the get-together
									<?php else : ?>
										joined
									<?php endif; ?>
								</span>



							</button>

					<?php else : ?>
						<a href="#" class="join-meeting-detail">
							<i class="fa fa-check"></i> switch to premium and join this get-together
						</a>
					<?php endif; ?>
					</div>

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





					<?php
					//Native bbp comment system, not used anymore
					/*
					<!-- start comment wrapper -->
					<div class="comment-wrapper">
						<span class="comment-counter"><b><?php echo $totalreplies; ?> comments</b> on</span>
						<div class="article-title meeting-title-reply">
							<a href="#"><?php echo $title ?></a>
						</div>

						 <?php if ( bbp_has_replies() ) : ?>

							<?php if ( bbp_thread_replies() ) : ?>

 								<?php bbp_list_replies(); ?>

 							<?php else : ?>
								<!-- Replies -->
 								<?php

								$n = 0;
								$startsub = false;
								while ( bbp_replies() ) :
									bbp_the_reply(); ?>
									<?php

									//bb press adds current topic as reply
									if($post->post_type != 'topic' ) :
										$n++;
										//deg($post);

										$level = $post->menu_order;


										if($post->reply_to > 0)  {
											$level = 2;
										} else {
											$level = 1;
										}	

                                        // only start container on the first element
										$userdata = new user_info($post->post_author);
										$name = ucfirst($userdata->name);

										 if ( $userdata->admin) :
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
															<img src="<?php echo $userdata->avatar ?>" alt="user-avatar">
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
														<!-- <a href="<?php echo $replyurl ?>" class="reply">
															<i class="fa fa-reply-all"></i> Reply
														</a> -->

														<?php do_action( 'bbp_theme_before_reply_admin_links' ); ?>

														<?php do_action( 'bbp_theme_after_reply_admin_links' ); ?>
													</div>
												</div>
											</div>
										</li>
									</ul>
									<?php
									// end check for reply as topic
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
						if ( $user->premium ) :
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


								</div>
								<?php if ( bbp_is_subscriptions_active() && !bbp_is_anonymous() && ( !bbp_is_reply_edit() || ( bbp_is_reply_edit() && !bbp_is_reply_anonymous() ) ) ) : ?>
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
					*/
					?>



				</div>

			</div>
		</div>

	</div>
	<!-- start comment section -->

</div>
<!-- end content -->


<div class="modal fade" role="dialog" id="participants_m">
	<div class="modal-dialog">
	    <!-- Modal content-->
	    <div class="modal-content">
	    	<div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal">&times;</button>
		        <h4 class="modal-title">Participants</h4>
		    </div>
		    <div class="modal-body">


					<table class="table-condensed table-modal-participants clearfix" >
						<tbody>
							<?php
									$mcr = new user_messages();
									$participantsm = unserialize($meta['joined'][0]);

									if(count($participantsm)>0 and !empty($participantsm)):
										foreach ($participantsm as $p) :

											$enc_id = $mcr->encrypt($p);
											$profile = page('member').'?u='.$enc_id;

											$meta = get_user_meta($p);

											$name = ucfirst($meta['first_name'][0]).' '.ucfirst($meta['last_name'][0]);

											if(!empty($meta)):
								?>

									<tr>
										<td><a href="<?php echo $profile ?>"><?php echo $name ?></a></td>
									</tr>
																		 											 											 											 											 											 											 											 											 					
								<?php 
											endif;
										endforeach;
									endif;
								?>
						</tbody>
					</table>


		    </div>									     

	    </div>
    </div>
</div>
