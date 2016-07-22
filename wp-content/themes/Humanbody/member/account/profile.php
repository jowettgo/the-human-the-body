<?php
/* purge everything on get and post */
$_GET   = filter::get();
$_POST  = filter::post();

$user = wp_get_current_user();
$meta = get_user_meta($user->ID);
$uid = $user->ID;
$user = new user_info();
$u_id = $user->encrypt($uid);
$user_info = $user->get();
$edit_profile = page('my-account-edit');

?>
<div id="content" class="secondary-subpage cont-utilizator">

	<!-- cont-utilizator-section -->
	<div class="cont-utilizator-section">
		<!-- Start top-banner-wrapper -->
		<div class="top-banner-wrapper">

			<div class="container">
				<!-- Start Bredcrumb -->
				<div class="breadcrumb-wrapper clearfix">

					<ol vocab="http://schema.org/" typeof="BreadcrumbList">
						<?php spinal_breadcrumb() ?>
					</ol>

				</div>
				<!-- End Bredcrumb -->

				<!-- Start cont-wrapper -->
				<div class="cont-wrapper">
					<div class="row">
						<div class="col-md-10 col-md-offset-1">
							<?php include_once('account-topbar.php') ?>
						</div>
						<div class="col-md-10 col-md-offset-1">
							<div id="no-more-tables">
								<?php
                                /*
                                COMMENTS
                                ----------------------------------------- */
                                 ?>
                                <?php
                                $comments = $user->get_comments(false, $page);
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
                                        <a href="javascript:void(0)" class="load-more" data-u="<?php echo $uid ?>">load more</a>
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
                                        <a href="javascript:void(0)" class="load-more" data-u="<?php echo $uid ?>">load more</a>
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

									<a href="javascript:void(0)" class="load-more" data-u="<?php echo $uid ?>">load more</a>

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
                                    <a href="javascript:void(0)" class="load-more" data-u="<?php echo $uid ?>">load more</a>
                                <?php endif; ?>
                            </div>
                            <?php
                            endif;
                            ?>  





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
