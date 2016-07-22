
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

						<p>
							<?php echo apply_filters('the_content', $post->post_content) ?>
						</p>

					</div>
					<!-- End description wrapper -->
				</div>
				<!-- / forum title and description -->
				<!-- most discussed topics -->
				<div class="row">
					<div class="col-md-12">
						<h5 class="divider">MOST DISCUSSED TOPICS</h5>
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
						$parentforumID = $post->ID;
						$subforumIDS = subforums($parentforumID);

						$spinal_comments = new spinal_comments;

						$args = array(
							'post_type' => 'topic',
							'posts_per_page' => 50,
							/*'orderby' => array(
								'meta_key' => '_bbp_voice_count',
								'meta_value_num' => 'DESC'
							),*/
							'orderby' => 'comment_count',
							'order'   => 'DESC',
							'post_parent__in' => $subforumIDS

						);
						$topics = new WP_Query($args);
						?>
						<table class="table-condensed clearfix">
							<thead class="clearfix">
								<tr>
									<th class="title-table">Most discussed</th>
									<th class="date-table">Updated</th>
									<th class="number-people">People talking</th>
									<th class="category-table">Category</th>
								</tr>
							</thead>
							<tbody>

								<?php
								foreach ($topics->posts as $topic) {
									$tid = $topic->ID;
									$topicMeta = get_post_meta($topic->ID);

									/* get the topic date and hour last updated */
									$updated = explode(' ', $topicMeta['_bbp_last_active_time'][0]);
									$voices = $topicMeta['_bbp_voice_count'][0];
									$date = str_replace('-', '.', $updated[0]);
										$dateparts = explode('.', $date);
											$date = $dateparts[2].'.'.$dateparts[1].'.'. $dateparts[0];
									$hour = substr($updated[1], 0, 5);
									$subforumID = get_post($topic->post_parent)->ID;
									$subforumTitle = get_the_title($subforumID);
									$subforumPermalink = get_the_permalink( $subforumID );

									$number_of_commenters = $spinal_comments->get_unique_commenters($tid);
									/* TOPIC HTML
									-----------------------------------------------------*/
									 ?>
									<!-- topic -->
									<tr>
										<td data-title="<?php echo get_the_title($tid); ?>" class="first">
											<a href="<?php echo get_the_permalink($tid); ?>"><?php echo get_the_title($tid); ?></a>
										</td>
										<td data-title="Updated">
											<b><?php echo $date ?></b>
											<br>
											<span><?php echo $hour ?></span>
										</td>
										<td data-title="People talking" class="last"><?php echo $number_of_commenters; ?></td>
										<td data-title="Category" class="last-after"><a href="<?php echo $subforumPermalink ?>"><?php echo $subforumTitle ?></a></td>
									</tr>
									<!-- topic -->
								<?php } ?>


							</tbody>
						</table>
					</div>
					<!-- end table -->
					<div class="row">
						<!-- End description wrapper -->
						<div class="col-lg-12">
							<form class="searchbox" action="#">
									<input type="text" id="search-forum" placeholder="Search for topics...">
									<input type="submit">
								</form>
						</div>
					</div>
				</div>
			</div>
			<!-- end research top inner -->



		</div>


	</div>
		<!-- start category-forum section -->
	<div class="category-forum-section">
		<div class="container">
			<!-- start divider -->
			<div class="row">
				<div class="col-md-12">
					<h5 class="divider grey">Categories</h5>
				</div>
			</div>
			<!-- end divider -->
			<div class="row">

					<?php if ( post_password_required() ) : ?>
						<?php bbp_get_template_part( 'form', 'protected' ); ?>
					<?php else : ?>

					<!-- start category wrapper -->
						<?php if ( bbp_has_forums(  array('posts_per_page'=>1000) ) ) : ?>
							<ul class="category-wrapper-outer default clearfix">

							<?php while ( bbp_forums() ) : bbp_the_forum(); ?>
								<li class="category-list-items">
									<div class="category-wrapper-inner">
										<span class="post-count"><?php bbp_forum_topic_count() ?></span>
										<div class="forum-category-inner">
											<a href="<?php bbp_forum_permalink(); ?>">
												<span>
													<?php bbp_forum_title(); ?>
												</span>
											</a>
										</div>
									</div>
								</li>
							<?php endwhile; ?>
							</ul>
						<?php endif; ?>
					<?php endif; ?>




					<?php if ( !bbp_is_forum_category() && bbp_has_topics() ) : ?>

						<?php bbp_get_template_part( 'pagination', 'topics'    ); ?>

						<?php bbp_get_template_part( 'loop',       'topics'    ); ?>

						<?php bbp_get_template_part( 'pagination', 'topics'    ); ?>

						<?php bbp_get_template_part( 'form',       'topic'     ); ?>

					<?php elseif ( !bbp_is_forum_category() ) : ?>

						<?php //bbp_get_template_part( 'feedback',   'no-topics' ); ?>

						<?php //bbp_get_template_part( 'form',       'topic'     ); ?>

					<?php endif; ?>



					<!-- start category wrapper -->
				</div>

			</div>
		</div>

		<!-- end category-forum section -->


	</div>
	<!-- end content -->
