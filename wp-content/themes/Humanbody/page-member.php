<?php
$_GET   = filter::get();
$_POST  = filter::post();
/*
 Template Name: Member Profile
 *
 * This is your custom page template. You can create as many of these as you need.
 * Simply name is "page-whatever.php" and in add the "Template Name" title at the
 * top, the same way it is here.
 *
 * When you create your page, you can just select the template and viola, you have
 * a custom page template to call your very own. Your mother would be so proud.
 *
 * For more info: http://codex.wordpress.org/Page_Templates
*/

/* ----------------------------------------------------------------------------------- */
/* redirect to sign in if user is not logged in */
logout_redirect(page('sign-in'));
/* ----------------------------------------------------------------------------------- */
do_action('member-profile');
/* check if we have the user password posted from the sign in form */
/* object used to get friendlist functionality */
$list = new friends_list();
?>
<?php
/* decrypt user id */
$mcr = new user_messages();
$user_id = $mcr->decrypt($_GET['u']);
/* redirect the user if id is not valid */
if(!$user_id || $user_id < 1) :
    wp_redirect( page('community') );
endif;


/* get current user */
$c_user = new user_info();
/* get user info */
$user = new user_info($user_id);
$info = $user->get($user_id);


/* this contains the current user info */
$currentInfo = $user->get();
$prem_check = get_userdata( $user_id );
?>

