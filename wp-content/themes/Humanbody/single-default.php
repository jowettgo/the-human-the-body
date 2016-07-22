<?php
global $post;
setPostViews($post->ID);
$featuredImage = featured_image($post, 'spinal-full-featured');

$postMeta = get_post_meta($post->ID);
$featuredAlign = $postMeta['align-featured'][0];
$pageTemplates = get_all_page_templates();
$url = $_GET['s'] ? get_site_url().'?s='.$_GET['s'] : $pageTemplates['page-blog.php']['url'];


$category = get_the_category();
if(is_array($category)) :

endif;

$catID =$category[0]->cat_ID;
$meta = get_term_meta($catID);
$color1 = $meta['color1'][0];
$color2 = $meta['color2'][0];
$cat_image = $meta['image'][0];
$featured_image = featured_image($post, 'large');

$link = get_the_permalink($post->ID);
$title = get_the_title().' | '.get_bloginfo( 'name');
$image = $featured_image;

 ?>

<div id="content" class="humans">

	<!-- Start top-banner-wrapper -->
	<div class="top-banner clearfix">

		<div class="left-container pull-left half-wrap" style="background-image:url('<?php echo $cat_image ?>')">
            <div class="category-color" style="background: <?php echo $color1 ?>;
                background: -moz-linear-gradient(-45deg, <?php echo $color1 ?> 0%, <?php echo $color2 ?> 100%);
                background: -webkit-linear-gradient(-45deg, <?php echo $color1 ?> 0%,<?php echo $color2 ?> 100%);
                background: linear-gradient(135deg, <?php echo $color1 ?> 0%,<?php echo $color2 ?> 100%);
                filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='<?php echo $color1 ?>', endColorstr='<?php echo $color2 ?>',GradientType=1 );">
            </div>
			<div class="left-inner pull-right half-inner">
				<div class="inner-wrap">
					<!-- Start Bredcrumb -->
					<div class="breadcrumb-wrapper">

						<ol vocab="http://schema.org/" typeof="BreadcrumbList" class="clearfix">
							<?php spinal_breadcrumb(); ?>
						</ol>
					</div>

					<!-- End Bredcrumb -->
					<div class="row">
						<!-- Start search wrapper -->
						<div class="search-wrapper col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<form class="searchbox" action="<?php echo get_term_link($category[0]) ?>" method="GET">
								<input type="text" name="s" placeholder="Search for articles...">
								<input type="submit">
							</form>
						</div>
						<!-- End search wrapper -->
					</div>
					<br>
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<h2><?php the_title() ?></h2>
							<p>
								<?php echo nl2br($postMeta['short-description'][0]) ?>
							</p>
                            <div class="article-author">
                                <?php
                                if(isset($postMeta['post-author'][0]) && $postMeta['post-author'][0] == 'on') {
                                    $userinfo = new user_info();
                                    $info = $userinfo->get($post->post_author);
                                    $enc_id = $userinfo->encrypt($info['ID']);
                                    $profile = page('member').'?u='.$enc_id;
                                    ?>
                                    Article writen by  <a href="<?php echo $profile ?>"><?php echo $info['name'] ?></a>
                                    <?php
                                }
                                 ?>
                            </div>
						</div>
						<!-- start social wrapper -->
						<div class="social-wrapper col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<ul class=" clearfix">
								<li><a href="http://www.facebook.com/share.php?u=<?php echo $link ?>" class="fb social-share js-share"><i class="fa fa-facebook"></i>Share</a></li>
								<li><a href="<?php echo "https://twitter.com/home?status=".get_the_title() ?> <?php echo $link ?>" class="tw social-share js-share"><i class="fa fa-twitter"></i>Tweet</a></li>
								<li><a href="<?php echo "https://pinterest.com/pin/create/button/?url=$link&media=$image&description=$title" ?>" class="pt social-share js-share"><i class="fa fa-pinterest-p"></i>Pin it</a></li>
								<li><a href="<?php echo "https://plus.google.com/share?url=$link" ?>" class="gl social-share js-share"><i class="fa fa-google-plus"></i>Google <span>+</span></a></li>
								<li><a href="<?php echo "http://tumblr.com/widgets/share/tool?canonicalUrl=$link" ?>" class="tb social-share js-share"><i class="fa fa-tumblr"></i>Tumblr</a></li>
							</ul>
						</div>
						<!-- end social wrapper -->
					</div>
				</div>
				<!-- end inner wrap -->
			</div>

		</div>
		<div class="right-container pull-right half-wrap"  style="background-image:url('<?php echo $featured_image ?>')">
			<div class="half-inner">
			</div>
		</div>

	</div>
	<!-- End top-banner-wrapper -->

	<!-- start main content wrapper -->
	<div class="main-content-wrapp container clearfix">

		<div class="row">

			<!-- start sidebar -->
			<div class="sidebar col-lg-4 col-md-5 col-sm-5 col-xs-12 pull-right">
				<div class="sidebar-inner">
					<!-- start categories wrapper -->
					<div class="categories-wrapper">
						<style>
						<?php
                        $args = array(
                        'type'                     => 'post',
                        'child_of'                 => 0,
                        'parent'                   => '',
                        'orderby'                  => 'name',
                        'order'                    => 'ASC',
                        'hide_empty'               => 1,
                        'hierarchical'             => 1,
                        'taxonomy'                 => 'category',
                        'pad_counts'               => false

                        );

                        $categories = get_categories( $args );
                        $i = 1;
                        foreach ($categories as $category) :
                            $meta = get_term_meta($category->cat_ID);
                            $color = $meta['color1'][0];
                            $textcolor = $meta['color-article-text'][0];
                            $ideaCategory = $meta['post-ideas'][0];
                            if($ideaCategory != 'on') :
                            ?>
				        	.color<?= $i; ?>:hover:after {
				        		width: 100%;
								background: <?php echo $textcolor;?> ;
							}
                            <?php
                            endif;
							$i++;
                        endforeach;
                        ?>
                        </style>
						<h3>Categories</h3>
						<ul class="category-container">
                            <?php
                            $args = array(
                            'type'                     => 'post',
                            'child_of'                 => 0,
                            'parent'                   => '',
                            'orderby'                  => 'name',
                            'order'                    => 'ASC',
                            'hide_empty'               => 1,
                            'hierarchical'             => 1,
                            'taxonomy'                 => 'category',
                            'pad_counts'               => false

                            );

                            $categories = get_categories( $args );
                            $i = 1;
                            foreach ($categories as $category) :
                                $meta = get_term_meta($category->cat_ID);
                                $color = $meta['color1'][0];
                                $textcolor = $meta['color-article-text'][0];
                                $ideaCategory = $meta['post-ideas'][0];
                                if($ideaCategory != 'on') :
                                ?>
							        <li><i style="background:<?php echo $textcolor ?>!important;"></i><a class="<?php echo 'color'.$i; ?>" href="<?php echo get_term_link($category) ?>"><?php echo $category->name ?></a></li>
                                <?php
                                endif;
								$i++;
                            endforeach;
                            ?>
						</ul>
					</div>
					<!-- end categories wrapper  -->

					<!-- start popular wrapper -->
					<div class="article-preview margin-top-25">
						<h3>Popular</h3>

						<ul class="preview-container clearfix">
                            <?php
                            /* POPULAR POSTS
                            ----------------------------------------------------------------------- */
                            query_posts("meta_key=post_views_count&orderby=meta_value_num&order=DESC&posts_per_page=4&category__in={$catID}");
                            // query_posts("orderby=comment_count&order=DESC&posts_per_page=4&category__in={$catID}");
                        	if (have_posts()) : while (have_posts()) : the_post(); ?>
                            <li>
								<a href="<?php the_permalink(); ?>" class="clearfix row" >
									<div class="col-md-6 col-sm-6 col-xs-6 align-middle">
										<div class="inner">
											<img src="<?php echo featured_image($post, 'spinal-thumb-300') ?>" alt="img">
										</div>
									</div>
									<div class="col-md-6 col-sm-6 col-xs-6 align-middle">
										<span class="title"><?php the_title() ?></span>
									</div>
								</a>
							</li>

                        	<?php
                        	endwhile; endif;
                        	wp_reset_query();
                            /* END POPULAR POSTS
                            ----------------------------------------------------------------------- */
                            ?>
						</ul>

					</div>
					<!-- end popular wrapper -->

					<!-- start latest wrapper -->
					<div class="article-preview margin-top-25">
						<h3>Latest</h3>

						<ul class="preview-container clearfix">
                            <?php
                            /* POPULAR POSTS
                            ----------------------------------------------------------------------- */
                            query_posts("orderby=date&order=DESC&posts_per_page=4&category__in={$catID}");
                            if (have_posts()) : while (have_posts()) : the_post(); ?>
                            <li>
                                <a href="<?php the_permalink(); ?>" class="clearfix row" >
                                    <div class="col-md-6 col-sm-6 col-xs-6 align-middle">
                                        <div class="inner">
                                            <img src="<?php echo featured_image($post, 'spinal-thumb-300') ?>" alt="img">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6 align-middle">
                                        <span class="title"><?php the_title() ?></span>
                                    </div>
                                </a>
                            </li>

                            <?php
                            endwhile; endif;
                            wp_reset_query();
                            /* END POPULAR POSTS
                            ----------------------------------------------------------------------- */
                            ?>
						</ul>

					</div>
					<!-- end latest wrapper -->

					<!-- start newsletter -->

					<div class="newsletter-wrapper margin-top-25">
						<h3>Subscribe to our newsletter</h3>

						<form class="subscribe-form" method="post">

							<input type="email" name="email" class="subscribe-input" placeholder="Enter your email address">
							<button type="submit" class="subscribe-submit">Subscribe <i class="fa fa-angle-right"></i></button>
						</form>

					</div>
					<!-- end newsletter -->
				</div>
			</div>
			<!-- end sidebar -->

			<!-- start main content -->
			<div class="main-content col-lg-8 col-md-7 col-sm-7 col-xs-12 pull-left">
				<div class="body">
					<?php the_content() ?>
				</div>

				<div class="social-wrapper">
					<ul class=" clearfix">
						<li><a href="<?php echo "https://www.facebook.com/sharer/sharer.php?u=$link" ?>" class="fb js-share"><i class="fa fa-facebook"></i>Share</a></li>
						<li><a href="<?php echo "https://twitter.com/home?status=".get_the_title() ?>" class="tw js-share"><i class="fa fa-twitter"></i>Tweet</a></li>
						<li><a href="<?php echo "https://pinterest.com/pin/create/button/?url=$link&media=$image&description=$title" ?>" class="pt js-share"><i class="fa fa-pinterest-p"></i>Pin it</a></li>
						<li><a href="<?php echo "https://plus.google.com/share?url=$link" ?>" class="gl js-share"><i class="fa fa-google-plus"></i>Google <span>+</span></a></li>
						<li><a href="<?php echo "http://tumblr.com/widgets/share/tool?canonicalUrl=$link" ?>" class="tb js-share"><i class="fa fa-tumblr"></i>Tumblr</a></li>
					</ul>
				</div>
                <?php include_once('comments.php') ?>
			</div>
			<!-- end main content -->

		</div>

	</div>
	<!-- end main content wrapper -->
</div>