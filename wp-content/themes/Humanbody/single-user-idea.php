<?php
global $post;
$featuredImage = featured_image($post, 'spinal-full-featured');

$postMeta = get_post_meta($post->ID);
$featuredAlign = $postMeta['align-featured'][0];
$pageTemplates = get_all_page_templates();
$url = $_GET['s'] ? get_site_url().'?s='.$_GET['s'] : $pageTemplates['page-blog.php']['url'];


$category = get_the_category();
$meta = get_term_meta($category[0]->cat_ID);
$color1 = $meta['color1'][0];
$color2 = $meta['color2'][0];
$cat_image = $meta['image'][0];
$post_meta = get_post_meta($post->ID);
$featured_image = featured_image($post, 'large');


$link = get_the_permalink($post->ID);
$title = get_the_title().' | '.get_bloginfo( 'name');
$image = $featured_image;


$custom_fields = get_post_custom($post->ID);
$banner1_link = $custom_fields['ba_banner1_link'];
$banner1_img = unserialize($custom_fields['ba_banner1_image'][0]);
$banner2_link = $custom_fields['ba_banner2_link'];
$banner2_img = unserialize($custom_fields['ba_banner2_image'][0]);

 ?>
 <div id="content">

 	<!-- start what-can section  -->
 	<div class="what-can-section secondary-subpage " <?php echo $featured_image ? 'style="background-image:url('.$featured_image.');"' : '' ?>>
 		<!-- start what-can top inner -->
 		<div class="what-can-top-inner">
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
 						<h1><?php the_title() ?></h1>

 						<p>
 							<?php the_description() ?>
 						</p>

 						<div class="header-posted-by">
 							Posted by <strong><?php echo $post_meta['idea-author'][0] ?></strong>
 						</div>

 					</div>
 					<!-- End description wrapper -->
 				</div>
 			</div>
 		</div>
 		<!-- end what-can top inner -->

 		<!-- start what-can top inner -->
 		<div class="what-can-bottom-inner">

 			<div class="container">
 				<div class="row">
 					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
 						<h4>The Idea</h4>
 						<p>
 						    <?php the_content() ?>
 						</p>
 					</div>

                    <!-- start social wrapper -->
                    <div class="social-wrapper col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <ul class=" clearfix">
                            <li><a href="<?php echo "https://www.facebook.com/sharer/sharer.php?u=$link" ?>" class="fb social-share js-share"><i class="fa fa-facebook"></i>Share</a></li>
                            <li><a href="<?php echo "https://twitter.com/home?status=".get_the_title() ?>  <?php echo $link ?>" class="tw social-share js-share"><i class="fa fa-twitter"></i>Tweet</a></li>
                            <li><a href="<?php echo "https://pinterest.com/pin/create/button/?url=$link&media=$image&description=$title" ?>" class="pt social-share js-share"><i class="fa fa-pinterest-p"></i>Pin it</a></li>
                            <li><a href="<?php echo "https://plus.google.com/share?url=$link" ?>" class="gl social-share js-share"><i class="fa fa-google-plus"></i>Google <span>+</span></a></li>
                            <li><a href="<?php echo "http://tumblr.com/widgets/share/tool?canonicalUrl=$link" ?>" class="tb social-share js-share"><i class="fa fa-tumblr"></i>Tumblr</a></li>
                        </ul>
                    </div>
                    <!-- end social wrapper -->
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

 					<div class="article-banner ">
                        <?php 
                            if(!empty($banner1_img['url'])){ ?>
                                <a target="_blank" style="background: url(<?php echo $banner1_img['url'] ?>);" href="<?php echo $banner1_link[0] ?>"></a>
                            <?php }
                        ?>
                        <?php 
                            if(!empty($banner2_img['url'])){ ?>
                                <a target="_blank" style="background: url(<?php echo $banner2_img['url'] ?>);" href="<?php echo $banner2_link[0] ?>"></a>
                            <?php }
                        ?>
                    </div>
 				    <?php include_once('comments.php') ?>
 					<!-- end comment form -->
 				</div>

 			</div>
 		</div>

 	</div>
 	<!-- start comment section -->

 </div>