<?php get_header(); ?>
<div id="content" class="secondary-subpage profile-page-content">

	<!-- cont-utilizator-section -->
	<div class="cont-utilizator-section">
		<!-- Start top-banner-wrapper -->
		<div class="top-banner-wrapper">

			<div class="container">
				<!-- Start Bredcrumb -->
				<div class="breadcrumb-wrapper clearfix">

					<ol vocab="http://schema.org/" typeof="BreadcrumbList">
						<?php spinal_breadcrumb(); ?>
						<li property="itemListElement" typeof="ListItem" class="active">
                            <a><span property="name"><?php echo $info['name'] ?></span></a>
                            <meta property="position" content="1">
                        </li>
					</ol>

				</div>
				<!-- End Bredcrumb -->

				<!-- Start cont-wrapper -->
				<div class="cont-wrapper">
					<div class="row">
						<div class="col-md-12">
							<!-- start profile-page -->
							<div class="profile-page clearfix">
								<div class="profile-top-content clearfix">
									<div class="col-sm-8 col-md-9">
										<div class="profile-avatar">
											<img src="<?php echo $info['avatar'] ?>" alt="">
										</div>
										<h2><?php echo $info['name'] ?></h2>
										<p><?php echo $info['location']['city'].', '.$info['location']['country'] ?></p>
                                        <?php


                                        $postscount = $user->get_post_count($user_id);
                                        $postscount = (int)$postscount > 0 ? $postscount.' posts' : '';

                                        $commentscount = $user->get_comments_count($user_id);
                                        $commentscount = (int)$commentscount > 0 ?  ' ' . $commentscount.' comments' : '';

                                        $imagescount = $user->get_images_count($user_id);
                                        $imagescount = (int)$imagescount > 0 ? ' '.$imagescount . ' pictures' : '';
                                        ?>
										<p><?php echo $postscount ?><?php echo $commentscount ?><?php echo $imagescount ?></p>
										<p>User since:  <?php echo date('jS', strtotime($info['since'])).' of '.date('F Y', strtotime($info['since']));?></p>
										<?php echo (isset($prem_check->caps['premium']) && !empty($prem_check->caps['premium']) or isset($prem_check->caps['premium_member']) && !empty($prem_check->caps['premium_member']))?'<p>Membership status: Member</p>':'<p>Membership status: Free User</p>' ?>
									</div>
									<div class="col-sm-4 col-md-3">
										<div class="profile-options">
											<ul>
												<?php if($c_user->premium) : ?>
												<li>
													<a data-toggle="modal" data-target="#joined" href="#joined" class="<?php echo $premiumclass ?>" title="<?php echo $premium_notification ?>"><i class="fa"><img src="<?php echo bloginfo('template_url') ?>/library/img/crowd.svg" alt="get-togheter" width="25"></i>See joined get-togethers</a>
												</li>
												<?php endif ?>
                                                <li>
                                                    <?php if($c_user->premium) : ?>
                                                        <a href="#fancybox-get-together" class="add-together" data-u="<?php echo $_GET['u'] ?>">
    														Invite to get-together <i class="fa"><img src="<?php echo _IMG_ ?>crowd.svg" alt="get-togheter" width="25"/></i>
    													</a>
                                                    <?php endif ?>
                                                </li>
												<li>
                                                    <?php if(!$list->is($user_id)) : ?>
                                                    	<?php $message = $info['name'].' is not a premium member.<br>'.$info['name'].' will be notified that you have attempted to add them to your friends list.'; ?>
                                                        <a <?php echo (isset($prem_check->caps['premium']) && !empty($prem_check->caps['premium']) or isset($prem_check->caps['premium_member']) && !empty($prem_check->caps['premium_member']))?'':'class="not-premium" data-message="'.$message.'" data-href="'.$list->get_add_link($user_id).'"'; ?> href="<?php echo (isset($prem_check->caps['premium']) && !empty($prem_check->caps['premium']) or isset($prem_check->caps['premium_member']) && !empty($prem_check->caps['premium_member']))?$list->get_add_link($user_id):'#are-you-sure'; ?>">Add <?php echo $info['name'] ?> to friends list<i class="fa fa-user-plus"></i></a>
                                                    <?php else : ?>
                                                        <a style="opacity:0.5"> <?php echo $info['name'] ?> is in your friends list</a>
                                                    <?php endif; ?>
                                                </li>
												<li>
													<?php $message = $info['name'].' is not a premium member.<br>'.$info['name'].' will be notified that you have attempted to send them a message.'; ?>
													<a <?php echo (isset($prem_check->caps['premium']) && !empty($prem_check->caps['premium']) or isset($prem_check->caps['premium_member']) && !empty($prem_check->caps['premium_member']))?'':'class="not-premium" data-message="'.$message.'" data-href="'.$mcr->get_send_link($user_id).'"'; ?> href="<?php echo (isset($prem_check->caps['premium']) && !empty($prem_check->caps['premium']) or isset($prem_check->caps['premium_member']) && !empty($prem_check->caps['premium_member']))?$mcr->get_send_link($user_id):'#are-you-sure'; ?>">Send <?php echo $info['name'] ?> a message<i class="fa fa-envelope-o"></i></a>
												</li>

											</ul>
										</div>
									</div>
								</div>
								<?php if(strlen($user->description) == 0): ?>
									<style>
										.profile-hobbies:after{
											background: none;
										}
									</style>
								<?php endif; ?>
								<div class="profile-hobbies clearfix" >

                                    <!-- user description -->
                                    <?php if(strlen($user->description) > 0) : ?>
									<div class="col-md-12">
										<div class="about-profile">
											<h3>About me</h3>
											<p><?php
                                            echo nl2br($user->description)
                                            ?></p>
										</div>
									</div>
                                    <?php endif; ?>
                                    <!-- / user description -->
                                    <?php

                                		if(!empty($user->affections)){
											$list = explode(',', $user->affections);
										?>
                                			<div class="col-sm-4 col-md-4">
		                                    	<h3>CONDITIONS</h3>
												<ul>
													<?php

														foreach ($list as $li) { ?>
															<li><?php echo $li ?></li>
														<?php }

													?>
												</ul>
		                                    </div>
                                		<?php }

                                	?>
									<div class="col-sm-8 col-md-8">
                                        <?php
                                        if($c_user->premium) :
                                        $interests = get_option('interests-options');
                                         ?>

                                        <?php
                                        if($user->interests_2 && strlen($user->interests_2) > 0) : ?>
										<!-- start films-list -->
										<div class="films-list">
											<h3>Interests</h3>
                                            <ul>
                                            <?php foreach (explode(',', $user->interests_2) as $int) {

                                                $interestID = explode('-', $int);
                                                $interestID = $interestID[0];
                                                /* this is a category insterest */
                                                $category = csv_import_interests::get_category_object_by_id($interestID);
                                                $type =  csv_import_interests::get_type_object_by_id($category->interest_id);
                                                //$int = preg_replace('/[^a-z\s]/i', ' ', $int);
                                            ?>
												<li><?php echo $type->interest.': '.$category->category ?></li>
                                            <?php } ?>
                                            <?php if($user->interests_3) : ?>

                                                    <?php foreach (explode(',', $user->interests_3) as $int) {

                                                        $interestID = explode('-', $int);
                                                        $interestID = $interestID[0];
                                                        /* this is a category insterest */
                                                        $interest = csv_import_interests::get_interest_object_by_id($interestID);
                                                        $category = csv_import_interests::get_category_object_by_id($interest->category_id);
                                                        $type = csv_import_interests::get_type_object_by_id($category->interest_id);

                                                        //$int = preg_replace('/[^a-z\s]/i', ' ', $int);
                                                        // $int = preg_replace('/[^a-z\s]/i', ' ', $int);
                                                    ?>
        												<li><?php echo $type->interest.': '.$interest->interest_type ?></li>
                                                    <?php } ?>
    											</ul>

                                            <?php endif; ?>

										</div>
										<!-- end films-list -->
                                    <?php endif; ?>
									</div>

                                <?php endif; ?>
                                    <?php if($c_user->premium && $user->affections && false) : ?>
                                    <!-- end interests-details -->
                                    <div class="col-sm-4 col-md-4">
                                        <div class="affections">
                                            <h3>Affections</h3>
                                            <?php
                                            $affections = $user->affections ? explode(',' , $user->affections) : false;
                                             ?>
                                            <ul>
                                                <?php
                                                if($affections) :

                                                    foreach ($affections as $affection) :
                                                    ?>
                                                        <li>
                                                            <?php echo $affection ?>
                                                        </li>
                                                    <?php
                                                    endforeach;
                                                endif;
                                                 ?>
                                            </ul>
                                            <br>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    <div style="clear:both;"></div>
								</div>
                                <br><br>
							</div>
							<!-- end profile-page -->
						</div>
						<div class="col-md-10 col-md-offset-1">
							<!-- start no-more-tables -->
							<div id="no-more-tables">
                                <?php
                                /*
                                COMMENTS
                                ----------------------------------------- */
                                 ?>
                                <?php $comments = $user->get_comments(false, $page);
                                if(is_array($comments) && count($comments) > 0) :
                                 ?>
                                <div class="table-section comments">
                                    <h2>Comments</h2>
                                    <table class="table-condensed clearfix">
                                        <thead class="clearfix">
                                            <tr class="top-table">
                                                <th class="date-table">Date and time</th>
                                                <th class="section-table">Section</th>
                                                <th class="topic-table">Topic</th>
                                                <th class="excerpt-table">Excerpt</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            /* defined in library/features/@autoload.functions/ajax/user_profile.php */
                                            ajax_get_comments($comments)
                                            ?>
                                    </tbody></table>
                                    <?php if(count($comments) >= 5) : ?>
                                        <a href="javascript:void(0)" class="load-more" data-u="<?php echo $_GET['u'] ?>">load more</a>
                                    <?php endif ?>
                                </div>
                                <?php endif;


                                /* User posts
                                --------------------------------------------------------------------- */
                                $posts = $user->get_posts();
                                if(is_array($posts) && count($posts) > 0) :
                                 ?>
                                <div class="table-section posts">
                                    <h2>Posts</h2>
                                    <table class="table-condensed clearfix">
                                        <thead class="clearfix">
                                            <tr class="top-table">
                                                <th class="date-table">Date and time</th>
                                                <th class="section-table">Section</th>
                                                <th class="topic-table">Topic</th>
                                                <th class="excerpt-table">Excerpt</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        /* defined in library/features/@autoload.functions/ajax/user_profile.php */
                                        ajax_get_getUserPosts($posts)
                                        ?>
                                        </tbody>
                                    </table>
                                    <?php if(count($posts) >= 5) :  ?>
                                        <a href="javascript:void(0)" class="load-more" data-u="<?php echo $_GET['u'] ?>">load more</a>
                                    <?php endif; ?>
                                </div>
                                <?php
                                endif;
                                ?>
                                <?php
                                $images = $user->get_images(5, $page);
                                if(is_array($images) && count($images)) :
                                ?>
                                <div class="table-section pictures">
									<h2>Pictures</h2>
									<table class="table-condensed clearfix">
										<thead class="clearfix">
											<tr class="top-table">
												<th class="date-table">Date and time</th>
												<th class="section-table">Section</th>
												<th class="topic-table">Topic</th>
												<th class="excerpt-table">Excerpt</th>
											</tr>
										</thead>
										<tbody>

                                        <?php

                                            ajax_get_userimages($images = false);
                                         ?>
									    </tbody>
                                    </table>

									<a href="javascript:void(0)" class="load-more" data-u="<?php echo $_GET['u'] ?>">load more</a>

								</div>
                                <?php endif;
                                /* User Images
                                --------------------------------------------------------------------- */
                                ?>


                                <?php
                                /* User posts IDEAS
                                --------------------------------------------------------------------- */
                                $posts = $user->get_posts_ideas();
                                if(is_array($posts) && count($posts) > 0) :
                                 ?>
                                <div class="table-section ideas">
                                    <h2>Ideas</h2>
                                    <table class="table-condensed clearfix">
                                        <thead class="clearfix">
                                            <tr class="top-table">
                                                <th class="date-table">Date and time</th>
                                                <th class="section-table">Section</th>
                                                <th class="topic-table">Topic</th>
                                                <th class="excerpt-table">Excerpt</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        /* defined in library/features/@autoload.functions/ajax/user_profile.php */
                                        ajax_get_getUserPostsIdeas($posts, 0)
                                        ?>
                                        </tbody>
                                    </table>
                                    <?php if(count($posts) >= 5) :  ?>
                                        <a href="javascript:void(0)" class="load-more" data-u="<?php echo $_GET['u'] ?>">load more</a>
                                    <?php endif; ?>
                                </div>
                                <?php
                                endif;
                                ?>  


							</div>
							<!-- end no-more-tables
						</div>
					</div>
					<!-- End row -->
				</div>
				<!-- End cont-wrapper -->
			</div>
			<!-- End container -->
		</div>
		<!-- End top-banner-wrapper -->
	</div>
	<!-- cont-utilizator-section -->
