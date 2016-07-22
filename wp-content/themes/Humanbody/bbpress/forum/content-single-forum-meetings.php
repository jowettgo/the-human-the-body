<?php
/* adds a hook to member premium pages */
do_action('member-premium-header-hook');
$forum = $post;

 ?>
<!-- start content -->
<div id="content" class="forum community-subpage">

	<!-- start forum section  -->
	<div class="community-section">
		<!-- start research top inner -->
		<div class="subpage-top">
			<div class="container">
				<!-- Start Bredcrumb -->
				<div class="breadcrumb-wrapper clearfix">

					<ol vocab="http://schema.org/" typeof="BreadcrumbList">
						<?php spinal_breadcrumb() ?>
					</ol>

				</div>
				<!-- End Bredcrumb -->

				<!-- forum title and description -->
				<div class="row">
					<!-- Start description wrapper -->
					<div class="description-wrapper col-lg-12 col-md-12 col-sm-12 col-xs-12">

						<h1>
							<?php the_title() ?>
						</h1>

						<?php echo apply_filters('the_content', $post->post_content) ?>

					</div>
					<!-- End description wrapper -->
				</div>
				<!-- / forum title and description -->
				<!-- most discussed topics -->
				<div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 community-search">
						<div class="row">
							<!-- Start search wrapper -->
							<div class="search-wrapper col-lg-9 col-md-8 col-sm-8 col-xs-12">
                                <?php
                                /* SEARCH FORM
                                ----------------------------------------------------------------------------------------- */
                                 ?>
                                <!-- search topics -->
                                <form class="searchbox" method="post" id="bbp-search-form" action="<?php bbp_search_url(); ?>">
                                    <input type="hidden" name="action" value="search-request" />
                                    <input type="hidden" name="id" value="<?php echo $post->ID ?>" />
                                    <input  tabindex="<?php bbp_tab_index(); ?>" type="text" value="<?php echo esc_attr( bbp_get_search_terms() ); ?>" name="search-forum"  placeholder="Search ...">
                                    <input tabindex="<?php bbp_tab_index(); ?>" class="button" type="submit" />
                                </form>
                                <!-- / search topics -->
                                <?php
                                /* END SEARCH FORM
                                ----------------------------------------------------------------------------------------- */
                                 ?>
							</div>
							<!-- End search wrapper -->
							<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
								<a class="add-topic" href="#fancybox-meeting"><!-- <i class="fa fa-plus"></i> -->create a get-together</a>
                                <!-- start avatar popup -->
	                            <div id="fancybox-meeting" class="avatar-popup">
                            		<h3>Create a get-together</h3>
                            		<!-- start form -->
                            		<form method="post" action="#" id="meeting-form" class="sing-up-general">

                            			<div class="row">
                            				<div class="col-sm-6 col-md-6">
                            					<label for="title">Title</label>
                            					<input type="text" id="title" name="title"/>
                            				</div>
                            				<div class="col-md-6 col-sm-6">
                            					<label for="location">Location</label>
                            					<input type="text" name="location" id="location"/>
                            				</div>
                            			</div>

                            			<div class="row">

                            				<div class="col-sm-6 col-md-6">
                            					<label for="meeting-date">Date of get-together:</label>
                            					<div class="dropdown datepicker clearfix">
                            						<input type="text" name="meeting-date" id="meeting-date" class="date-picker"/>
                            					</div>
                            				</div>


                            				<div class="col-md-6 col-sm-6">
                            					<label for="time-date">Time of get-together:</label>
                            					<div class="dropdown datepicker clearfix">
                            						<input type="text" name="time-date" id="time-date" />
                            					</div>
                            				</div>

                            			</div>
                            			<div class="row">
                            				<div class="col-md-6 col-sm-6">
                            					<label for="description">Short Description</label>
                            					<textarea name="short-description" id="description" ></textarea>
                            				</div>
                            				<div class="col-md-6 col-sm-6">
                            					<label for="description">Description</label>
                            					<textarea name="description" id="description" ></textarea>
                            				</div>
                            			</div>

                            			<div class="row">

                            				<div class="col-md-12">
                            					<input type="submit" value="Create" />
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
				<!-- / most discussed topics -->
			</div>
			<!-- / container -->



			<div class="subpage-bot">

				<div class="container">
					<!-- start table -->
					<div id="no-more-tables" class="clearfix">

						<?php
						$forumID = $post->ID;
						$args = array(
							'post_type' => 'topic',
							'posts_per_page' => 50,
                            'paged' => get_query_var('paged' ),
							'post_parent' =>$forumID,
							// 'orderby' => array(
								// 'meta_key' => '_bbp_voice_count',
								// 'meta_value_num' => 'DESC'
							// ),

						);
						$topics = new WP_Query($args);
						?>


						<table class="table-condensed clearfix">
							<thead class="clearfix">
								<tr>
									<th class="title-table">Get-togethers taking place</th>
									<th class="location-table">Location</th>
									<th class="date-table">Happening</th>
									<th class="number-people">People attending</th>
									<th class="join-table">Join</th>
								</tr>
							</thead>
							<tbody>

								<?php
								$mcr = new user_messages();
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
									$nr = 2;
									/* TOPIC HTML
									-----------------------------------------------------*/
									 ?>
									<!-- topic -->
									<tr>
										<td data-title="<?php echo $topicTitle; ?>" class="first">
											<a href="<?php echo $topicPermalink; ?>"><?php echo $topicTitle; ?></a>
										</td>
										<td data-title="Location">
											<?php

												if(empty($location[1])){ ?>
													<b><?php echo $location[0] ?></b>
													<br>
													<span><?php echo $location[1] ?></span>
												<?php }else{ ?>
													<b><?php echo $location[0].',' ?></b>
													<br>
													<span><?php echo $location[1] ?></span>
												<?php }

											?>

										</td>
										<td data-title="Happenings">
											<b><?php echo $date ?></b>
											<br>
											<span><?php echo $time ?></span>
										</td>
										<td data-title="People attending" class="last total-attending">
											<a id="people-<?php echo $topic->ID;?>" title="" class="" href="#participants_m_all" data-target="#participants_m_all" data-pid="<?php echo $topic->ID;?>" data-toggle="modal">
												<?php echo $joined ?>
											</a>
											
											<table class="table-condensed table-modal-participants clearfix people_attending_append-<?php echo $topic->ID;?>" style="display: none">
												<?php
													$participantsm = unserialize($topicMeta['joined'][0]);
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

											</table>	

										</td>
										<?php if(strtotime(date('m/d/Y')) >= strtotime($date)): ?>
											<td data-title="Join">
											<button disabled style="background: darkgray; color: #fff">Closed</button>
											</td>
										<?php else: ?>
											<td data-title="Join"><button class="join-meeting <?php if(joined($topic->ID)) echo 'unjoin-meeting active';?>" data-text-active="joined" data-text-inactive="join" data-id="<?php echo $tid ?>">
												<span class="join-text">
													<?php
													if(!joined($topic->ID)) : ?>
														join
													<?php else : ?>
														joined
													<?php endif; ?>
												</span>
											</button></td>
										<?php endif; ?>

									</tr>
									<!-- topic -->
								<?php endwhile ?>


							</tbody>
						</table>
					</div>
					<!-- end table -->
                    <?php
                    /* PAGINATION
                    ----------------------------------------------------------------------------------------- */
                    ?>
                        <!-- start pagination -->
                        <nav class="pagination-wrapper">

							<ul class="pagination clearfix">
                                <?php
                                $pagination = new pagination();
                                $pagination->perpage = 50;
                                 ?>
                                <?php echo $pagination->paginate($topics, $forum) ?>
							</ul>

                        </nav>
                        <!-- end pagination -->
                    <?php
                    /* END PAGINATION
                    ----------------------------------------------------------------------------------------- */
                    ?>
                </div>
				</div>
			</div>
			<!-- end research top inner -->



		</div>
	</div>


<div class="modal fade" role="dialog" id="participants_m_all">
	<div class="modal-dialog">
	    <!-- Modal content-->
	    <div class="modal-content">
	    	<div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal">&times;</button>
		        <h4 class="modal-title">Participants</h4>
		    </div>
		    <div class="modal-body">


		    </div>									     

	    </div>
    </div>
</div>	
