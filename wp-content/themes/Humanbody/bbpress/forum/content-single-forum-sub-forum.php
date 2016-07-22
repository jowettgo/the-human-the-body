<?php
global $wp_query;

$forum = $post;
/* this is the forum id */
$forumID = $forum->ID;

if(logged_in()) {
	$_POST = filter::post();
	if(isset($_POST['topic-title']) && $_POST['topic-title'] && $_POST['description-topic'] && $_POST['new-topic']) :
		$user = wp_get_current_user();
		$topic_data = apply_filters( 'bbp_new_topic_pre_insert', array(
			'post_author'    => $user->ID,
			'post_title'     => $_POST['topic-title'],
			'post_content'   => $_POST['description-topic'],
			'post_status'    => 'publish',
			'post_parent'    => $post->ID,
			'post_date' => date('Y-m-d H:i:s'),
			'post_date_gmt' =>  date('Y-m-d H:i:s'),
			'post_type'      => bbp_get_topic_post_type(),
			'comment_status' => 'open'
		) );

		// Insert topic
		$topic_id = wp_insert_post( $topic_data );

		update_post_meta($topic_id, 'type', 'topic');
		update_post_meta($topic_id, '_bbp_forum_id', $forumID);
		update_post_meta($topic_id, '_bbp_topic_id', $topic_id);
		update_post_meta($topic_id, '_bbp_author_ip', getUserIP());
		update_post_meta($topic_id, '_bbp_last_active_time', date('Y-m-d H:i:s'));
		update_post_meta($topic_id, '_bbp_reply_count', 0);
		update_post_meta($topic_id, '_bbp_reply_count_hidden', 0);
		update_post_meta($topic_id, '_bbp_last_active_id', 0);
		update_post_meta($topic_id, '_bbp_voice_count', 1);
		update_post_meta($topic_id, '_bbp_last_reply_id', 0);
		update_post_meta($topic_id, '_bbp_status', 'publish');
		update_post_meta($post->ID, '_bbp_topic_count', ($meta['_bbp_topic_count'][0]) + 1 );
		update_post_meta($post->ID, '_bbp_total_topic_count', ($meta['_bbp_total_topic_count'][0]) + 1 );


		wp_redirect(get_the_permalink());
	endif;
}
?>

