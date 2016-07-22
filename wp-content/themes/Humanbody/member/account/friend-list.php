<?php
$flo = new friends_list();
$friends = $flo->get();
$mcr = new user_messages();

 ?>
<div id="content" class="secondary-subpage messages">

	<!-- cont-utilizator-section -->
	<div class="cont-utilizator-section" style="background-image:url('<?php echo _IMG_ ?>bgi/friendlist.jpg')">
		<!-- Start top-banner-wrapper -->
		<div class="top-banner-wrapper">

			<div class="container">

				<!-- Start cont-wrapper -->
				<div class="cont-wrapper">
					<div class="row">
						<!-- start profile -->
						<div class="col-md-10 col-md-offset-1">
							<?php include_once('account-topbar.php') ?>
						</div>
						<!-- start profile -->
						<div class="col-md-12">
							<div class="table-section messages-table friend-list-table">
								<div class="title-wrapper">
									<h1>Friends list</h1>
								</div>
								<div id="no-more-tables" class="clearfix">
									<table class="table-condensed clearfix">
										<tbody>
											<?php if(is_array($friends) && count($friends) > 0) : 50 ?>
												<?php foreach ($friends as $id) :
													$u = new user_info();
                                                    $clean_user_id = $id;
													$friend = $u->get($id);
													$enc_id = $mcr->encrypt($friend['ID']);
													$profile = page('member').'?u='.$enc_id;

													$message_user = page('my-account-message-room').'?new=r&u='.$enc_id;
												 ?>
											<tr>
												<td>
													<div class="messenger-wrapper">

														<div class="messenger-inner">
															<img src="<?php echo $friend['avatar'] ?>" alt="x">
														</div>
													</div>
												</td>
												<td>
													<div class="friend-name">
														<a href="<?php echo $profile ?>"><?php echo $friend['name'] ?></a>
														<span><?php echo $friend['location']['city'].', '.$friend['location']['country'] ?></span>
													</div>
												</td>
												<td>
													<a href="<?php echo $message_user ?>">
														<i class="fa fa-envelope-o"></i>Send a message</a>
												</td>
												<td>
													<a href="#fancybox-get-together" class="add-together" data-u="<?php echo $enc_id ?>">
														<img src="<?php echo _IMG_ ?>crowd.svg" alt="get-togheter" width="20"/>Invite to get-together
													</a>
												</td>
												<td>
													<a href="<?php echo $flo->get_remove_link($friend['ID']) ?>" class="remove-friend"><i class="fa fa-close"></i></a>
												</td>
											</tr>
											<?php
									endforeach;
										endif;
											 ?>
										</tbody>
									</table>
									<!-- start avatar popup -->
									<div id="fancybox-get-together" class="avatar-popup">
										<h3>Invite to get-together</h3>
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

															if($topics->have_posts()) :
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
								</div>

							</div>

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
<a class="are-you-sure" href="#are-you-sure"></a>
<div class="hidden">
    <div id="are-you-sure" class="text-center">
        <h3>Are you sure?</h3>
        <p>you are about to remove this user from your friends list</p>
        <a href="javascript:void()" class="btn remove-no">no</a>
        <a href="javascript:void()" class="btn remove-yes">yes</a>
    </div>
</div>
