<?php
$_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
/**
 * Search Content Part
 *
 * @package bbPress
 * @subpackage Theme
 */

$args = array(
	'post_type' => 'topic',
	'posts_per_page' => -1,
	'post_parent' => $_POST['id'],
	's' => $_POST['search-forum']
);
if($_POST['search-forum']) :
	$posts = new WP_Query($args);
endif;
$mainforum = get_page($_POST['id']);
$mainforumtitle = $forum->post_title;
$mainpermalink = get_the_permalink($forum->ID);
?>
<div id="content" class="biology community-subpage">

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
							<?php echo $mainforum->post_title ?>
						</h1>

						<p>
							<?php echo apply_filters('the_content', $mainforum->post_content) ?>
						</p>

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
									<form class="searchbox" method="post" id="bbp-search-form" action="<?php echo get_site_url() ?>/search/">
	                                    <input type="hidden" name="action" value="search-request" />
	                                    <input type="hidden" name="id" value="<?php echo $_POST['id'] ?>" />
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
                                 ?>

                                 <div class="category-inner-search">
 									<div id="nav-icon-category">
 										
 									</div>
 									<select class="category-select" id="select-category-forum">

                                        <option>Categories</option>

                                        <?php
                                        $allsubforums = subforums($mainforum->post_parent);

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
				<!-- start search-results -->
				<div class="row">
					<div class="col-md-12">

						<div class="search-results">
							<i class="fa fa-check"></i> <b><?php echo count($posts->posts) ?></b> results found in “<?php echo $mainforum->post_title ?>”
						</div>
					</div>
				</div>
				<!-- end search-results -->
				<?php
				if($_POST['search-forum'] && count($posts->posts) > 0) :
				 ?>
						<!-- start table -->
						<div id="no-more-tables" class="clearfix">
							<table class="table-condensed clearfix">
								<thead class="clearfix">
									<tr>
										<th class="title-table"><?php echo $mainforum->post_title ?></th>
										<th class="date-table">Updated</th>
										<th class="number-people">People talking</th>
									</tr>
								</thead>
								<tbody>
									<?php
									if($posts->have_posts()) :

										while($posts->have_posts()) :
											$posts->the_post();
											global $post;
											$topicMeta = get_post_meta($post->ID);

				                            /* get the topic date and hour last updated */
				                            $updated = explode(' ', $topicMeta['_bbp_last_active_time'][0]);
				                            $date = str_replace('-', '.', $updated[0]);
				                                $dateparts = explode('.', $date);
				                                    $date = $dateparts[2].'.'.$dateparts[1].'.'. $dateparts[0];
				                            $hour = substr($updated[1], 0, 5);
											$voicecount = $topicMeta['_bbp_voice_count'][0];

									 ?>
									<tr>
										<td data-title="<?php echo $forum->post_title ?>" class="first"> <a href="<?php the_permalink() ?>"><?php the_title() ?></a></td>
										<td data-title="Updated">
											<b><?php echo $date ?></b>
											<br>
											<span><?php echo $hour ?></span>
										</td>
										<td data-title="People talking" class="last"><?php echo $voicecount; ?></td>
									</tr>
									<?php
										endwhile;
									endif;
									 ?>
								</tbody>
							</table>
						</div>
						<!-- end table -->
				<?php else: ?>

				<?php endif; ?>
				<!-- start pagination -->
				<a href="<?php echo get_the_permalink($mainforum->ID) ?>" class="back"><i class="fa fa-angle-left"></i>back</a>
				<!-- end pagination -->
			</div>
		</div>
		<!-- end research top inner -->
	</div>
	<!-- end research section  -->

</div>