<!-- start content -->
<div id="content" class="forum community-subpage">
	<!-- start research section  -->
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

				<div class="row">
					<!-- Start description wrapper -->
					<div class="description-wrapper col-lg-12 col-md-12 col-sm-12 col-xs-12">

						<h1>
							<?php the_title() ?>
						</h1>

						<?php echo apply_filters('the_content', $post->post_content) ?>

					</div>
					<!-- End description wrapper -->

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
									<input  tabindex="<?php bbp_tab_index(); ?>" type="text" value="<?php echo esc_attr( bbp_get_search_terms() ); ?>" name="search-forum"  placeholder="Search for topics...">
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
                                <?php
                                /* SELECT SUBFORUM (named category, but actually its a subforum)
                                ----------------------------------------------------------------------------------------- */
								if(logged_in()) :
                                 ?>
								 <a class="add-topic" href="#fancybox-avatar3"><!-- <i class="fa fa-plus"></i> --> Add New Topic</a>
							 	<?php endif; ?>
                                 <div class="category-inner-search">
 									<div id="nav-icon-category">
 										<!-- <span></span>
 										<span></span>
 										<span></span> -->
 									</div>
 									<select class="category-select" id="select-category-forum">

                                        <option>Categories</option>

                                        <?php
                                        $allsubforums = subforums($forum->post_parent);

                                        foreach ($allsubforums as $fID) :

                                            $forum = get_the_title($fID);
                                            $forumLink = get_the_permalink( $fID );
                                            ?>
                                            	<option value="<?php echo $forumLink ?>"><?php echo $forum ?></option>
                                            <?php

                                        endforeach;
                                         ?>


 									</select>

								</div>
                                <?php

                                /* END SELECT SUBFORUM
                                ----------------------------------------------------------------------------------------- */
                                 ?>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
		<div class="subpage-bot">
			<div class="container">
				<!-- start table -->
				<div id="no-more-tables" class="clearfix">

                    <?php
                    /* TOPICS
                    ----------------------------------------------------------------------------------------- */
                     ?>
                    <?php if ( !bbp_is_forum_category() && bbp_has_topics() ) : ?>

					<table class="table-condensed clearfix">
						<thead class="clearfix">
							<tr>
								<th class="title-table"><?php the_title() ?></th>
								<th class="date-table">Updated</th>
								<th class="number-people">People talking</th>
							</tr>
						</thead>
						<tbody>


                		<?php
                        /* topics loop */
                        $spinal_comments = new spinal_comments;
                        while ( bbp_topics() ) : bbp_the_topic();
                            $topicMeta = get_post_meta($post->ID);

                            /* get the topic date and hour last updated */
                            $updated = explode(' ', $topicMeta['_bbp_last_active_time'][0]);
                            $date = str_replace('-', '.', $updated[0]);
                                $dateparts = explode('.', $date);
                                    $date = $dateparts[2].'.'.$dateparts[1].'.'. $dateparts[0];
                            $hour = substr($updated[1], 0, 5);


                            /* TOPIC HTML
                            -----------------------------------------------------*/
                             ?>
                            <!-- topic -->
                            <tr>
								<td data-title="<?php bbp_topic_title(); ?>" class="first">
                                    <a href="<?php bbp_topic_permalink(); ?>"><?php bbp_topic_title(); ?></a>
                                </td>
								<td data-title="Updated">
									<b><?php echo $date ?></b>
									<br>
									<span><?php echo $hour ?></span>
								</td>
								<td data-title="People talking" class="last">
									<?php //bbp_topic_voice_count(); ?>
									<?php
										$number_of_commenters = $spinal_comments->get_unique_commenters($post->ID);
										echo $number_of_commenters;
									?>									
								</td>
							</tr>
                            <!-- topic -->
                            <?php
                            /* TOPIC HTML
                            -----------------------------------------------------*/
                            ?>


                        <?php
                        /* end topics loop */
                        endwhile; ?>

						</tbody>
					</table>
                    <?php
                    /* END TOPICS
                    ----------------------------------------------------------------------------------------- */
                    ?>


                    <?php
                    /* NO TOPICS ACTION
                    ----------------------------------------------------------------------------------------- */
                    ?>
                    <?php elseif ( !bbp_is_forum_category() ) : ?>
                        <!-- no topics -->
                        <?php //bbp_get_template_part( 'feedback',   'no-topics' ); ?>

                        <?php //bbp_get_template_part( 'form',       'topic'     ); ?>

                    <?php endif; ?>
                    <?php
                    /* NO TOPICS ACTION
                    ----------------------------------------------------------------------------------------- */
                    ?>


				</div>
                <!-- end table -->
                <?php
                /* PAGINATION
                ----------------------------------------------------------------------------------------- */
                ?>
    				<!-- start pagination -->
    				<nav class="pagination-wrapper">
    					<?php bbp_paginate() ?>
    				</nav>
    				<!-- end pagination -->
                <?php
                /* END PAGINATION
                ----------------------------------------------------------------------------------------- */
                ?>
			</div>
		</div>
		<!-- end research top inner -->
	</div>
	<!-- end research section  -->

</div>
<!-- end content -->

<div id="fancybox-avatar3" class="avatar-popup" style="display: none;">
	<h3>Add new topic</h3>
	<div class="avatar-inner">
		<form id="add-topic" class="sing-up-general clearfix" method="post">
			<div class="col-md-12">
				<label for="topic">Topic Title</label>
				<input type="text" id="topic" name="topic-title">
			</div>
			<div class="col-md-12">
				<label for="description">Description</label>
				<textarea name="description-topic" id="description"></textarea>
			</div>
			<div class="col-md-12">
				<input type="submit" name="new-topic" value="Create Topic">
			</div>
		</form>
	</div>
</div>