</div>
<?php
/* INVITE TO GET TOGETHER
-------------------------------------------- */
 ?>
<!-- start avatar popup -->
<div id="fancybox-get-together" class="avatar-popup">
	<h3>Invite to get-together</h3>
	<?php
	$message = $info['name'].' is not a premium member. '.$info['name'].' will be notified that you have attempted to invite them to a get-together.';
	echo (isset($prem_check->caps['premium']) && !empty($prem_check->caps['premium']) or isset($prem_check->caps['premium_member']) && !empty($prem_check->caps['premium_member']))?'':'<p style="text-align: center;color: #fff;">'.$message.'</p>' ?>
	<!-- start form -->
	<form method="post" id="get-together-form" class="sing-up-general">
		<br>
		<div class="row">
			<div class="col-sm-12 col-md-8 col-md-offset-2">
				<label>Select meeting</label>
				<input type="hidden" name="u" id="get-together-input" value="">
				<div class="ui selection dropdown" id="meeting">
					<input type="hidden" name="meeting">
					<i class="dropdown icon"></i>
					<div class="default text">Select meeting</div>
					<div class="menu">
						<?php
						$meeting = get_page_by_path( 'meetings', 'OBJECT', 'forum' );
						$meetingID = $meeting->ID;

						$args = array(
							'post_type' => 'topic',
							'posts_per_page' => -1,
							'post_parent' =>$meetingID,
							'orderby' => array(
								'meta_key' => '_bbp_voice_count',
								'meta_value_num' => 'DESC'
							),
						);
						$topics = new WP_Query($args);
						$flo = new friends_list();
						if($topics && $topics->have_posts()) :
							while($topics->have_posts()) :
								$topics->the_post();
								?>

								<div class="item" data-value="<?php echo $flo->encrypt($post->ID) ?>" data-text="<?php the_title() ?>">
									<?php the_title() ?>
								</div>

								<?php
							endwhile;
						endif;
						 ?>
					</div>
				</div>
			</div>
		</div>
		<br>
		<br>
		<div class="row">

			<div class="col-md-12">
				<input type="submit" value="Invite" />
			</div>

		</div>

	</form>
	<!-- end form -->
