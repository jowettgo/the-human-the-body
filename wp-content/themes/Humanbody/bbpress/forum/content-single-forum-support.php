<!-- start content -->
<div id="content" class="support community-subpage">

	<!-- start forum section  -->
	<div class="support-section">
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

					<!-- Start search wrapper -->
					<div class="search-wrapper col-lg-12 col-md-12 col-sm-12 col-xs-12">
						
						<a class="fancy-therapeut" href="#fancybox-therapeut">View therapists</a>
						<div id="fancybox-therapeut" class="asdasd">
							<ul>

								<?php
									$type = 'psychotherapist';
									$args=array(
									  'post_type' => $type,
									  'orderby' => 'date',
									  'post_status' => 'publish',
									  'posts_per_page' => -1,
									);
									$my_query = new WP_Query($args);
									if( $my_query->have_posts() ) {
									  while ($my_query->have_posts()) : $my_query->the_post();
									  $content = get_the_content();
									  $custom_field = get_post_custom();
									?>
									<li>
										<?php 
											if ( has_post_thumbnail() ) {
											    the_post_thumbnail();
											}
										?>
										<span class="name-psiho"><?php the_title() ?></span> 
										<?php echo apply_filters('the_content', the_content()) ?>
									</li>
							    	<?php
									  endwhile;
									}
									wp_reset_query();  // Restore global post data stomped by the_post().
								?>
							</ul>
						</div>

						<?php
							if(is_user_logged_in()) {
								?>
									<p>Mesaj user nelogat</p>
								<?php
							} else {
								?>
									<form class="searchbox" action="#">
										<input type="text" placeholder="Search for topics..." id="search-forums">
										<input type="submit">
									</form>
								<?php
							}
						?>

					</div>
					<!-- End search wrapper -->
				</div>
			</div>
		</div>
		<div class="subpage-bot">

			<div class="container">
				<div class="row">
					<?php if ( post_password_required() ) : ?>
						<?php bbp_get_template_part( 'form', 'protected' ); ?>
					<?php else : ?>

					<!-- start category wrapper -->
					<ul class="category-wrapper-outer clearfix list-forum ?>">
						<?php if ( bbp_has_forums( array('posts_per_page'=>1000)) ) : ?>

							<?php while ( bbp_forums() ) : bbp_the_forum(); ?>

								<li class="category-list-items">
									<div class="category-wrapper-inner">
										<span class="post-count"><?php bbp_forum_topic_count($post->ID, true) ?></span>
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


						<?php endif; ?>




					</ul>

					<?php endif; ?>




					<?php if ( !bbp_is_forum_category() && bbp_has_topics() ) : ?>

						<?php bbp_get_template_part( 'pagination', 'topics'    ); ?>

						<?php bbp_get_template_part( 'loop',       'topics'    ); ?>

						<?php bbp_get_template_part( 'pagination', 'topics'    ); ?>

						<?php //bbp_get_template_part( 'form',       'topic'     ); ?>

					<?php elseif ( !bbp_is_forum_category() ) : ?>

						<?php //bbp_get_template_part( 'feedback',   'no-topics' ); ?>

						<?php //bbp_get_template_part( 'form',       'topic'     ); ?>

					<?php endif; ?>

					<!-- start category wrapper -->
					</div>

					<!-- start pagination -->
					<div class="back-wrapper">
						<a href="<?php echo page('community') ?>" class="back"><i class="fa fa-angle-left"></i>back</a>
					</div>
					<!-- end pagination -->
				</div>
			</div>
			<!-- end research top inner -->
		</div>
		<!-- end forum section  -->

	</div>
	<!-- end content -->