</div>
<!-- end avatar popup -->

<?php if($c_user->premium) : ?>
<div class="modal fade" role="dialog" id="joined">
	<div class="modal-dialog">
	    <!-- Modal content-->
	    <div class="modal-content">
	    	<div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal">&times;</button>
		        <h4 class="modal-title">Joined get-togethers</h4>
		      </div>
		<!-- start table -->

		<?php if(!$c_user->premium) : ?>
		<div class="premium-notice">
		    <a href="<?php echo page('membership') ?>">Upgrade to premium membership and get more features</a>
		</div>
		<?php else: ?>
		<div id="no-more-tables" class="clearfix">

			<?php
			$forumID = 72;
			$args = array(
				'post_type' => 'topic',
	            'posts_per_page' => -1,
				// 'orderby' => array(
					// 'meta_key' => '_bbp_voice_count',
					// 'meta_value_num' => 'DESC'
				// ),

			);
			$topics = new WP_Query($args);
			$nr = 1;
			?>

            <table class="table-condensed clearfix">
			<?php if($topics->have_posts()): ?>

					<?php
					while ( $topics->have_posts() ) : $topics->the_post();

	                    $topic = $post;
						/* get topic id */
						$tid = $topic->ID;
						/* get topic meta */
						$topicMeta = get_post_meta($topic->ID);

						/* get the topic date and hour last updated */
						$updated = explode(' ', $topicMeta['_bbp_last_active_time'][0]);
						/* voices */
						$voices = $topicMeta['_bbp_voice_count'][0];
						/* get the date */
						$date = $topicMeta['date'][0];
						/* get meeting time */
						$time = $topicMeta['time'][0];

						$location = explode(',',$topicMeta['location'][0]);

						/* topic title and permalink */
						$subforumID = get_post($topic->post_parent)->ID;
						$topicTitle = get_the_title($tid);
						$topicPermalink = get_the_permalink( $tid );

						$joined = total_joined($tid);
						/* TOPIC HTML
						-----------------------------------------------------*/
						 ?>
						 <?php if(joined_user($topic->ID, $user->ID)): ?>



                        <?php if ($nr==1):?>
                            <thead class="clearfix">
                                <tr>
                                <th class="title-table">Get-togethers taking place</th>
                                <th class="location-table">Location</th>
                                <th class="date-table">Happening</th>
                                <th class="number-people">People attending</th>
                                </tr>
                            </thead>
                        <?php endif;?>


						<!-- topic -->
						<tr <?php echo $nr > 5?'style="display: none;"':''; ?>>
							<td data-title="<?php echo $topicTitle; ?>" class="first">
								<a href="<?php echo $topicPermalink; ?>"><?php echo $topicTitle; ?></a>
							</td>
							<td data-title="Location">
								<b><?php echo $location[0].',' ?></b>
								<br>
								<span><?php echo $location[1] ?></span>
							</td>
							<td data-title="Happenings">
								<b><?php echo $date ?></b>
								<br>
								<span><?php echo $time ?></span>
							</td>
							<td data-title="People attending" class="last total-attending"><?php echo $joined ?></td>
						</tr>
						<!-- topic -->

						<?php
						$nr++;
						endif; ?>
					<?php endwhile ?>
			
			<?php endif; ?>
            </table>

            <?php if($nr==1) : ?>
                <div class="cont-utilizator">
                    <h2 style="color: #000 !important;">No get-togethers joined</h2>
                </div>
            <?php endif; ?>

			<nav class="pagination-wrapper">
				<?php
				if($nr > 6): ?>
	            	<a href="javascript:void(0)" class="load-more" data-load="10">load more</a>
	            <?php endif; ?>
	        </nav>
		</div>
		<!-- end table -->
		<?php endif; ?>

	    </div>
    </div>
</div>

<?php endif; ?>

<?php
/* END INVITE TO GET TOGETHER
-------------------------------------------- */



 ?>

<div class="hidden">
    <div id="are-you-sure" class="text-center custom-message">
        <p></p>
        <a class="btn remove-no">Cancel</a>
        <a href="#" class="btn remove-yes">Ok</a>
    </div>
</div>
<?php get_footer(); ?>
